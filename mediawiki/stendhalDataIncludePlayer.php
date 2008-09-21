<?php
if (!defined('MEDIAWIKI')) {
	die("<b>Stendhal Data Include</b> is a MediaWiki extension not intended to be used on its own.");
}

require_once($IP.'/../scripts/xml.php');
require_once($IP.'/../scripts/mysql.php');
require_once($IP.'/../scripts/players.php');


/**
 * reads the icon of a Stendhal players
 */
function stendhalDataIncludePlayerIconOnly($player) {
	$res .= '<span class="stendhalPlayerIcon"><a href="/?id=content/scripts/character&name=' . urlencode($player->name) . '&exact">';
	$res .= '<img src="/createoutfit.php?outfit=' . htmlspecialchars($player->outfit) . '" />';
	$res .= '</a></span>';
	return $res;
}

/**
 * reads stats and optionally the icon and the description of Stendhal players
 */
function stendhalDataIncludePlayerStats($player, $argv) {
	$res .= '<div class="stendhalPlayer"><span class="stendhalPlayerIconNameBanner">';

	if (!isset($argv['info'])) {
		$res .= stendhalDataIncludePlayerIconOnly($player);
	}

	if (!isset($argv['info']) || ($argv['info'] == 'stats')) {
		$res .= '<a href="/?id=content/scripts/player&character=' . urlencode($player->name) . '&exact">';
		$res .= $player->name;
		$res .= '</a>';
	}
	$res .= '</span>';
		
	if (!isset($argv['info']) || ($argv['info'] == 'stats')) {
		$res .= '<br />';
		$res .= 'Class: ' . htmlspecialchars(ucfirst($player->class)) . '<br />';
		foreach($player->attributes as $label=>$data) {
			if ($label != "quantity") {
				$res .= htmlspecialchars(ucfirst($label)) . ': ' . htmlspecialchars($data) . '<br />';
			}
		}
	}
	if (!isset($argv['info'])) {
		$res .= '<br />' . $player->description . '<br />';
	}

	$res .= '</div>';
	return $res;
}

/**
 * includes data about Stendhal players
 */
function stendhalDataIncludePlayer($input, $argv, &$parser) {
	$res = '';
	connect();
	$player = getPlayer($input);
	disconnect();
	if ($player == NULL) {
		return '&lt;player "' . htmlspecialchars($input) . '" not found&gt;';
	}

	if (isset($argv['info']) && ($argv['info'] == 'icon')) {
		$res .= stendhalDataIncludePlayerIconOnly($player);
	} else {
		$res .= stendhalDataIncludePlayerStats($player, $argv);
	}

	$link = '/?id=content/scripts/player&character=' . urlencode($player->name) . '&exact';
	$res = stendhalDataIncludeAddMoveoverBoxIfDesired($argv, $link, $player->name, "stendhalPlayerLink", $res);

	return $res;
}

?>
