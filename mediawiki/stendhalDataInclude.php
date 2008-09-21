<?php
if (!defined('MEDIAWIKI')) {
	die("<b>Stendhal Data Include</b> is a MediaWiki extension not intended to be used on its own.");
}

require_once($IP.'/../scripts/xml.php');
require_once($IP.'/../scripts/items.php');

$wgExtensionFunctions[] = "stendhalDataIncludeSetup";
$wgExtensionCredits['other'][] = array(
	'version' => '0.5',
	'name' => 'StendhalDataInclude',
	'author' => 'Hendrik Brummermann',
	'url' => 'http://arianne.sf.net',
	'description' => 'Include Stendhal Data<br>'
		. '&lt;item&gt;rod of the gm&lt;item&gt;<br>'
		. 'info="default" <em>or</em> type="icon" <em>or</em> type="stats"<br>'
		. 'type="default" <em>or</em> type="mouseover" <em>or</em> type="include"<br>'
);


/**
 * helper function to read the item with this name using the Stendhal website code
 */
function getItemByName($name) {
	$items = getItems();

	foreach($items as $item) {
		if($item->name==$name) {
			return $item;
		}
	}
	return NULL;
}


/**
 * reads the icon of a Stendhal items
 */
function stendhalDataIncludeItemIconOnly($item) {
	$res .= '<span class="stendhalItemIcon"><a href="/?id=content/scripts/item&name=' . urlencode($item->name) . '&exact">';
	$res .= '<img src="/' . htmlspecialchars($item->gfx) . '" />';
	$res .= '</a></span>';
	return $res;
}

/**
 * reads stats and optionally the icon and the description of Stendhal items
 */
function stendhalDataIncludeItemStats($item, $argv) {
	$res .= '<div class="stendhalItem"><span class="stendhalItemIconNameBanner">';

	if (!isset($argv['info'])) {
		$res .= stendhalDataIncludeItemIconOnly($item);
	}

	if (!isset($argv['info']) || ($argv['info'] == 'stats')) {
		$res .= '<a href="/?id=content/scripts/item&name=' . urlencode($item->name) . '&exact">';
		$res .= $item->name;
		$res .= '</a>';
	}
	$res .= '</span>';
		
	if (!isset($argv['info']) || ($argv['info'] == 'stats')) {
		$res .= '<br />';
		$res .= 'Class: ' . htmlspecialchars(ucfirst($item->class)) . '<br />';
		foreach($item->attributes as $label=>$data) {
			if ($label != "quantity") {
				$res .= htmlspecialchars(ucfirst($label)) . ': ' . htmlspecialchars($data) . '<br />';
			}
		}
	}
	if (!isset($argv['info'])) {
		$res .= '<br />' . $item->description . '<br />';
	}

	$res .= '</div>';
	return $res;
}

/**
 * includes data about Stendhal items
 */
function stendhalDataIncludeItem($input, $argv, &$parser) {
	$res = '';
	$item = getItemByName($input);
	if ($item == NULL) {
		return '&lt;item "' . htmlspecialchars($input) . '" not found&gt;';
	}

	if (isset($argv['info']) && ($argv['info'] == 'icon')) {
		$res .= stendhalDataIncludeItemIconOnly($item);
		$block = false;
	} else {
		$res .= stendhalDataIncludeItemStats($item, $argv);
		$block = true;
	}

	$link = '/?id=content/scripts/item&name=' . urlencode($item->name) . '&exact';
	$res = stendhalDataIncludeAddMoveoverBoxIfDesired($argv, $link, $item->name, $res);

	return $res;
}


/**
 * wraps the hmtl into a box if the "type" parameter is either undefined
 * or set to "mouseover".
 *
 * @param $argv arguments in the tag
 * @param $link link to include in the normal page
 * @param $name text to show on the link
 * @param $html the html code to put into the box
 */
function stendhalDataIncludeAddMoveoverBoxIfDesired($argv, $link, $name, $html) {
	$res = $html;
	if (!isset($argv['type']) || ($argv['type'] == 'mouseover')) {
		$res = '';
		$res .= '<a href="' . $link . '"';
		$res .= ' onmouseover="return overlib(\''.rawurlencode($html).'\', FGCOLOR, \'#000\', BGCOLOR, \'#FFF\',';
		$res .= 'DECODE, FULLHTML';
		$res .= ');" onmouseout="return nd();" class="stendhalItemLink">';
		$res .= htmlspecialchars($name);
		$res .= '</a>';
	}
	return $res;
}


/**
 * setup the parser by telling it about the tags we can 
 * handle and include the required java script
 */
function stendhalDataIncludeSetup() {
	global $wgParser, $wgScriptPath, $wgOut;
	$wgParser->setHook( 'item', 'stendhalDataIncludeItem' );

	$wgOut->addHTML('<script type="text/javascript" src="' . $wgScriptPath . '/extensions/overlibmws/overlibmws.js"></script>');
	$wgOut->addHTML('<script type="text/javascript" src="' . $wgScriptPath . '/extensions/overlibmws/overlibmws_filter.js" /></script>');
}

?>
