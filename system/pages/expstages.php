<?php
/**
 * Experience stages
 */
defined('MYAAC') or die('Direct access not allowed!');

$title = t('intro.rates');

require_once LIBS . 'zealot_stages.php';

$stagesFile = rtrim($config['server_path'], '/\\') . '/data/stages.lua';
$stagesContent = is_readable($stagesFile) ? file_get_contents($stagesFile) : '';
$groups = [
  'Experience' => myaac_parse_stages_block($stagesContent, 'experienceStages'),
  'Skills' => myaac_parse_stages_block($stagesContent, 'skillsStages'),
  'Magic Level' => myaac_parse_stages_block($stagesContent, 'magicLevelStages'),
];
?>
<div class="TableContainer">
	<div class="CaptionContainer">
		<div class="CaptionInnerContainer">
			<span class="CaptionEdgeLeftTop" style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightTop" style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionBorderTop" style="background-image:url(<?= $template_path ?>/images/global/content/table-headline-border.gif);"></span>
			<span class="CaptionVerticalLeft" style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-vertical.gif);"></span>
			<div class="Text"><?= htmlspecialchars(t('intro.rates'), ENT_QUOTES, 'UTF-8') ?></div>
			<span class="CaptionVerticalRight" style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-vertical.gif);"></span>
			<span class="CaptionBorderBottom" style="background-image:url(<?= $template_path ?>/images/global/content/table-headline-border.gif);"></span>
			<span class="CaptionEdgeLeftBottom" style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
			<span class="CaptionEdgeRightBottom" style="background-image:url(<?= $template_path ?>/images/global/content/box-frame-edge.gif);"></span>
		</div>
	</div>
	<table class="Table3" cellpadding="0" cellspacing="0">
		<tbody>
		<tr>
			<td>
				<div class="InnerTableContainer">
					<table style="width:100%;">
						<?php foreach ($groups as $label => $stages): ?>
						<tr><td class="LabelV"><?= htmlspecialchars(
        t('intro.group.' . strtolower(str_replace(' ', '_', $label))),
        ENT_QUOTES,
        'UTF-8'
      ) ?></td></tr>
							<tr>
								<td>
									<table class="TableContent" width="100%">
										<tr bgcolor="<?= $config['vdarkborder'] ?>">
										<td class="white"><b><?= htmlspecialchars(t('intro.from_level'), ENT_QUOTES, 'UTF-8') ?></b></td>
										<td class="white"><b><?= htmlspecialchars(t('intro.to_level'), ENT_QUOTES, 'UTF-8') ?></b></td>
										<td class="white"><b><?= htmlspecialchars(t('intro.rate'), ENT_QUOTES, 'UTF-8') ?></b></td>
										</tr>
										<?php if (empty($stages)): ?>
											<tr bgcolor="<?= $config['lightborder'] ?>"><td colspan="3"><?= htmlspecialchars(
  t('intro.no_stages'),
  ENT_QUOTES,
  'UTF-8'
) ?></td></tr>
										<?php else: ?>
											<?php foreach ($stages as $index => $stage): ?>
												<tr bgcolor="<?= getStyle($index + 1) ?>">
													<td><?= htmlspecialchars($stage['minlevel']) ?></td>
													<td><?= $stage['maxlevel'] === null
               ? htmlspecialchars(t('intro.and_above'), ENT_QUOTES, 'UTF-8')
               : htmlspecialchars($stage['maxlevel']) ?></td>
													<td><?= htmlspecialchars($stage['multiplier']) ?>x</td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
									</table>
								</td>
							</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
</div>
