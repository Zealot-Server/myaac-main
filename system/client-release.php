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
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0-6baaf98/zealot-client-1.0.0-macos-arm64.zip',
      'sha256' => 'f8f50e6315543836eb798e2d0f61914288552e8d016c70688717bb72f290f2b3',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0-6baaf98/zealot-client-1.0.0-macos-x86_64.zip',
      'sha256' => 'e869f69ecc3a01d1b97254b64a2dea977ea3c5b0df2514305429daa3c4f5f0bd',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0-8d698f3/zealot-client-1.0.0-windows-x86_64.zip',
      'sha256' => '17e1a326c4e94ea849bb09542f71b51cded06e7840ac8b282b3c6add8ee20ca0',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.0-6baaf98/zealot-client-1.0.0-linux-x86_64.tar.gz',
      'sha256' => '685be66cbf689c009820cebb9d994853e4cde886c579cb3e8534f5130afaf36f',
    ],
  ],
];
