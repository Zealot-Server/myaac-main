import importlib.util
import tempfile
import unittest
from pathlib import Path


SCRIPT = Path(__file__).resolve().parents[1] / "tools/sync_client_release.py"
SPEC = importlib.util.spec_from_file_location("sync_client_release", SCRIPT)
sync = importlib.util.module_from_spec(SPEC)
SPEC.loader.exec_module(sync)


def fixture(version="1.0.5"):
    sha = "a" * 40
    assets = []
    for platform, architecture, extension in sync.ASSETS:
        name = f"zealot-client-{version}-{platform}-{architecture}.{extension}"
        assets.append({
            "name": name,
            "size": 100,
            "digest": "sha256:" + "b" * 64,
            "browser_download_url":
                f"https://github.com/{sync.REPO}/releases/download/v{version}/{name}",
        })
    release = {"tag_name": f"v{version}", "draft": False, "prerelease": False,
               "target_commitish": sha, "assets": assets}
    runs = {"workflow_runs": [{"head_sha": sha, "head_branch": "main",
                                "event": "push", "conclusion": "success"}]}
    jobs = {"jobs": [{"name": name, "conclusion": "success"} for name in sync.E2E_JOBS]}
    return release, runs, jobs


class ClientReleaseSyncTests(unittest.TestCase):
    def setUp(self):
        self.temp = tempfile.TemporaryDirectory()
        self.addCleanup(self.temp.cleanup)
        self.manifest = Path(self.temp.name) / "client-release.php"
        old, _, _ = fixture("1.0.4")
        _, _, assets = sync.release_assets(old)
        self.original = sync.render_manifest("1.0.4", assets)
        self.manifest.write_text(self.original)

    def test_promotes_only_verified_release(self):
        release, runs, jobs = fixture()
        self.assertTrue(sync.update_manifest(release, runs, jobs, self.manifest))
        self.assertIn("'version' => '1.0.5'", self.manifest.read_text())
        self.assertIn("zealot-client-1.0.5-linux-x86_64.tar.gz", self.manifest.read_text())

    def test_same_release_does_not_change_manifest(self):
        release, runs, jobs = fixture("1.0.4")
        self.assertFalse(sync.update_manifest(release, runs, jobs, self.manifest))
        self.assertEqual(self.original, self.manifest.read_text())

    def test_missing_package_is_rejected(self):
        release, runs, jobs = fixture()
        release["assets"].pop()
        with self.assertRaisesRegex(ValueError, "Missing or empty"):
            sync.update_manifest(release, runs, jobs, self.manifest)
        self.assertEqual(self.original, self.manifest.read_text())

    def test_failed_e2e_job_is_rejected(self):
        release, runs, jobs = fixture()
        jobs["jobs"][0]["conclusion"] = "failure"
        with self.assertRaisesRegex(ValueError, "Required client tests"):
            sync.update_manifest(release, runs, jobs, self.manifest)
        self.assertEqual(self.original, self.manifest.read_text())

    def test_downgrade_is_rejected(self):
        release, runs, jobs = fixture("1.0.3")
        with self.assertRaisesRegex(ValueError, "downgrade"):
            sync.update_manifest(release, runs, jobs, self.manifest)

    def test_release_requires_exact_sha_and_digest(self):
        release, _, _ = fixture()
        release["target_commitish"] = "main"
        with self.assertRaisesRegex(ValueError, "exact commit"):
            sync.release_assets(release)
        release, _, _ = fixture()
        release["assets"][0]["digest"] = ""
        with self.assertRaisesRegex(ValueError, "SHA256"):
            sync.release_assets(release)


if __name__ == "__main__":
    unittest.main()
