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
	function writeHttpHeader($page_url) {
		global $protocol;
		if ($protocol == 'https') {
			header('X-XRDS-Location: '.STENDHAL_LOGIN_TARGET.'/?id=content/account/openid-provider&xrds');
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>'.substr(STENDHAL_TITLE, strpos(STENDHAL_TITLE, ' ', 2) + 1).'</title>'."\n";
		echo '<link rel="alternate" type="application/rss+xml" title="Stendhal News" href="'.rewriteURL('/rss/news.rss').'" >'."\n";
		echo '<meta name="keywords" content="Stendhal, game, gra, Spiel, Rollenspiel, juego, role, gioco, online, open, source, multiplayer, roleplaying, Arianne, foss, floss, Adventurespiel">';
		echo '<meta name="description" content="Stendhal is a fully fledged free open source multiplayer online adventures game (MORPG) developed using the Arianne game system.">';
		?>
<style type="text/css">
#oneLineDescription {
	background-image: url("../images/onelinedesc.png");
	background-position: center;
	background-repeat: no-repeat;
	color: Black;
	text-align: left;
	font-size: 14px;
	font-weight: bold;
	width: 546px;
	height: 84px;
	padding: 0;
	margin: 0 0 0 15px;
}

#oneLineDescription img {
	margin: 14px 0 0 10px;
	float: left;
}

#oneLineDescription span {
	position: relative;
	top: 10px;
	padding: 0px 8px 0 0;
}
</style>
		<?php
	}

	function writeContent() {
?>

<div id="oneLineDescription">
	<img src="/images/playit.gif" alt="play stendhal" width="106px" height="45px">
	<span>Stendhal is a fully fledged free open source multiplayer online 
	adventures game (MORPG) developed using the Arianne game system.</span>
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
		<li style="width: 100%"><a id="menuNewsTrade" href="http://twitter.com/stendhaltrade">Harold's Trading Announcements</a></li>
	</ul>
	<?php
	endBox();
	?>
</div>
<?php
	}
}
$page = new MainPage();
?>