#!/usr/bin/env python3
"""Promote a verified OTClient GitHub release to the website manifest."""

import json
import os
import re
import sys
import urllib.error
import urllib.request
from pathlib import Path


REPO = "Zealot-Server/otclient"
API = f"https://api.github.com/repos/{REPO}"
MANIFEST = Path("system/client-release.php")
E2E_JOBS = {
    "Release E2E - Linux x86_64",
    "Release E2E - Windows x86_64",
    "Release E2E - macOS Apple Silicon",
    "Release E2E - macOS Intel",
}
ASSETS = (
    ("macos", "arm64", "zip"),
    ("macos", "x86_64", "zip"),
    ("windows", "x86_64", "zip"),
    ("linux", "x86_64", "tar.gz"),
)


def api_get(path):
    token = os.environ.get("GH_TOKEN")
    if not token:
        raise ValueError("GH_TOKEN is required")
    request = urllib.request.Request(
        f"{API}/{path}",
        headers={
            "Authorization": f"Bearer {token}",
            "Accept": "application/vnd.github+json",
            "User-Agent": "zealot-client-release-sync",
            "X-GitHub-Api-Version": "2022-11-28",
        },
    )
    with urllib.request.urlopen(request, timeout=20) as response:
        return json.load(response)


def version_tuple(version):
    match = re.fullmatch(r"(?:v)?(\d+)\.(\d+)\.(\d+)", version)
    if not match:
        raise ValueError(f"Unsupported release version: {version}")
    return tuple(int(part) for part in match.groups())


def release_assets(release):
    tag = release["tag_name"]
    version_tuple(tag)
    if not tag.startswith("v") or release["draft"] or release["prerelease"]:
        raise ValueError("Only published stable vX.Y.Z releases are eligible")
    sha = release["target_commitish"]
    if not re.fullmatch(r"[0-9a-f]{40}", sha):
        raise ValueError("Release must point to an exact commit SHA")
    version = tag[1:]
    by_name = {asset["name"]: asset for asset in release["assets"]}
    if len(by_name) != len(release["assets"]):
        raise ValueError("Release contains duplicate asset names")
    result = {}
    for platform, architecture, extension in ASSETS:
        name = f"zealot-client-{version}-{platform}-{architecture}.{extension}"
        asset = by_name.get(name)
        expected_url = f"https://github.com/{REPO}/releases/download/{tag}/{name}"
        if not asset or asset.get("size", 0) <= 0:
            raise ValueError(f"Missing or empty release asset: {name}")
        if asset.get("browser_download_url") != expected_url:
            raise ValueError(f"Unexpected download URL for {name}")
        digest = asset.get("digest", "")
        if not re.fullmatch(r"sha256:[0-9a-f]{64}", digest):
            raise ValueError(f"Missing SHA256 digest for {name}")
        result[(platform, architecture)] = (expected_url, digest[7:])
    return version, sha, result


def verified_run(runs, jobs, sha):
    valid_runs = [
        run for run in runs["workflow_runs"]
        if run["head_sha"] == sha
        and run["head_branch"] == "main"
        and run["event"] == "push"
        and run["conclusion"] == "success"
    ]
    if not valid_runs:
        raise ValueError("No successful main branch push CI run for release commit")
    passed = {job["name"] for job in jobs["jobs"] if job["conclusion"] == "success"}
    missing = E2E_JOBS - passed
    if missing:
        raise ValueError(f"Required client tests did not pass: {', '.join(sorted(missing))}")


def render_manifest(version, assets):
    lines = [
        "<?php", "", "/**", " * Public client packages maintained by Zealot.",
        " *", " * Keep this manifest in Git so the website publishes only the exact assets",
        " * produced by the matching GitHub release, together with their checksums.",
        " */", "return [", f"  'version' => '{version}',",
    ]
    for platform, architectures in (("macos", ("arm64", "x86_64")),
                                    ("windows", ("x86_64",)), ("linux", ("x86_64",))):
        lines.append(f"  '{platform}' => [")
        for architecture in architectures:
            url, digest = assets[(platform, architecture)]
            lines.extend((
                f"    '{architecture}' => [", "      'url' =>", f"        '{url}',",
                f"      'sha256' => '{digest}',", "    ],",
            ))
        lines.append("  ],")
    return "\n".join(lines + ["];", ""])


def update_manifest(release, runs, jobs, manifest_path=MANIFEST):
    version, sha, assets = release_assets(release)
    current = manifest_path.read_text()
    match = re.search(r"'version' => '(\d+\.\d+\.\d+)'", current)
    if not match:
        raise ValueError("Cannot read current client manifest version")
    if version_tuple(version) < version_tuple(match.group(1)):
        raise ValueError("Refusing to downgrade the published client")
    if version_tuple(version) == version_tuple(match.group(1)):
        return False
    verified_run(runs, jobs, sha)
    manifest_path.write_text(render_manifest(version, assets))
    return True


def main():
    release = api_get("releases/latest")
    version, sha, _ = release_assets(release)
    current = MANIFEST.read_text()
    match = re.search(r"'version' => '(\d+\.\d+\.\d+)'", current)
    if not match:
        raise ValueError("Cannot read current client manifest version")
    if version_tuple(version) <= version_tuple(match.group(1)):
        print(f"No newer release than {match.group(1)}")
        return
    runs = api_get(f"actions/workflows/ci.yml/runs?head_sha={sha}&branch=main&status=success&per_page=100")
    candidates = [run for run in runs["workflow_runs"] if run["event"] == "push" and run["head_sha"] == sha]
    if not candidates:
        raise ValueError("No successful main branch push CI run for release commit")
    for run in candidates:
        jobs = api_get(f"actions/runs/{run['id']}/jobs?per_page=100")
        try:
            verified_run({"workflow_runs": [run]}, jobs, sha)
        except ValueError:
            continue
        if update_manifest(release, {"workflow_runs": [run]}, jobs):
            print(f"Updated website manifest to {version} from CI run {run['id']}")
        return
    raise ValueError("No successful CI run passed all four release E2E jobs")


if __name__ == "__main__":
    try:
        main()
    except (ValueError, KeyError, urllib.error.URLError) as error:
        print(f"Client release sync failed: {error}", file=sys.stderr)
        sys.exit(1)
