<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2010 The Arianne Project

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

class MyCharactersPage extends Page {

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>My Characters'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if(!isset($_SESSION['account'])) {
			startBox("Login Required");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url='.rewriteURL('/account/mycharacters.html').'">login</a> to see a list of your characters.</p>';
			endBox();
			return;
		}
		
		startBox("Character Selector");

		$players = getCharactersForUsername($_SESSION['account']->username);
		if(sizeof($players)==0) {
			echo '<div>Please <a href="'.rewriteURL('/account/create-character.html').'">create a new character</a>.</div>';
		} else {
			echo '<div>Click on a character below to play or <a href="'.rewriteURL('/account/create-character.html').'">create a new character</a>.</div>';
			echo '<div style="height: '.((floor(count($players) / 7) + 1) * 140) .'px">';
			foreach($players as $p) {
				echo '<div class="onlinePlayer characterHeight">';
				echo '  <a class = "onlineLink" href="'.STENDHAL_FOLDER.'/index.php/stendhal-starter.jnlp?id=content/account/starter&amp;character='.surlencode($p->name).'">';
				echo '  <img src="'.rewriteURL('/images/outfit/'.surlencode($p->outfit).'.png').'" alt="">';
				echo '  <span class="block">'.htmlspecialchars(utf8_encode($p->name)).'</span></a>';
				echo '  <span class="block">Level: '.$p->level.'</span>';
				echo '  <span class="block">Age: '.intval($p->age/60).' h</span>';
				echo '  <span class="block"><a class = "characterLink" href="'.rewriteURL('/character/'.htmlspecialchars(utf8_encode($p->name)).'.html').'">Details</a></span>';
				echo '</div>';
			}
			echo '</div>';
		}
		endBox();

		if(sizeof($players) > 0) {
			startBox("Note");
			?>
			<p>Starting the Stendhal client may take a minute. Please be patient after clicking on your character.</p>
			<p>On the very first start additional Stendhal will need to download some additional files. Subsequent starts will be a lot faster.</p>
			<?php 
			endBox();
			startBox("Trouble Shooting");
			?>
			<p>You will be asked to open a file called stendhal-starter.jnlp with Java Webstart or Java Network Launched Application.
			If the application is unknown, please download and install <a href="http://java.com">Java</a>.</p>

			<p>If you have trouble to join the game, please ask in the <a href="/chat/">#arianne-chat</a> or create a 
			<a href="https://sourceforge.net/tracker/?group_id=1111&amp;atid=201111">support ticket</a>.</p>
			<?php
			endBox();
		}
	}
}
$page = new MyCharactersPage();
?>