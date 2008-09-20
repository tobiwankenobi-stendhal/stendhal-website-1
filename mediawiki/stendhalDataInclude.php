<?php
if (!defined('MEDIAWIKI')) {
	die("<b>Stendhal Data Include</b> is a MediaWiki extension not intended to be used on its own.");
}

require_once($IP.'/../scripts/xml.php');
require_once($IP.'/../scripts/items.php');

$wgExtensionFunctions[] = "stendhalDataIncludeSetup";
$wgExtensionCredits['other'][] = array(
	'version' => '0.1',
	'name' => 'StendhalDataInclude',
	'author' => 'Hendrik Brummermann',
	'url' => 'http://arianne.sf.net',
	'description' => 'Include Stendhal Data<br>'
		. '&lt;item&gt;rod of the gm&lt;item&gt;<br>'
		. 'info="default" <em>or</em> type="icon" <em>or</em> type="stats"<br>'
		. 'type="default" <em>or</em> type="mouseover" <em>or</em> type="include"<br>'
);

/*
object(Item)#256 (6) {
  ["name"]=>
  string(13) "rod of the gm"
  ["description"]=>
  string(129) "You see a rod of the GM.  This rod is reserved for those with special powers, be careful not to let it fall into the wrong hands."
  ["class"]=>
  string(4) "club"
  ["gfx"]=>
  string(59) "itemimage.php?url=data/sprites/items/club/rod_of_the_gm.png"
  ["attributes"]=>
  array(3) {
    ["atk"]=>
    string(3) "100"
    ["def"]=>
    string(3) "100"
    ["rate"]=>
    string(1) "2"
  }
  ["equipableat"]=>
  NULL
}
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

function stendhalDataIncludeItem($input, $argv, &$parser) {
	$item = getItemByName($input);
	var_dump($item);
	if ($item == NULL) {
		return '&lt;item not found&gt;';
	}

	return $item->description;
}

function stendhalDataIncludeSetup() {
	global $wgParser;
	$wgParser->setHook( 'item', 'stendhalDataIncludeItem' );
	$wgParser->ot['item'] = true;
}

?>
