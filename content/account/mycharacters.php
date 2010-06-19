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
		echo '<title>Starter'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if(!isset($_SESSION['username'])) {
			startBox("Login Required");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url='.rewriteURL('/account/mycharacters.html').'">login</a> to see a list of your characters.</p>';
			endBox();
			return;
		}
		
		startBox("Character Selector");
		$players = getCharactersForUsername($_SESSION['username']);
		if(sizeof($players)==0) {
			echo 'You have no characters.';
		}
		foreach($players as $p) {
			echo '<div class="onlinePlayer">';
			echo '  <a href="'.STENDHAL_FOLDER.'/index.php/stendhal-starter.jnlp?id=content/account/starter&amp;character='.surlencode($p->name).'">';
			echo '  <img src="'.rewriteURL('/images/outfit/'.surlencode($p->outfit).'.png').'" alt="">';
			echo '  <span class="block">'.htmlspecialchars(utf8_encode($p->name)).'</span></a>';
			echo '  <span class="block">Level: '.$p->level.'</span>';
			echo '  <span class="block">Age: '.intval($p->age/60).' h</span>';
			echo '  <span class="block"><a href="'.rewriteURL('/character/'.htmlspecialchars(utf8_encode($p->name)).'.html').'">Details</a></span>';
			echo '</div>';
		}
		endBox();
		startBox("Note");
		?>
		<p>Starting the Stendhal client may take a minute. Please be patient after clicking on your character.</p>
		<p>You need <a href="http://java.com"> Jave in order to play. On the very first start additional files of
		Stendhal will be downloaded. Subsequent starts will be a lot faster.</p>
		<?php endBox();
	}
}
$page = new MyCharactersPage();
?>