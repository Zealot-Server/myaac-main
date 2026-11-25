<?php

/**
 * Public client packages maintained by Zealot.
 *
 * Keep this manifest in Git so the website publishes only the exact assets
 * produced by the matching GitHub release, together with their checksums.
 */
return [
  'version' => '1.0.8',
  'macos' => [
    'arm64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.8/zealot-client-1.0.8-macos-arm64.zip',
      'sha256' => '4278def0a4c5854413d1f8e529450bbb4d540ca8a0394bf880008a37c739956a',
    ],
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.8/zealot-client-1.0.8-macos-x86_64.zip',
      'sha256' => 'a222f137d86db1ded2d41fda57cc816a9a700d7ae422c6af784f1b21055ea625',
    ],
  ],
  'windows' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.8/zealot-client-1.0.8-windows-x86_64.zip',
      'sha256' => '313dc707e426777baf3ceb93f0da27df92f1f544655bd8f916aba68e69ce944b',
    ],
  ],
  'linux' => [
    'x86_64' => [
      'url' =>
        'https://github.com/Zealot-Server/otclient/releases/download/v1.0.8/zealot-client-1.0.8-linux-x86_64.tar.gz',
      'sha256' => 'ce2bbb5035a838401759ef6feb8728b8f93b63963cc26675b3ffde361c36b78d',
    ],
  ],
];
