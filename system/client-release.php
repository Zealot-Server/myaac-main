<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.3',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.3/zealot-client-1.0.3-macos-arm64.zip',
      'sha256' => '8437aabed8a4ab8f41a70bd9491c5109566aa586778c3a8d53a7fbb5c44dcd0e',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.3/zealot-client-1.0.3-macos-x86_64.zip',
      'sha256' => 'c3b057b52eedbd732b687d4c4f7e2fccd2b42fd8917277c6476a806adb0cde9e',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.3/zealot-client-1.0.3-windows-x86_64.zip',
      'sha256' => 'a3da54c30d453a48e2f058294befc5bba00b49dbf5639749d1fe5bdece6d9bd8',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.3/zealot-client-1.0.3-linux-x86_64.tar.gz',
      'sha256' => '38f54a0a857af46261b3de08cf57293e7651018cf4f910ffbde5fdc8de3a2634',
    ],
  ],
];
