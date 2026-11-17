<?php

declare(strict_types=1);

// Public release metadata used by the desktop client's update prompt.
// The source of truth is the same checksum manifest as the download page.
$release = require __DIR__ . '/system/client-release.php';
$version = $release['version'] ?? null;
$packages = [];

foreach (['macos', 'windows', 'linux'] as $platform) {
  foreach ($release[$platform] ?? [] as $architecture => $package) {
    $url = $package['url'] ?? null;
    $sha256 = $package['sha256'] ?? null;
    if (
      !is_string($url) ||
      !str_starts_with($url, 'https://github.com/Zealot-Server/otclient/releases/download/') ||
      !is_string($sha256) ||
      preg_match('/^[a-f0-9]{64}$/D', $sha256) !== 1
    ) {
      continue;
    }
    $packages[$platform][$architecture] = ['url' => $url, 'sha256' => $sha256];
  }
}

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=60');
echo json_encode(
  ['version' => $version, 'packages' => $packages],
  JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
);
