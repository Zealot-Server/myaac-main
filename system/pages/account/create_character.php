<?php
/**
 * Create character
 *
 * @package   MyAAC
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @author    OpenTibiaBR
 * @copyright 2023 MyAAC
 * @link      https://github.com/opentibiabr/myaac
 */
defined('MYAAC') or die('Direct access not allowed!');

$character_name = isset($_POST['name']) ? stripslashes(ucwords(strtolower($_POST['name']))) : null;
$character_sex = isset($_POST['sex']) ? (int)$_POST['sex'] : null;
$character_vocation = isset($_POST['vocation']) ? (int)$_POST['vocation'] : null;
$character_town = isset($_POST['town']) ? (int)$_POST['town'] : null;

$character_created = false;
$save = isset($_POST['save']) && $_POST['save'] == 1;
$errors = array();
if($save) {
	require_once LIBS . 'CreateCharacter.php';
	$createCharacter = new CreateCharacter();

	$character_created = $createCharacter->doCreate($character_name, $character_sex, $character_vocation, $character_town, $account_logged, $errors);
}

if(count($errors) > 0) {
	$twig->display('error_box.html.twig', array('errors' => $errors));
}

if(!$character_created) {
	$accountOperatingSystem = $account_logged->getCustomField('operating_system');
	$accountOperatingSystem = array('windows' => 'Windows', 'linux' => 'Linux', 'macos' => 'macOS')[$accountOperatingSystem]
		?? t('account.operating_system_unknown', 'Not specified');
	$twig->display('account.create_character.html.twig', array(
		'account_operating_system' => $accountOperatingSystem,
		'name' => $character_name,
		'sex' => $character_sex,
		'vocation' => $character_vocation,
		'town' => $character_town,
		'save' => $save,
		'errors' => $errors
	));
}
