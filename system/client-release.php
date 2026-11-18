<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.4',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.4/zealot-client-1.0.4-macos-arm64.zip',
      'sha256' => 'a705ec756600fde761ac32766bd77cabbe84080545039e963df0cb1a95e93ad1',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.4/zealot-client-1.0.4-macos-x86_64.zip',
      'sha256' => '5b64031048c3fc54a806a3b9a8360e9d1a148dba136fad50f67d7dd2ee09244f',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.4/zealot-client-1.0.4-windows-x86_64.zip',
      'sha256' => '0187ac27ca08f61af1ca1589b0c28af5167f2d830b3139eda21674b34ed4e867',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.4/zealot-client-1.0.4-linux-x86_64.tar.gz',
      'sha256' => '8465ab5d1d2998f510b3af7802d0d2fc98c3ce39e31a50040e06fe3cb1afe814',
    ],
  ],
];
