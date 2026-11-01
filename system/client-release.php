<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.2',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.2/zealot-client-1.0.2-macos-arm64.zip',
      'sha256' => '58961f7d0ed8da426410fc134ff9d4d699fbf70f3cce099192b867592a2086e4',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.2/zealot-client-1.0.2-macos-x86_64.zip',
      'sha256' => '3df7feac1d4319febfa1fc5cf6fec160f343f633a60663561ba58a5685233af6',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.2/zealot-client-1.0.2-windows-x86_64.zip',
      'sha256' => 'cbce11a78a5f1978d6bb2ab32d64d3c47abf045e5ce9e81a067e8bc6802224be',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.2/zealot-client-1.0.2-linux-x86_64.tar.gz',
      'sha256' => '6d5ddb8d16786ea62d64c473274fe8b85ff70c7df48f23c0eee9c5a38882c996',
    ],
  ],
];
