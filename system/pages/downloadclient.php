<?php
defined('MYAAC') or die('Direct access not allowed!');
$title = t('download.title', 'Download Client');

$getpage_download = $_GET['step'] ?? '';
$download_subtopic = $_GET['subtopic'] ?? PAGE;
$platform = $_GET['platform'] ?? '';
$clientRelease = require BASE . 'system/client-release.php';
$releaseVersion = (string) ($clientRelease['version'] ?? '');

$downloads = [
  'macos' => [
    'arm64' => [
      'url' => trim((string) ($clientRelease['macos']['arm64']['url'] ?? '')),
      'sha256' => trim((string) ($clientRelease['macos']['arm64']['sha256'] ?? '')),
    ],
    'x86_64' => [
      'url' => trim((string) ($clientRelease['macos']['x86_64']['url'] ?? '')),
      'sha256' => trim((string) ($clientRelease['macos']['x86_64']['sha256'] ?? '')),
    ],
  ],
  'windows' => [
    'url' => trim((string) ($clientRelease['windows']['x86_64']['url'] ?? '')),
    'sha256' => trim((string) ($clientRelease['windows']['x86_64']['sha256'] ?? '')),
  ],
  'linux' => [
    'url' => trim((string) ($clientRelease['linux']['x86_64']['url'] ?? '')),
    'sha256' => trim((string) ($clientRelease['linux']['x86_64']['sha256'] ?? '')),
  ],
];

