<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2012 The Arianne Project

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'mycharacters.php';
require_once 'fb.php';
require_once 'createcharacter.php';

class GadgetPage extends Page {
	private $api;

	function __construct() {
		$this->api = new Facebook();
	}

	public function writeHttpHeader() {
		$this->writePageStart();
		$this->writeContent();
		$this->writePageEnd();
		return false;
	}

	function writePageStart() {
		echo '<!DOCTYPE html>';
		echo '<html><head>';
		echo '<title>Stendhal</title>';
		?>
		<style type="text/css">
.gadgetcanvas {

	width: 570px; 
	border: 1px solid black
	margin: 0;
	padding: 0;
	font-family: Arial, Helvetica, "Liberation Sans", "Lucida Sans", Sans-serif;
	font-size: 12pt;
}

.box {
	margin: 4px;
	padding: 0;
	border: solid 2px black;
	background-color: white;
}

.boxTitle {
	margin: 0;
	padding: 3px;
	color: white;
	font-weight: bolder;
	background-image: url("/data/gui/panelwood119.jpg");
	background-repeat: repeat;
	border: outset 2px brown;
}

.boxContent {
	color: Black;
	padding: 4px;
}

.onlinePlayer {
	width: 80px;
	text-align: center;
	float: left;
	font-size: 12px;
	font-weight: bold;
}

.characterHeight {
	height: 140px;
}

.onlineLink {
	text-decoration: none;
	color: black;
}
span.block {
	display: block;
	overflow-x: hidden;
}

.outfitpanel {border: 1px solid black; width: 8.5em; height: 256px; padding: 0em; float: left; margin-right: 2em; margin-bottom: 2em}
.prev, .next {float: left; margin-top: 2em}
.outfitpart {float: left; display: block; width:48px; height: 64px; background-position: 0 128px;}

		</style>
		<?php
		echo '</head><body>';
	}

	function writeContent() {

		// authenticate
		if (!isset($_SESSION['account'])) {

			// is this a request with authentication information?
			if (isset($_REQUEST['signed_request'])) {
				$accountLink = $this->api->createAccountLinkForSignedRequest();
			} else if (isset($_REQUEST['code'])) {
				$accountLink = $this->api->createAccountLink();
			}
			if (isset($accountLink)) {
				Account::loginOrCreateByAccountLink($accountLink);
			}

			// redirect to app authorisation page
			if (!isset($_SESSION['account'])) {
				echo '<span id="gadget-redirect" href="'.$this->api->getCanvasAuthUrl().'"></span>';
				return;
			}
		}


		// we are now authenticated

		echo '<div class="gadgetcanvas">';

		$createURL = '/?id=content/account/gadget&tab=create&social='.urlencode($_REQUEST['social']);

		$tab = 'mycharacters';
		if (isset($_REQUEST['tab'])) {
			$tab = $_REQUEST['tab'];
		}

		// If there are no characters, go to the char creation page
		if ($tab == 'mycharacters') {
			$players = getCharactersForUsername($_SESSION['account']->username);
			if (sizeof($players) == 0) {
				$tab = 'create';
			}
		}

		// handle character creation
		if ($tab == 'create') {
			$page = new CreateCharacterPage();
			$res = $page->process();
			if (!$res) {
				$page->show($createURL);
			} else {
				$tab = 'mycharacters';
			}
		}

		// handle character list
		if ($tab == 'mycharacters') {
			startBox('Links');
			echo '<a target="_blank" href="https://stendhalgame.org">Game Server Website</a>&nbsp;&nbsp;&nbsp;';
			echo '<a target="_blank" href="https://stendhalgame.org/world/atlas.html"> Atlas</a>&nbsp;&nbsp;&nbsp;';
			echo '<a target="_blank" href="https://stendhalgame.org/wiki/Stendhal_Manual/Controls_and_Game_Settings">Manual</a>&nbsp;&nbsp;&nbsp;';
			echo '<a target="_blank" href="https://stendhalgame.org/wiki/BeginnersGuide">Beginner\'s Guide</a>&nbsp;&nbsp;&nbsp;';
			endBox();
			$page = new MyCharactersPage();
			$page->writeCharacterList(6, $createURL);
		}

		echo '</div>';
	}

	function writePageEnd() {
		global $frame;
		echo '</div>';
		$frame->includeJs();
		echo '</body></html>';
	}
}
$page = new GadgetPage();
