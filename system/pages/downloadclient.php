<?php
defined('MYAAC') or die('Direct access not allowed!');
$title = t('download.title', 'Download Client');

$getpage_download = $_GET['step'] ?? '';
$download_subtopic = $_GET['subtopic'] ?? PAGE;
$platform = $_GET['platform'] ?? '';

$downloads = [
    'macos' => [
        'url' => trim((string) ($config['client_link_macos'] ?? '')),
        'sha256' => trim((string) ($config['client_macos_sha256'] ?? '')),
    ],
    'windows' => [
        'url' => trim((string) ($config['client_link_windows'] ?? ($config['client_link'] ?? ''))),
    ],
    'linux' => [
        'url' => trim((string) ($config['client_link_linux'] ?? '')),
    ],
];

if ($platform === 'macos') {
    $macDownloadUrl = $downloads['macos']['url'];
    $macSha256 = $downloads['macos']['sha256'];
    ?>
    <div class="zealot-download-platform">
        <p class="zealot-download-platform__back"><a href="?subtopic=downloadclient">&larr; Escolher outro sistema</a></p>
        <h1>Zealot Client para macOS</h1>
        <p class="zealot-download-platform__lead">Versão para Macs com Apple Silicon (M1, M2, M3 e M4), com macOS 11 ou superior.</p>

        <div class="zealot-download-platform__download">
            <?php if ($macDownloadUrl !== ''): ?>
                <a class="zealot-download-platform__button" href="<?= htmlspecialchars($macDownloadUrl, ENT_QUOTES, 'UTF-8') ?>">Baixar Zealot Client para macOS</a>
                <p>Versão <?= htmlspecialchars((string) (config('client') / 100), ENT_QUOTES, 'UTF-8') ?> &middot; arquivo ZIP</p>
                <?php if ($macSha256 !== ''): ?>
                    <p class="zealot-download-platform__checksum"><strong>SHA-256:</strong> <code><?= htmlspecialchars($macSha256, ENT_QUOTES, 'UTF-8') ?></code></p>
                <?php endif; ?>
            <?php else: ?>
                <span class="zealot-download-platform__button zealot-download-platform__button--disabled">Download para macOS em preparação</span>
                <p>O instalador será publicado nesta página assim que terminar a validação da primeira versão.</p>
            <?php endif; ?>
        </div>

        <section class="zealot-download-platform__notice" aria-labelledby="mac-security-notice">
            <h2 id="mac-security-notice">Aviso de segurança do macOS</h2>
            <p>Esta primeira versão é distribuída em ZIP e ainda não possui assinatura Apple. Por isso, o macOS pode informar que não consegue confirmar o desenvolvedor na primeira abertura.</p>
            <p>Baixe o cliente somente por este site. Não desative o Gatekeeper nem execute cópias recebidas por fontes desconhecidas.</p>
        </section>

        <section class="zealot-download-platform__steps" aria-labelledby="mac-install-steps">
            <h2 id="mac-install-steps">Como instalar e abrir</h2>
            <ol>
                <li>Abra o arquivo ZIP baixado na pasta <strong>Downloads</strong>.</li>
                <li>Arraste <strong>Zealot Client.app</strong> para a pasta <strong>Applications</strong>.</li>
                <li>Abra o aplicativo pela pasta Applications.</li>
                <li>Se o macOS bloquear a primeira abertura, vá em <strong>Ajustes do Sistema &gt; Privacidade e Segurança</strong>, localize a mensagem sobre o Zealot Client e clique em <strong>Abrir Mesmo Assim</strong>.</li>
                <li>Confirme em <strong>Abrir</strong>. Nas próximas execuções, o cliente abrirá normalmente.</li>
            </ol>
            <p>O cliente já vem configurado para o Zealot: basta informar seu e-mail e senha para jogar.</p>
        </section>
    </div>
    <?php
} elseif (empty($getpage_download)) {
    ?>
    <div class="TableContainer">
        <div class="CaptionContainer">
            <div class="CaptionInnerContainer">
                <span class="CaptionEdgeLeftTop"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionEdgeRightTop"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionBorderTop"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                <span class="CaptionVerticalLeft"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                <div class="Text"><?= t('download.title', 'Download Client') ?></div>
                <span class="CaptionVerticalRight"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                <span class="CaptionBorderBottom"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                <span class="CaptionEdgeLeftBottom"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionEdgeRightBottom"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
            </div>
        </div>
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
                                                    <h1><?= t('download.official_client', 'Official {server} Client', ['server' => configLua('serverName')]) ?></h1>
                                                    <p class="zealot-client-download__intro">Escolha seu sistema operacional para ver o download e as instruções de instalação.</p>
                                                    <div class="zealot-client-download__platforms">
                                                        <a class="zealot-client-download__platform-card" href="?subtopic=downloadclient&amp;platform=macos">
                                                            <strong>macOS</strong>
                                                            <span>Apple Silicon (M1/M2/M3/M4)</span>
                                                            <small>Ver instruções</small>
                                                        </a>
                                                        <?php if ($downloads['windows']['url'] !== ''): ?>
                                                            <a class="zealot-client-download__platform-card" href="<?= htmlspecialchars($downloads['windows']['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                                                                <strong>Windows</strong>
                                                                <span>Cliente para Windows</span>
                                                                <small>Baixar cliente</small>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="zealot-client-download__platform-card zealot-client-download__platform-card--disabled">
                                                                <strong>Windows</strong>
                                                                <span>Cliente para Windows</span>
                                                                <small>Em preparação</small>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if ($downloads['linux']['url'] !== ''): ?>
                                                            <a class="zealot-client-download__platform-card" href="<?= htmlspecialchars($downloads['linux']['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                                                                <strong>Linux</strong>
                                                                <span>Cliente para Linux</span>
                                                                <small>Baixar cliente</small>
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="zealot-client-download__platform-card zealot-client-download__platform-card--disabled">
                                                                <strong>Linux</strong>
                                                                <span>Cliente para Linux</span>
                                                                <small>Em preparação</small>
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
                                                <td class="LabelV"><?= t('download.disclaimer', 'Disclaimer') ?></td>
                                            </tr>
                                            <tr>
                                                <td><?= t('download.disclaimer_copy', 'The software and any related documentation is provided "as is" without warranty of any kind. The entire risk arising out of use of the software remains with you. In no event shall Zealot be liable for any damages to your computer or loss of data.') ?>
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
    <?php
}

