<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.5',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.5/zealot-client-1.0.5-macos-arm64.zip',
      'sha256' => '0b669dd0ee35bc81433fa3aaede3cec3fbc2e5713e129bba078245b49646f233',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.5/zealot-client-1.0.5-macos-x86_64.zip',
      'sha256' => '1d40b0f5f20bb636cd7126bc0046f23d6f8b983c19ae3f66d5035b745c29a3b9',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.5/zealot-client-1.0.5-windows-x86_64.zip',
      'sha256' => 'eb55293fe89d38badc81be4b30eaaeae4b34b34cd187ef460281782a8a70de5c',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.5/zealot-client-1.0.5-linux-x86_64.tar.gz',
      'sha256' => 'e3827cd45af17958c931a82c45dc5c708ab8a7d1482e80858131daa154e51ab2',
    ],
  ],
];