if ($platform === 'macos') {
  $macDownloads = [
    'arm64' => [
      'label' => t('download.macos.arm64.label', 'Apple Silicon'),
      'description' => t('download.macos.arm64.description', 'Macs with Apple Silicon'),
      'button' => t('download.macos.arm64.download', 'Download for Apple Silicon'),
    ],
    'x86_64' => [
      'label' => t('download.macos.x86_64.label', 'Intel'),
      'description' => t('download.macos.x86_64.description', 'Macs with an Intel processor'),
      'button' => t('download.macos.x86_64.download', 'Download for Intel'),
    ],
  ]; ?>
    <div class="zealot-download-platform">
        <p class="zealot-download-platform__back"><a href="?subtopic=downloadclient">&larr; <?= t(
          'download.macos.back',
          'Choose another system'
        ) ?></a></p>
        <h1><?= t('download.macos.title', 'Zealot Client for macOS') ?></h1>
        <p class="zealot-download-platform__lead"><?= t(
          'download.macos.lead',
          'Choose the package for your Mac processor. Both versions require macOS 13.3 or later.'
        ) ?></p>

        <div class="zealot-download-platform__architectures">
            <?php foreach ($macDownloads as $architecture => $metadata): ?>
                <?php $download = $downloads['macos'][$architecture]; ?>
                <section id="<?= $architecture === 'arm64'
                  ? 'apple-silicon'
                  : 'intel' ?>" class="zealot-download-platform__download">
                    <h2><?= htmlspecialchars($metadata['label'], ENT_QUOTES, 'UTF-8') ?></h2>
                    <p><?= htmlspecialchars($metadata['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php if ($download['url'] !== ''): ?>
                        <a class="zealot-download-platform__button" href="<?= htmlspecialchars(
                          $download['url'],
                          ENT_QUOTES,
                          'UTF-8'
                        ) ?>"><?= htmlspecialchars($metadata['button'], ENT_QUOTES, 'UTF-8') ?></a>
                        <p><?= t('download.version', 'Version') ?> <?= htmlspecialchars(
   $releaseVersion,
   ENT_QUOTES,
   'UTF-8'
 ) ?> &middot; <?= t('download.macos.archive', 'ZIP archive') ?></p>
                    <?php else: ?>
                        <span class="zealot-download-platform__button zealot-download-platform__button--disabled"><?= t(
                          'download.macos.preparing',
                          'macOS download in preparation'
                        ) ?></span>
                        <p><?= t(
                          'download.macos.preparing_copy',
                          'The installer will be published on this page once the first version has finished validation.'
                        ) ?></p>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        </div>

        <section class="zealot-download-platform__notice" aria-labelledby="mac-security-notice">
            <h2 id="mac-security-notice"><?= t(
              'download.macos.security_title',
              'macOS security notice'
            ) ?></h2>
            <p><?= t(
              'download.macos.security_copy',
              'This first version is distributed as a ZIP and is not yet signed by Apple. Therefore, macOS may say it cannot verify the developer the first time you open it.'
            ) ?></p>
            <p><?= t(
              'download.macos.security_source',
              'Download the client only from this site. Do not disable Gatekeeper or run copies received from unknown sources.'
            ) ?></p>
        </section>

        <section class="zealot-download-platform__steps" aria-labelledby="mac-install-steps">
            <h2 id="mac-install-steps"><?= t(
              'download.macos.steps_title',
              'How to install and open'
            ) ?></h2>
            <ol>
                <li><?= t(
                  'download.macos.step_1',
                  'Open the downloaded ZIP file in the <strong>Downloads</strong> folder.'
                ) ?></li>
                <li><?= t(
                  'download.macos.step_2',
                  'Drag <strong>Zealot Client.app</strong> to the <strong>Applications</strong> folder.'
                ) ?></li>
                <li><?= t(
                  'download.macos.step_3',
                  'Open the application from the Applications folder.'
                ) ?></li>
                <li><?= t(
                  'download.macos.step_4',
                  'If macOS blocks the first launch, go to <strong>System Settings &gt; Privacy &amp; Security</strong>, find the Zealot Client message, and click <strong>Open Anyway</strong>.'
                ) ?></li>
                <li><?= t(
                  'download.macos.step_5',
                  'Confirm by clicking <strong>Open</strong>. The client will open normally on subsequent launches.'
                ) ?></li>
            </ol>
            <p><?= t(
              'download.macos.ready',
              'The client is already configured for Zealot: enter your email and password to play.'
            ) ?></p>
        </section>
    </div>
    <?php
} elseif ($platform === 'windows' || $platform === 'linux') {

  $platformDownload = $downloads[$platform];
  $isWindows = $platform === 'windows';
  $platformName = $isWindows ? 'Windows' : 'Linux';
  $archiveFormat = $isWindows ? 'ZIP' : 'TAR.GZ';
  $title = $isWindows
    ? t('download.windows.title', 'Zealot Client for Windows')
    : t('download.linux.title', 'Zealot Client for Linux');
  $lead = $isWindows
    ? t('download.windows.lead', 'This release is for 64-bit Windows computers.')
    : t('download.linux.lead', 'This release is a portable package for 64-bit Linux computers.');
  $steps = $isWindows
    ? [
      t(
        'download.windows.step_1',
        'Download the ZIP file and open it from your <strong>Downloads</strong> folder.'
      ),
      t('download.windows.step_2', 'Extract all files to a folder of your choice.'),
      t(
        'download.windows.step_3',
        'Open the extracted folder and run <strong>Zealot Client.exe</strong>.'
      ),
      t(
        'download.windows.step_4',
        'If Windows shows a security prompt, verify that the file was downloaded from this site before continuing.'
      ),
    ]
    : [
      t(
        'download.linux.step_1',
        'Download the TAR.GZ archive and extract it to a folder of your choice.'
      ),
      t('download.linux.step_2', 'Open a terminal in the extracted folder.'),
      t('download.linux.step_3', 'Run <strong>./run-zealot-client.sh</strong>.'),
      t(
        'download.linux.step_4',
        'If needed, make the launcher executable first with <strong>chmod +x run-zealot-client.sh</strong>.'
      ),
    ];
  ?>
    <div class="zealot-download-platform">
        <p class="zealot-download-platform__back"><a href="?subtopic=downloadclient">&larr; <?= t(
          'download.macos.back',
          'Choose another system'
        ) ?></a></p>
        <h1><?= $title ?></h1>
        <p class="zealot-download-platform__lead"><?= $lead ?></p>

        <section class="zealot-download-platform__download">
            <h2><?= $platformName ?> <?= t('download.platform.64bit', '64-bit') ?></h2>
            <p><?= t('download.version', 'Version') ?> <?= htmlspecialchars(
   $releaseVersion,
   ENT_QUOTES,
   'UTF-8'
 ) ?> &middot; <?= $archiveFormat ?></p>
            <a class="zealot-download-platform__button" href="<?= htmlspecialchars(
              $platformDownload['url'],
              ENT_QUOTES,
              'UTF-8'
            ) ?>" target="_blank" rel="noopener"><?= t(
  'download.platform.available',
  'Download client'
) ?></a>
        </section>

        <section class="zealot-download-platform__steps" aria-labelledby="<?= $platform ?>-install-steps">
            <h2 id="<?= $platform ?>-install-steps"><?= t(
  'download.install.steps_title',
  'How to install and open'
) ?></h2>
            <ol>
                <?php foreach ($steps as $step): ?>
                    <li><?= $step ?></li>
                <?php endforeach; ?>
            </ol>
            <p><?= t(
              'download.install.ready',
              'The client is already configured for Zealot: enter your email and password to play.'
            ) ?></p>
        </section>
    </div>
    <?php
} elseif (empty($getpage_download)) { ?>
    <div class="TableContainer">
        <table class="Table5" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>
                    <div class="InnerTableContainer">
                        <table style="width:100%;">
                            <tbody>
                            <tr>
                                <td>
                                    <div class="TableContentContainer">
                                        <table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
                                            <tbody>
                                            <tr>
                                                <td class="zealot-client-download">
                                                    <h1><?= t(
                                                      'download.official_client',
                                                      'Official {server} Client',
                                                      ['server' => configLua('serverName')]
                                                    ) ?></h1>
                                                    <p class="zealot-client-download__intro"><?= t(
                                                      'download.intro',
                                                      'Choose your operating system to see the download and installation instructions.'
                                                    ) ?></p>
                                                    <div class="zealot-client-download__platforms">
                                                        <a class="zealot-client-download__platform-card" href="?subtopic=downloadclient&amp;platform=macos">
                                                            <strong>macOS</strong>
                                                            <span><?= t(
                                                              'download.macos.platforms',
                                                              'Apple Silicon (ARM64) / Intel (x86_64)'
                                                            ) ?></span>
                                                            <small><?= t(
                                                              'download.macos.instructions',
                                                              'View instructions'
                                                            ) ?></small>
                                                        </a>
                                                        <?php if (
                                                          $downloads['windows']['url'] !== ''
                                                        ): ?>
                                                            <a class="zealot-client-download__platform-card" href="?subtopic=downloadclient&amp;platform=windows">
                                                                <strong>Windows</strong>
                                                                <span><?= t(
                                                                  'download.platform.windows_label',
                                                                  'Windows Client'
                                                                ) ?></span>
                                                                <small><?= t(
                                                                  'download.platform.windows_details',
                                                                  'Windows 64-bit · View instructions'
                                                                ) ?></small>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="zealot-client-download__platform-card zealot-client-download__platform-card--disabled">
                                                                <strong>Windows</strong>
                                                                <span><?= t(
                                                                  'download.platform.windows_label',
                                                                  'Windows Client'
                                                                ) ?></span>
                                                                <small><?= t(
                                                                  'download.platform.preparing',
                                                                  'In preparation'
                                                                ) ?></small>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if (
                                                          $downloads['linux']['url'] !== ''
                                                        ): ?>
                                                            <a class="zealot-client-download__platform-card" href="?subtopic=downloadclient&amp;platform=linux">
                                                                <strong>Linux</strong>
                                                                <span><?= t(
                                                                  'download.platform.linux_label',
                                                                  'Linux Client'
                                                                ) ?></span>
                                                                <small><?= t(
                                                                  'download.platform.linux_details',
                                                                  'Linux 64-bit · View instructions'
                                                                ) ?></small>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="zealot-client-download__platform-card zealot-client-download__platform-card--disabled">
                                                                <strong>Linux</strong>
                                                                <span><?= t(
                                                                  'download.platform.linux_label',
                                                                  'Linux Client'
                                                                ) ?></span>
                                                                <small><?= t(
                                                                  'download.platform.preparing',
                                                                  'In preparation'
                                                                ) ?></small>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="TableContentContainer">
                                        <table class="TableContent" width="100%" style="border:1px solid #faf0d7;">
                                            <tbody>
                                            <tr>
                                                <td class="LabelV"><?= t(
                                                  'download.disclaimer',
                                                  'Disclaimer'
                                                ) ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= t(
                                                  'download.disclaimer_copy',
                                                  'The software and any related documentation is provided "as is" without warranty of any kind. The entire risk arising out of use of the software remains with you. In no event shall Zealot be liable for any damages to your computer or loss of data.'
                                                ) ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <?php }