if ($download_subtopic == 'downloadclient' and $getpage_download == 'downloadagreement') {
    ?>
    <p>Before you can download the client program please read the Service Agreement and state if you agree to it by clicking on the appropriate button below.</p>

    <div class="TableContainer">
        <div class="CaptionContainer">
            <div class="CaptionInnerContainer">
                <span class="CaptionEdgeLeftTop"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionEdgeRightTop"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionBorderTop"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                <span class="CaptionVerticalLeft"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                <div class="Text">Service Agreement</div>
                <span class="CaptionVerticalRight"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-vertical.gif);"></span>
                <span class="CaptionBorderBottom"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/table-headline-border.gif);"></span>
                <span class="CaptionEdgeLeftBottom"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
                <span class="CaptionEdgeRightBottom"
                      style="background-image:url(<?= $template_path; ?>/images/global/content/box-frame-edge.gif);"></span>
            </div>
        </div>
        <table class="Table1" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td>
                    <div class="InnerTableContainer"><p>This agreement describes the terms on which Zealot offers you access to an account for being able to play the online role playing game. By creating an account or downloading the client software you accept the terms and conditions below and state that you are of full legal age in your country or have the permission of your parents to play this game.</p>
                        <p>You agree that the use of the software is at your sole risk. We provide the software, the game, and all other services "as is". We disclaim all warranties or conditions of any kind, expressed, implied or statutory, including without limitation the implied warranties of title, non-infringement, merchantability and fitness for a particular purpose. We do not ensure continuous, error-free, secure or virus-free operation of the software, the game, or your account.</p>
                        <p>We are not liable for any lost profits or special, incidental or consequential damages arising out of or in connection with the game, including, but not limited to, loss of data, items, accounts, or characters from errors, system downtime, or adjustments of the gameplay.</p>
                        <p>While you are playing the game, you must abide by some rules ("Game Rules") that are stated on this homepage. If you break any of these rules, your account may be removed and all other services terminated immediately.</p>
                        <p>Zealot is neither willing nor required to take part in out-of-court dispute resolution.</p>
                        <p>By creating an account or downloading the client software, you also accept the terms and conditions stated in the BattlEye End-User Licence Agreement.</p>
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
                 style="background-image:url(<?= $template_path; ?>/images/global/buttons/sbutton.gif)">
                <div onmouseover="MouseOverBigButton(this);" onmouseout="MouseOutBigButton(this);">
                    <div class="BigButtonOver"
                         style="background-image:url(<?= $template_path; ?>/images/global/buttons/sbutton_over.gif);"></div>
                    <input class="BigButtonText" type="submit" value="<?= t('download.agree', 'I agree') ?>"></div>
            </div>
        </form>
    </center>
    <?php
}
