<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2009  Miguel Angel Blanch Lardin, The Arianne Project

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

class MainPage extends Page {

	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	function writeHttpHeader() {
		global $protocol;
		if ($protocol == 'https') {
			header('X-XRDS-Location: '.STENDHAL_LOGIN_TARGET.'/?id=content/account/openid-provider&xrds');
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>'.substr(STENDHAL_TITLE, strpos(STENDHAL_TITLE, ' ', 2) + 1).'</title>'."\n";
		echo '<link rel="alternate" type="application/rss+xml" title="Stendhal News" href="'.rewriteURL('/rss/news.rss').'" >'."\n";
		echo '<meta name="keywords" content="Stendhal, game, gra, Spiel, Rollenspiel, juego, role, gioco, online, open, source, multiplayer, roleplaying, Arianne, foss, floss, Adventurespiel, morpg, rpg">';
		echo '<meta name="description" content="Stendhal is a fun friendly and free multiplayer online adventure game. Start playing, get hooked... Get the source code, and add your own ideas...">';
	}

	function writeContentOld() {
?>
<div id="oneLineDescription">
	<a href="<?php echo(STENDHAL_LOGIN_TARGET.rewriteURL('/account/mycharacters.html'))?>" style="border:0">
	<img style="border:0" src="/images/playit.gif" alt="play stendhal" width="106px" height="45px"></a>
	<span>Stendhal is a fun friendly and free multiplayer online adventure game. Start playing, get hooked... Get the source code, and add your own ideas...</span>
</div>
<div id="newsArea">
	<?php
	foreach(getNews(' where news.active=1 ') as $i) {
		$i->show();
	}
	?>
</div>
<br>
<br>
<div>
	<?php startBox('More News');?>
	<ul class="menu">
		<li style="width: 100%"><a id="menuNewsArchive" href="<?php echo rewriteURL('/world/newsarchive.html');?>">Older news</a></li>
		<li style="width: 100%"><a id="menuNewsRss" href="<?php echo rewriteURL('/rss/news.rss');?>">RSS-Feed for this page</a></li>
		<li style="width: 100%"><a id="menuNewsTrade" href="/trade/">Harold's Trading Announcements</a></li>
	</ul>
	<?php
	endBox();
	?>
</div>
<?php
	}

	function writeContent() {
		if (isset($_REQUEST['test'])) {
			$this->writeStartPage();
		} else {
			$this->writeContentOld();
		}
	}

	function writeStartPage() {
		echo '<style type="text/css">';
		// general layout adjustments
		echo 'body {background-image: none} #container, .box {border: none} #container {width: auto; max-width: 970px} #topMenu {background-image: none}';

		// layout adjustments for the start page
		echo '#contentArea {margin: 51px 0 80px 190px;}';
		echo '#leftArea, #rightArea {display: none}';
		echo '.box {margin-bottom: 1em}';
		echo '</style>';

		// about stendhal
		echo '<div style="width: 55%; float: left">';
		startBox('<h1>Stendhal</h1>');
		echo '<p><b>Stendhal is a fun friendly and free multiplayer online adventure game. Start playing, get hooked... Get the source code, and add your own ideas...</b></p>';
		echo '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>';
 		echo '<p>Sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>';
		endBox();

		// quick access to important pages
		startBox('<h1>Learn more</h1>');
		echo '<p>World Guide: ';
		echo '<a href="'.rewriteURL('/world/atlas.html').'">Map</a>, ';
		echo '<a href="'.rewriteURL('/region.html').'">Regions</a>, ';
		echo '<a href="/dungeon.html">Dungeons</a>, ';
		echo '<a href="'.rewriteURL('/npc/').'">NPCs</a>, ';
		echo '<a href="/quest.html">Quests</a>, ';
		echo '<a href="'.rewriteURL('/achievement.html').'">Achievements</a>, ';
		echo '<a href="'.rewriteURL('/creature/').'">Creatures</a>, ';
		echo '<a href="'.rewriteURL('/item/').'">Items</a></p>';

		echo '<p>Player\'s Guide: ';
		echo '<a href="/wiki/Stendhal_Manual">Manual</a>, ';
		echo '<a href="/player-guide/faq.html">FAQ</a>, ';;
		echo '<a href="/player-guide/beginner-guide.html">Beginner\'s Guide</a>, ';
		echo '<a href="/player-guide/ask-for-help.html">Ask For Help</a>, ';
		echo '<a href="/chat/">Chat</a></p>';

	 	endBox();
		
		echo '</div>';

		// login form
		echo '<div style="width: 35%; float: right">';
		startBox('<h1>Login</h1>');
		echo '<form>';
		echo '<input type="text" placeholder="Username">';
		echo '<input type="password" placeholder="Password">';
		echo '<p><input type="submit" value="Login"></p>';
		echo '<p><a href="">Create account...</a></p>';
		echo '</form>';
		endBox();

		// screenshots and videos
		startBox('<h1>Media</h1>');
		echo '<p><img src="/images/screenshot.jpeg" width="120px" height=""> ';
		echo '<img src="/images/video.jpeg" width="120px"> ';
		echo '<img src="/images/screenshot.jpeg" width="120px"> ';
		echo '<img src="/images/video.jpeg" width="120px"> ';
		echo '<p><a href="">More images...</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="">More videos...</a>';
		endBox();

		// news
		startBox('<h1>News</h1>');
		echo '<p><a href="">Development meeting: Stendhal Website</a><br>2014-03-30 14:23:01';
		echo '<p><a href="">Stendhal 1.13: Hellish repainting</a><br>2014-02-10 20:53:37';
		endBox();
		echo '</div>';

	}
}
$page = new MainPage();
?>