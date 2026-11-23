<?php
/**
 * Online
 *
 * @package   MyAAC
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @author    OpenTibiaBR
 * @copyright 2023 MyAAC
 * @link      https://github.com/opentibiabr/myaac
 */
defined('MYAAC') or die('Direct access not allowed!');
$title = 'Who is online?';

if($config['account_country'])
	require SYSTEM . 'countries.conf.php';

$promotion = $db->hasColumn('players', 'promotion') ? ', `players`.`promotion`' : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'name';
if(!in_array($order, array('country', 'name', 'level', 'vocation')))
	$order = $db->fieldName('name');
else if($order == 'country')
	$order = $db->tableName('accounts') . '.' . $db->fieldName('country');
else if($order == 'vocation')
	$order = '`players`.`vocation` ASC';

$skull_type = 'skull';
if($db->hasColumn('players', 'skull_type')) {
	$skull_type = 'skull_type';
}

$skull_time = 'skulltime';
if($db->hasColumn('players', 'skull_time')) {
	$skull_time = 'skull_time';
}

$outfit_addons = false;
$outfit = '';
if($config['online_outfit']) {
	$outfit = ', lookbody, lookfeet, lookhead, looklegs, looktype';
	if($db->hasColumn('players', 'lookaddons')) {
		$outfit .= ', lookaddons';
		$outfit_addons = true;
	}
}

$vocationGroups = [
	'all' => ['label' => 'All', 'ids' => []],
	'rookgaard' => ['label' => 'Rookgaard', 'ids' => [0]],
	'sorcerer' => ['label' => 'Sorcerers', 'ids' => [1, 5]],
	'druid' => ['label' => 'Druids', 'ids' => [2, 6]],
	'paladin' => ['label' => 'Paladins', 'ids' => [3, 7]],
	'knight' => ['label' => 'Knights', 'ids' => [4, 8]],
	'monk' => ['label' => 'Monks', 'ids' => [9, 10]],
];
$selectedVocation = (string)($_GET['vocation'] ?? 'all');
if (!isset($vocationGroups[$selectedVocation]))
	$selectedVocation = 'all';
$vocs = array_fill_keys(array_keys($vocationGroups), 0);
$outfitRenderer = null;
if ($config['online_outfit']) {
	require_once LIBS . 'ZealotMarket.php';
	$outfitRenderer = new ZealotMarket($db, $config);
}

if($db->hasTable('players_online')) // tfs 1.0
	$playersOnline = $db->query('SELECT `accounts`.`country`, `players`.`name`, `players`.`level`, `players`.`vocation`' . $outfit . $promotion . ', `players`.`' . $skull_time . '` as `skulltime`, `players`.`' . $skull_type . '` as `skull` FROM `accounts`, `players`, `players_online` WHERE `players`.`id` = `players_online`.`player_id` AND `accounts`.`id` = `players`.`account_id`  ORDER BY ' . $order);
else
	$playersOnline = $db->query('SELECT `accounts`.`country`, `players`.`name`, `players`.`level`, `players`.`vocation`' . $outfit . $promotion . ', `players`.`' . $skull_time . '` as `skulltime`, `players`.`' . $skull_type . '` as `skull` FROM `accounts`, `players` WHERE `players`.`online` > 0 AND `accounts`.`id` = `players`.`account_id`  ORDER BY ' . $order);

