<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.1',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.1/zealot-client-1.0.1-macos-arm64.zip',
      'sha256' => '88b4522ee40404df71fe8698bbe193397b90551486dd643402a9dd13bf663980',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.1/zealot-client-1.0.1-macos-x86_64.zip',
      'sha256' => '3f6557413bc0634853b2efc25c369559ab1b1802e145f1c5e5c1585fae5c57ab',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.1/zealot-client-1.0.1-windows-x86_64.zip',
      'sha256' => 'a0f2612e2a06b121659932d3350909f3ddf08904f65604f8bb339139c21a27d4',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.1/zealot-client-1.0.1-linux-x86_64.tar.gz',
      'sha256' => '285e9a29780a31a7b47efb8445719d22bc89e2c38b2be239f18f822d4adb357a',
    ],
  ],
];
