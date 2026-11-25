<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.6',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.6/zealot-client-1.0.6-macos-arm64.zip',
      'sha256' => 'ca7115e49ce2571fb2357cbe9f1a17b3a9dc1b23a69698dd878d1f8e5490b520',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.6/zealot-client-1.0.6-macos-x86_64.zip',
      'sha256' => '9da7ba1ddbcd60c38500a36c26360f1304fa6a0f8c37d7b0223d4527a868aa6e',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.6/zealot-client-1.0.6-windows-x86_64.zip',
      'sha256' => '3407053600ac295ae5687e3b8ab29291f6097ec90feaa7248973e5fd6478f408',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.6/zealot-client-1.0.6-linux-x86_64.tar.gz',
      'sha256' => '2ff7e2e62b5e8a076adab1db6dd8c39e9d7b446cc6a5839418f0ead948562eca',
    ],
  ],
];