$players_data = array();
$explodeFlags = array();
$totalPlayers = 0;
$data = '';
foreach($playersOnline as $player){
	++$totalPlayers;
	$skull = '';
	if($config['online_skulls'])
	{
		if($player['skulltime'] > 0)
		{
			if($player['skull'] == 3)
				$skull = ' <img style="border: 0;" src="images/white_skull.gif"/>';
			elseif($player['skull'] == 4)
				$skull = ' <img style="border: 0;" src="images/red_skull.gif"/>';
			elseif($player['skull'] == 5)
				$skull = ' <img style="border: 0;" src="images/black_skull.gif"/>';
		}
	}

	if(isset($player['promotion'])) {
		if((int)$player['promotion'] > 0 && (int)$player['vocation'] >= 1 && (int)$player['vocation'] <= 4)
			$player['vocation'] += ($player['promotion'] * $config['vocations_amount']);
	}

	$vocationId = (int)$player['vocation'];
	$vocationGroup = null;
	foreach ($vocationGroups as $key => $definition) {
		if ($key !== 'all' && in_array($vocationId, $definition['ids'], true)) {
			$vocationGroup = $key;
			++$vocs[$key];
			break;
		}
	}
	if ($selectedVocation !== 'all' && $selectedVocation !== $vocationGroup)
		continue;
	$vocation_image = getVocationImage($vocationId);
	$realOutfit = $config['online_outfit'] ? $outfitRenderer->currentOutfitUrl([
		'looktype' => $player['looktype'] ?? 0,
		'addons' => $player['lookaddons'] ?? 0,
		'head' => $player['lookhead'] ?? 0,
		'body' => $player['lookbody'] ?? 0,
		'legs' => $player['looklegs'] ?? 0,
		'feet' => $player['lookfeet'] ?? 0,
	]) ?? $vocation_image : null;

	$players_data[] = array(
		'name' => getPlayerLink($player['name']),
		'player' => $player,
		'level' => $player['level'],
		'vocation' => $config['vocations'][$vocationId] ?? ([9 => 'Monk', 10 => 'Exalted Monk'][$vocationId] ?? 'Unknown'),
		'country_image' => $config['account_country'] ? getFlagImage($player['country']) : null,
		'outfit' => $realOutfit,
		'vocation_image' => $vocation_image,
		'skull' => $skull,
	);
}

$vocs['all'] = $totalPlayers;
$vocationFilters = [];
foreach ($vocationGroups as $key => $definition) {
	$query = $key === 'all' ? [] : ['vocation' => $key];
	$vocationFilters[] = [
		'key' => $key,
		'label' => $definition['label'],
		'count' => $vocs[$key],
		'url' => getLinkWithQuery('online', $query),
	];
}

$record = '';
if($config['online_record']){
	$record_timestamp = null;
	if($db->hasTable('server_record')) {
		$query =
			$db->query(
				'SELECT `record`, `timestamp` FROM `server_record` WHERE `world_id` = ' . (int)$config['lua']['worldId'] .
				' ORDER BY `record` DESC LIMIT 1');
	}else if($db->hasTable('server_config')) { // tfs 1.0
		$timestamp_field = $db->hasColumn('server_config', 'timestamp') ? '`timestamp`, ' : '';
		$query = $db->query('SELECT ' . $timestamp_field . '`value` as `record` FROM `server_config` WHERE `config` = ' . $db->quote('players_record'));
	}else{
		$query = NULL;
	}

	if(isset($query) && $query->rowCount() > 0){
		$result = $query->fetch();
		if(isset($result['timestamp'])) {
			$record_timestamp = is_numeric($result['timestamp']) ? (int)$result['timestamp'] : strtotime($result['timestamp']);
		}

		$record = '' . $result['record'] . ' players';
		if(!empty($record_timestamp)) {
			$record .= '<br><small>' . date('d/m/Y, H:i:s', $record_timestamp) . '</small>';
		}
	}
}

$twig->display('online.html.twig', array(
	'players' => $players_data,
	'totalPlayers' => $totalPlayers,
	'record' => $record,
	'current_date' => date('d/m/Y, H:i'),
	'vocs' => $vocs,
	'vocationFilters' => $vocationFilters,
	'selectedVocation' => $selectedVocation,
	'redSkullDay' => (int)configLua('dayKillsToRedSkull'),
	'redSkullWeek' => (int)configLua('weekKillsToRedSkull'),
	'redSkullMonth' => (int)configLua('monthKillsToRedSkull'),
));

//search bar
$twig->display('online.form.html.twig');
