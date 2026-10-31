<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.0',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0/zealot-client-1.0.0-macos-arm64.zip',
      'sha256' => 'c8cb984984c11b33c26b575ebe6e7d6bb9e16c7785dcd3bee18ab360ea7d815f',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0/zealot-client-1.0.0-macos-x86_64.zip',
      'sha256' => 'c7ab4ae13aaa2e261ff402412cec0008dc736c1493c98aa7dfb21bedf6e57fd8',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0/zealot-client-1.0.0-windows-x86_64.zip',
      'sha256' => '8b96033653001306c15d13330900ceada724032caa24adb24d59b17f75b30c01',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0/zealot-client-1.0.0-linux-x86_64.tar.gz',
      'sha256' => '19ccc8e0756eb7bd0cfcf9e9aa7d004f8f7a0672c8b8e32b77a14107496d6bb8',
    ],
  ],
];
