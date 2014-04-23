<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2011 The Arianne Project

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


class StendhalFrame extends PageFrame {

	/**
	 * gets the default page in case none is specified.
	 *
	 * @return name of default page
	 */
	function getDefaultPage() {
		return 'content/main';
	}

	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	function writeHttpHeader($page_url) {
		return true;
	}

	/**
	 * this method can write additional html headers.
	 */
	function writeHtmlHeader() {
		echo '<link rel="icon" type="image/x-icon" href="'.STENDHAL_FOLDER.'/favicon.ico">';

		if (isset($_SESSION['_layout'])) {
 			echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
			echo '<style type="text/css">';
			// general layout adjustments
			echo 'body {background-image: none} #container, .box {border: none} #container {width: auto; max-width: 970px} #topMenu {background-image: none}';
		
			// layout adjustments for the start page
			echo '#contentArea {margin: 51px 0 80px 190px;}';
			echo '#leftArea, #rightArea {display: none}';
			echo '.box {margin-bottom: 1em}';
			echo '.boxTitle {border:none; background-image: none; background: #0D4619}';
			echo '#container {background-image: none}';
			echo '#header {background-image: url("/images/header_background.jpg"); background-position: center top; background-repeat: repeat-x; height: 130px;}';
			echo '#playArea, #downloadArea {border: none}';	
			echo '#topMenu {margin:0; height: auto}';
			echo '.tabPageContent {background-image:none; background-color: transparent; border: none}';
			echo '.activeTab {background-image:none; background-color: transparent; border: 2px solid #000; border-bottom: none}';
			echo '.backgroundTab {background-image:none; background-color: #545A58}';
			echo '@media screen and (max-width : 480px) { #contentArea {margin-left:0} }';
			echo '</style>';
		}
	}