if ($download_subtopic == 'downloadclient' and $getpage_download == 'downloadagreement') { ?>
    <p><?= t(
      'download.agreement_intro',
      'Before you can download the client program please read the Service Agreement and state if you agree to it by clicking on the appropriate button below.'
    ) ?></p>

    <div class="TableContainer">
        <div class="CaptionContainer">
            <div class="CaptionInnerContainer">
                <span class="CaptionEdgeLeftTop"
                      style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionEdgeRightTop"
                      style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionBorderTop"
                      style="background-image:url(<?= $template_path ?>/images/global/content/table-headline-border.gif);"></span>
                <span class="CaptionVerticalLeft"
                      style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-vertical.gif);"></span>
                <div class="Text"><?= t('download.agreement_title', 'Service Agreement') ?></div>
                <span class="CaptionVerticalRight"
                      style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-vertical.gif);"></span>
                <span class="CaptionBorderBottom"
                      style="background-image:url(<?= $template_path ?>/images/global/content/table-headline-border.gif);"></span>
                <span class="CaptionEdgeLeftBottom"
                      style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionEdgeRightBottom"
                      style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
            </div>
        </div>
        <table class="Table1" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>
                    <div class="InnerTableContainer"><p><?= t(
                      'download.agreement.paragraph_1',
                      'This agreement describes the terms on which Zealot offers you access to an account for being able to play the online role playing game. By creating an account or downloading the client software you accept the terms and conditions below and state that you are of full legal age in your country or have the permission of your parents to play this game.'
                    ) ?></p>
                        <p><?= t(
                          'download.agreement.paragraph_2',
                          'You agree that the use of the software is at your sole risk. We provide the software, the game, and all other services "as is". We disclaim all warranties or conditions of any kind, expressed, implied or statutory, including without limitation the implied warranties of title, non-infringement, merchantability and fitness for a particular purpose. We do not ensure continuous, error-free, secure or virus-free operation of the software, the game, or your account.'
                        ) ?></p>
                        <p><?= t(
                          'download.agreement.paragraph_3',
                          'We are not liable for any lost profits or special, incidental or consequential damages arising out of or in connection with the game, including, but not limited to, loss of data, items, accounts, or characters from errors, system downtime, or adjustments of the gameplay.'
                        ) ?></p>
                        <p><?= t(
                          'download.agreement.paragraph_4',
                          'While you are playing the game, you must abide by some rules ("Game Rules") that are stated on this homepage. If you break any of these rules, your account may be removed and all other services terminated immediately.'
                        ) ?></p>
                        <p><?= t(
                          'download.agreement.paragraph_5',
                          'Zealot is neither willing nor required to take part in out-of-court dispute resolution.'
                        ) ?></p>
                        <p><?= t(
                          'download.agreement.paragraph_6',
                          'By creating an account or downloading the client software, you also accept the terms and conditions stated in the BattlEye End-User Licence Agreement.'
                        ) ?></p>
                        <table style="width:100%;"></table>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <br>
    <center>
        <form action="?subtopic=downloadclient" method="post" style="padding:0px;margin:0px;">
            <div class="BigButton"
                 style="background-image:url(<?= $template_path ?>/images/global/buttons/sbutton.gif)">
                <div onmouseover="MouseOverBigButton(this);" onmouseout="MouseOutBigButton(this);">
                    <div class="BigButtonOver"
                         style="background-image:url(<?= $template_path ?>/images/global/buttons/sbutton_over.gif);"></div>
                    <input class="BigButtonText" type="submit" value="<?= t(
                      'download.agree',
                      'I agree'
                    ) ?>"></div>
            </div>
        </form>
    </center>
    <?php }
