<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.7',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.7/zealot-client-1.0.7-macos-arm64.zip',
      'sha256' => '834f15814c44819abe0bc83ac295e7aa0333e5c3b74d5e12f0b2a2483f8bb216',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.7/zealot-client-1.0.7-macos-x86_64.zip',
      'sha256' => 'a8de6b6812320b23638783fa864f8326d58fc86b59c4075953de78eee456dcb3',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.7/zealot-client-1.0.7-windows-x86_64.zip',
      'sha256' => '3703cf74b92267df851d10b3f43e559a2ecb88556650c4583f3f879834f835ca',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.7/zealot-client-1.0.7-linux-x86_64.tar.gz',
      'sha256' => '4ece9657408566a612667e64d180025eb6f9fbd3caf8720c8c542bcc7a0b2c04',
    ],
  ],
];