	/**
	 * renders the frame
	 */
	function renderFrame() {
		global $page, $protocol, $adminLevel;
?>
<body <?php echo $page->getBodyTagAttributes()?>>
<div id="container">
	<div id="header">
		<?php
		echo '<a href="/"><img style="border: 0;" src="/images/logo.gif" alt="Stendhal"></a>';
		echo '<form id="headersearchform" action="'.rewriteURL('/search').'" method="GET">';
		if (!STENDHAL_MODE_REWRITE) {
			echo '<input type="hidden" name="id" value="content/game/search">';
		}
		echo '<div>';
		echo '<input id="headersearchforminput" name="q" id="q" placeholder="Search"><button><img src="https://stendhalgame.org/w/skins/vector/images/search-ltr.png?303" alt=""></button>';
		echo '</div>';

		if (isset($_SESSION['_layout'])) {
			echo '<a href="'.STENDHAL_LOGIN_TARGET.'/account/mycharacters.html"><span class="block" id="playArea"></span></a>';
			echo '<a href="http://arianne.sourceforge.net/download/stendhal.zip"><span class="block" id="downloadArea"></span></a>';
		}
		echo '</form>';
		?>
 		</div>

	<div id="topMenu">
	<?php 
		if (isset($_SESSION['_layout'])) {
			$this->navigationMenu();
		}
	?>
	</div>
	<?php
		if (!isset($_SESSION['_layout'])) {
	?>
	<div id="navigationColumns">
	<div id="leftArea">
		<?php 
		startBox('Screenshot');
		// Return the latest screenshot added to the webpage.
		$screen=getLatestScreenshot();
		echo '<a id="screenshotLink" href="'.rewriteURL('/images/screenshot/').'" target="_blank">';
		$screen->showThumbnail();
		echo '</a>';
		endBox();

		startBox('Movie'); ?>
			<a href="https://stendhalgame.org/wiki/Stendhal_Videos"><img src="<?php echo STENDHAL_FOLDER;?>/images/video.jpeg" width="99%" style="border: 0;" title="Stendhal videos &amp; video tutorials" alt="A screenshot of Stendhal in Semos Bank with a bank chest window open showing lots if items. In the middle of the screenshow a semitransparent play-icon is painted, indicating this image links to a video."></a>
		<?php endBox() ?>

		<?php startBox('World Guide'); ?>
		<ul id="gamemenu" class="menu">
			<?php 
			echo '<li><a id="menuAtlas" href="'.rewriteURL('/world/atlas.html').'">Map</a></li>'."\n";
			echo '<li><a id="menuRegion" href="/region.html">Regions</a></li>'."\n";
			echo '<li><a id="menuDungeons" href="/dungeon.html">Dungeons</a></li>'."\n";
			echo '<li><a id="menuNPCs" href="'.rewriteURL('/npc/').'">NPCs</a></li>'."\n";
			echo '<li><a id="menuQuests" href="/quest.html">Quests</a></li>'."\n";
			echo '<li><a id="menuAchievements" href="'.rewriteURL('/achievement.html').'">Achievements</a></li>'."\n";
			echo '<li><a id="menuCreatures" href="'.rewriteURL('/creature/').'">Creatures</a></li>'."\n";
			echo '<li><a id="menuItems" href="'.rewriteURL('/item/').'">Items</a></li>'."\n";
			?>
		</ul>
		<?php endBox(); ?>

		<?php startBox('Player\'s Guide'); ?>
		<ul id="helpmenu" class="menu">
			<?php 
			echo '<li><a id="menuHelpManual" href="/wiki/Stendhal_Manual">Manual</a></li>';
			echo '<li><a id="menuHelpFAQ" href="/player-guide/faq.html">FAQ</a></li>';
			echo '<li><a id="menuHelpBeginner" href="/player-guide/beginner-guide.html">Beginner\'s Guide</a></li>';
			echo '<li><a id="menuHelpAsk" href="/player-guide/ask-for-help.html">Ask For Help</a></li>';
			echo '<li><a id="menuHelpChat" href="/chat/">Chat</a></li>';
			// echo '<li><a id="menuHelpSupport" href="https://sourceforge.net/p/arianne/support-requests/new/">Support Ticket</a></li>';
			// echo '<li><a id="menuHelpForum" href="https://sourceforge.net/p/arianne/discussion/">Forum</a></li>';
			echo '<li><a id="menuHelpRules" href="/player-guide/rules.html">Rules</a></li>';
 			?>
		</ul>
		<?php endBox() ?>

	</div>

	<div id="rightArea">
		<a href="<?php echo(STENDHAL_LOGIN_TARGET.rewriteURL('/account/mycharacters.html'));?>"><span class="block" id="playArea"></span></a>
		<a href="http://arianne.sourceforge.net/download/stendhal.zip"><span class="block" id="downloadArea"></span></a>
		<?php
			startBox('My Account');
		?>
		<ul id="accountmenu" class="menu">
			<?php if(checkLogin()) { 
				$messageCount = StoredMessage::getCountUndeliveredMessages($_SESSION['account']->id, "characters.charname = postman.target AND deleted != 'R'"); ?>
				<li><a id="menuAccountMain" href="<?php echo(rewriteURL('/account/myaccount.html')); ?>">Logged in as <strong><?php echo htmlspecialchars($_SESSION['account']->username); ?></strong></a></li>
				<li><a id="menuAccountCharacters" href="<?php echo(rewriteURL('/account/mycharacters.html')); ?>">My Characters</a></li>
				<li><a id="menuAccountMessages" href="<?php echo(rewriteURL('/account/messages.html')); ?>">Messages (<?php echo htmlspecialchars($messageCount); ?>)</a></li>
				<li><a id="menuAccountHistory" href="<?php echo(rewriteURL('/account/history.html')); ?>">Login History</a></li>
				<li><a id="menuAccountEMail" href="<?php echo(rewriteURL('/account/email.html')); ?>">Mail address</a></li>
				<li><a id="menuAccountPassword" href="<?php echo(rewriteURL('/account/change-password.html')); ?>">New Password</a></li>
				<li><a id="menuAccountMerge" href="<?php echo(rewriteURL('/account/merge.html')); ?>">Merge Accounts</a></li>
				<li><a id="menuAccountLogout" href="<?php echo(rewriteURL('/account/logout.html')); ?>">Logout</a></li>
			<?php } else { ?>
				<li><a id="menuAccountLogin" href="<?php echo(STENDHAL_LOGIN_TARGET.rewriteURL('/account/login.html')); ?>">Login</a></li>
			<?php } ?>
		</ul>
		<?php
			endBox();
		?>

		<?php 
		/*
		 * Show the admin menu only if player is really an admin.
		 * Admins are designed using the stendhal standard way.
		 */
		$adminLevel = getAdminLevel();
		if($adminLevel >= 100) {
			startBox('Administration'); ?>
			<ul id="adminmenu" class="menu">
				<?php 
				if($adminLevel >= 400) { ?>
					<li><a id="menuAdminNews" href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/news">News</a></li>
					<li><a id="menuAdminScreenshots" href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/screenshots">Screenshots</a></li>
				<?php } ?>
				<li><a id="menuAdminDataExplorer" href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/data">Data Explorer</a></li>
				<li><a id="menuAdminInspect" href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/inspect">Render Inspect</a></li>
				<li><a id="menuAdminSupportlog" href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/logs">Support Logs</a></li>
				<li><a id="menuAdminPlayerhistory" href="<?php echo STENDHAL_FOLDER;?>/?id=content/admin/playerhistory">Player History</a></li>
			</ul>
			<?php endBox();
		}?>

		<?php 
		startBox('Best Player');
		$player=getBestPlayer('recent', REMOVE_ADMINS_AND_POSTMAN);
		if($player!=NULL) {
			Player::showFromArray($player);
		} else {
			echo '<div class="small_notice">'.STENDHAL_NO_BEST_PLAYER.'</div>';
		}
		endBox(); ?>

		<?php startBox('Players'); ?>
		<ul class="menu">
			<li style="white-space: nowrap"><a id="menuPlayerOnline" href="<?php echo rewriteURL('/world/online.html')?>">Online players</a></li>
			<li><a id="menuPlayerHalloffame" href="<?php echo rewriteURL('/world/hall-of-fame/active_overview.html')?>">Hall Of Fame</a></li>
			<li><a id="menuPlayerEvents" href="<?php echo rewriteURL('/world/events.html')?>">Recent Events</a></li>
			<li><a id="menuPlayerKillstats" href="<?php echo rewriteURL('/world/kill-stats.html')?>">Kill stats</a></li>
		</ul>
		<form method="get" action="<?php echo STENDHAL_FOLDER;?>/" accept-charset="iso-8859-1">
			<input type="hidden" name="id" value="content/scripts/character">
			<input type="text" name="name" maxlength="30" style="width:9.8em">
			<input type="submit" name="search" value="Player search">
		</form>
		<?php endBox(); ?>

		<?php startBox('Contribute'); ?>
		<ul id="contribmenu" class="menu">
			<?php
			echo '<li><a id="menuContribChat" href="'.rewriteURL('/chat/').'">Chat</a></li>'."\n";
			echo '<li><a id="menuContribWiki" href="https://stendhalgame.org/wiki/Stendhal">Wiki</a></li>'."\n";
			echo '<li><a id="menuContribBugs" href="'.rewriteURL('/development/bug.html').'">Report Bug</a></li>'."\n";
			echo '<li><a id="menuContribRequests" href="https://sourceforge.net/p/arianne/feature-requests/new/">Suggest Feature</a></li>'."\n";
			echo '<li><a id="menuContribHelp" href="https://sourceforge.net/p/arianne/patches/new/">Submit Patch</a></li>'."\n";
			echo '<li><a id="menuContribQuests" href="https://stendhalgame.org/wiki/Stendhal_Quest_Contribution">Quests</a></li>'."\n";
			echo '<li><a id="menuContribTesting" href="https://stendhalgame.org/wiki/Stendhal_Testing">Testing</a></li>'."\n";
			echo '<li><a id="menuContribHistory" href="'.rewriteURL('/development/sourcelog.html').'">Changes</a></li>'."\n";
			echo '<li><a id="menuContribDownload" href="https://sourceforge.net/projects/arianne/files/stendhal">All Downloads</a></li>'."\n";
			echo '<li><a id="menuContribDevelopment" href="'.rewriteURL('/development').'">Development</a></li>'."\n";
			?>
		</ul>
		<?php endBox(); ?>
	</div>
	</div>
	<?php }?>

	<div id="contentArea">
		<?php
			// The central area of the website.
			$page->writeContent();
		?>
	</div>
	
	<div id="footerArea">
		<?php
		$breadcrumbs = $page->getBreadCrumbs();
		if (isset($breadcrumbs)) {
			echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
			for ($i = 0; $i < count($breadcrumbs) / 2; $i++) {
				echo '&gt; <span typeof="v:Breadcrumb"><a href="'.surlencode($breadcrumbs[$i * 2 + 1]).'" rel="v:url" property="v:title">';
				echo htmlspecialchars($breadcrumbs[$i * 2]);
				echo '</a></span> ';
			}
			echo '</div>';
		}?>
		<span class="copyright">&copy; 1999-2014 <a href="http://arianne.sourceforge.net">Arianne Project</a></span>
	</div>

	<div class="time">
		<?php
		// Show the server time
		echo 'Server time: '.date('G:i');
		?>
	</div>
</div>

<div id="popup">
<a id="popupClose">x</a>
<div id="popupContent"></div>
</div>
<div id="backgroundPopup"></div>

<?php

if (defined('STENDHAL_MOUSE_FLOATING_IMAGE_ON_TOP_OF_BOXES')) {
	echo '<img id="mousefloatingimageontopofboxes" src="'
		. urldecode(STENDHAL_MOUSE_FLOATING_IMAGE_ON_TOP_OF_BOXES)
		. '" data-offset="'.STENDHAL_MOUSE_FLOATING_IMAGE_ON_TOP_OF_BOXES_OFFSET
		. '" data-sound="'.STENDHAL_MOUSE_FLOATING_IMAGE_SOUND.'">';
}
$this->includeJs();
$page->writeAfterJS();
?>
</body>
</html>

<?php
		}

