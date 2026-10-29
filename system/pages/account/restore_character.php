<?php
/**
 * Restore a character that is still within the deletion grace period.
 */
defined('MYAAC') or die('Direct access not allowed!');

$errors = [];
$playerName = isset($_POST['restore_name']) ? stripslashes($_POST['restore_name']) : '';

if (!isset($_POST['restorecharactersave']) || $_POST['restorecharactersave'] != 1) {
    $errors[] = t('character.restore.invalid_request', 'Invalid character restoration request.');
} elseif (!Validator::characterName($playerName)) {
    $errors[] = t('character.restore.invalid_name', 'Character name contains illegal characters.');
} elseif (!$db->hasColumn('players', 'deletion')) {
    $errors[] = t('character.restore.unavailable', 'Character restoration is not available on this server.');
} else {
    $player = new OTS_Player();
    $player->find($playerName);

    if (!$player->isLoaded()) {
        $errors[] = t('character.restore.not_found', 'Character does not exist.');
    } elseif ($player->getAccount()->getId() != $account_logged->getId()) {
        $errors[] = t('character.restore.not_yours', 'This character is not on your account.');
    } elseif (!$player->isDeleted()) {
        $errors[] = t('character.restore.not_deleted', 'This character is not scheduled for deletion.');
    } else {
        $deletionDeadline = (int) $player->getCustomField('deletion');
        if ($deletionDeadline <= time()) {
            $errors[] = t('character.restore.expired', 'The 30-day restoration period for this character has expired.');
        } else {
            $player->setCustomField('deletion', 0);
            $account_logged->logAction('Restored character <b>' . $player->getName() . '</b>.');
            $twig->display('success.html.twig', [
                'title' => t('character.restore.success_title', 'Character Restored'),
                'description' => t('character.restore.success_description', 'The character <b>{name}</b> is available again.', ['name' => htmlspecialchars($player->getName(), ENT_QUOTES, 'UTF-8')]),
            ]);
            return;
        }
    }
}

$twig->display('error_box.html.twig', ['errors' => $errors]);