	function navigationMenu() {
// http://www.silent-fran.de/css/tutorial/aufklappmenue.html
		?>
		<style type="text/css">

nav {
	width: 100%;
}
nav ul {
	padding: 0px;
	margin: 0px;
}
nav ul:after {
	clear: both;
	content: " ";
	display: block;
	font-size: 0;
	height: 0;
	visibility: hidden;
}
nav ul,nav ul li{
	background-color: #FAFAFA;
}
nav ul li {
	list-style: none;
	float:left;
}
nav ul li a {
	text-decoration: none;
	display: block;
	color: #333;
	padding: 0.5em 1.5em;
}
nav ul li:hover > ul {
	visibility: visible;
}
nav ul li ul{
	display: inline;
	visibility: hidden;
	position: absolute;
	padding:0px;
	z-index: 10000;
}
nav ul li ul li{
	float: none;
	text-align: left;
}
nav ul li ul li a {
	padding-left: 35px;
	background: 5px center no-repeat;
}
nav ul li ul li a:hover{
	color: #333;
}

nav ul {
	padding: 0px;
	margin: 0px;
	box-shadow: 2px 2px 2px #dfdfdf;
	-moz-box-shadow: 2px 2px 2px #dfdfdf;
	-webkit-box-shadow: 2px 2px 2px #dfdfdf;
}
nav ul li {
	list-style: none;
	float:left;
	border-right: 1px solid #dfdfdf;
}
nav ul li a {
	-webkit-transition: background 0.3s ease-out 0s;
	-moz-transition: background 0.3s ease-out 0s;
	-o-transition: background 0.3s ease-out 0s;
	transition: background 0.3s ease-out 0s;
}
nav ul li:hover a, nav ul li:hover > ul li a  {
	background-color: #545A58;
}

nav ul li:hover > a {
	color: #FAFAFA;
}
nav ul li ul li a {
	color: #FAFAFA;
}
nav ul li ul li a:hover{
	color: #333;
	background-color: #FAFAFA !important;
}

		</style>
		<?php

		echo '<nav><ul class="navigation">';

		echo '<li><a href="/media.html">Media</a><ul>';
			echo '<li><a href="/world/newsarchive.html">News</a>';
			echo '<li><a href="/media/screenshots.html">Screenshots</a>';
			echo '<li><a href="/media/videos.html">Videos</a></ul>';

		echo '<li><a href="/world.html">World Guide</a><ul>';
			echo '<li class=""><a id="menuAtlas" href="/world/atlas.html">Map</a>';
			echo '<li class="cat2"><a id="menuRegion" href="/region.html">Regions</a>';
			echo '<li><a id="menuDungeons" href="/dungeon.html">Dungeons</a>';
			echo '<li><a id="menuNPCs" href="/npc/">NPCs</a>';
			echo '<li><a id="menuQuests" href="/quest.html">Quests</a>';
			echo '<li><a id="menuAchievements" href="/achievement.html">Achievements</a>';
			echo '<li><a id="menuCreatures" href="/creature/">Creatures</a>';
			echo '<li><a id="menuItems" href="/item/">Items</a></ul>';

		echo '<li><a href="/player-guide.html">Player\'s Guide</a><ul>';
			echo '<li><a id="menuHelpManual" href="/wiki/Stendhal_Manual">Manual</a>';
			echo '<li><a id="menuHelpFAQ" href="/player-guide/faq.html">FAQ</a>';
			echo '<li><a id="menuHelpBeginner" href="/player-guide/beginner-guide.html">Beginner\'s Guide</a>';
			echo '<li><a id="menuHelpAsk" href="/player-guide/ask-for-help.html">Ask For Help</a>';
			echo '<li><a id="menuHelpChat" href="/chat/">Chat</a>';
			// echo '<li><a id="menuHelpSupport" href="https://sourceforge.net/p/arianne/support-requests/new/">Support Ticket</a>';
			// echo '<li><a id="menuHelpForum" href="https://sourceforge.net/p/arianne/discussion/">Forum</a>';
			echo '<li><a id="menuHelpRules" href="/player-guide/rules.html">Rules</a></ul>';

		echo '<li><a href="/community.html">Community</a><ul>';
			echo '<li><a id="menuPlayerOnline" href="/world/online.html">Online players</a>';
			echo '<li><a id="menuPlayerHalloffame" href="/world/hall-of-fame/active_overview.html">Hall Of Fame</a>';
			echo '<li><a id="menuPlayerEvents" href="/world/events.html">Recent Events</a>';
			echo '<li><a id="menuPlayerKillstats" href="/world/kill-stats.html">Kill stats</a></ul>';
			
		echo '<li><a href="/development.html">Development</a><ul>';
			echo '<li><a id="menuContribChat" href="/chat/">Chat</a>';
			echo '<li><a id="menuContribWiki" href="https://stendhalgame.org/wiki/Stendhal">Wiki</a>';
			echo '<li><a id="menuContribBugs" href="/development/bug.html">Report Bug</a>';
			echo '<li><a id="menuContribRequests" href="https://sourceforge.net/p/arianne/feature-requests/new/">Suggest Feature</a>';
			echo '<li><a id="menuContribHelp" href="https://sourceforge.net/p/arianne/patches/new/">Submit Patch</a>';
			echo '<li><a id="menuContribQuests" href="https://stendhalgame.org/wiki/Stendhal_Quest_Contribution">Quests</a>';
			echo '<li><a id="menuContribTesting" href="https://stendhalgame.org/wiki/Stendhal_Testing">Testing</a>';
			echo '<li><a id="menuContribHistory" href="/development/sourcelog.html">Changes</a>';
			echo '<li><a id="menuContribDownload" href="https://sourceforge.net/projects/arianne/files/stendhal">All Downloads</a>';
			echo '<li><a id="menuContribDevelopment" href="/development">Development</a></ul>';

		$adminLevel = getAdminLevel();
		if ($adminLevel >= 100) {
			echo '<li><a>A</a><ul>';
			if ($adminLevel >= 400) {
				echo '<li><a id="menuAdminNews" href="/?id=content/admin/news">News</a>';
				echo '<li><a id="menuAdminScreenshots" href="/?id=content/admin/screenshots">Screenshots</a>';
			}
			echo '<li><a id="menuAdminDataExplorer" href="/?id=content/admin/data">Data Explorer</a>';
			echo '<li><a id="menuAdminInspect" href="/?id=content/admin/inspect">Render Inspect</a>';
			echo '<li><a id="menuAdminSupportlog" href="/?id=content/admin/logs">Support Logs</a>';
			echo '<li><a id="menuAdminPlayerhistory" href="/?id=content/admin/playerhistory">Player History</a></ul>';
		}

		if (checkLogin()) { 
			$messageCount = StoredMessage::getCountUndeliveredMessages($_SESSION['account']->id, "characters.charname = postman.target AND deleted != 'R'");
			echo '<li><a href="/account/myaccount.html">'.htmlspecialchars(substr($_SESSION['account']->username, 0, 10)).'</a><ul>';
			echo '<li><a id="menuAccountCharacters" href="/account/mycharacters.html">My Characters</a>';
			echo '<li><a id="menuAccountMessages" href="/account/messages.html">Messages (<?php echo htmlspecialchars($messageCount); ?>)</a>';
			echo '<li><a id="menuAccountHistory" href="/account/history.html">Login History</a>';
			echo '<li><a id="menuAccountEMail" href="/account/email.html">Mail address</a>';
			echo '<li><a id="menuAccountPassword" href="/account/change-password.html">New Password</a>';
			echo '<li><a id="menuAccountMerge" href="/account/merge.html">Merge Accounts</a>';
			echo '<li><a id="menuAccountLogout" href="/account/logout.html">Logout</a></ul>';
		} else {
			echo '<li><a href="'.STENDHAL_LOGIN_TARGET.'/account/login.html">Login</a></li>';
		}
/*


		<?php 
		startBox('Best Player');
		$player=getBestPlayer('recent', REMOVE_ADMINS_AND_POSTMAN);
		if($player!=NULL) {
			Player::showFromArray($player);
		} else {
			echo '<div class="small_notice">'.STENDHAL_NO_BEST_PLAYER.'</div>';
		}
		endBox(); ?>

 */
			echo '</ul></nav>';
		}
}
$frame = new StendhalFrame();
