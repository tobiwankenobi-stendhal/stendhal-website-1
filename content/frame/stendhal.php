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
// 		echo '<meta name="viewport" content="width=device-width, initial-scale=1" />';
	}

	/**
	 * renders the frame
	 */
	function renderFrame() {
		global $page, $protocol, $adminLevel;
?>
<body <?php echo $page->getBodyTagAttributes()?>>

<div class="container">

<br>

      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
          	<a class="navbar-brand" href="/"><img src="/images/logo.gif" title="Stendhal Logo" alt=""></a>
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">About <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a id="menuAboutNews" href="https://stendhalgame.org/wiki/">News (TODO)</a></li>
<li><a id="menuAboutScreenshots" href="https://stendhalgame.org/wiki/">Screenshots (TODO)</a></li>
<li><a id="menuAboutVideos" href="https://stendhalgame.org/wiki/Stendhal_Videos">Videos</a></li>
</ul>
</li>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Player's Guide <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a id="menuHelpManual" href="https://stendhalgame.org/wiki/StendhalManual">Manual</a></li>
<li><a id="menuHelpFAQ" href="https://stendhalgame.org/wiki/StendhalFAQ">FAQ</a></li>
<li><a id="menuHelpBeginner" href="https://stendhalgame.org/wiki/BeginnersGuide">Beginner's Guide</a></li>
<li><a id="menuHelpAsk" href="https://stendhalgame.org/wiki/AskForHelp">Ask For Help</a></li>
<li><a id="menuHelpChat" href="/chat/">Chat</a></li>
<li><a id="menuHelpSupport" href="https://sourceforge.net/p/arianne/support-requests/new/">Support Ticket</a></li>
<li><a id="menuHelpForum" href="https://sourceforge.net/p/arianne/discussion/">Forum</a></li>
<li><a id="menuHelpRules" href="https://stendhalgame.org/wiki/StendhalRuleSystem">Rules</a></li>
</ul>
</li>
			

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">World Guide <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a id="menuAtlas" href="/world/atlas.html">Atlas</a></li>
<li><a id="menuNPCs" href="/npc/">NPCs</a></li>
<li><a id="menuCreatures" href="/creature/">Creatures</a></li>
<li><a id="menuItems" href="'/item/">Items</a></li>
<li><a id="menuQuests" href="https://stendhalgame.org/wiki/StendhalQuest">Quests</a></li>
<li><a id="menuAchievements" href="/achievement.html">Achievements</a></li>
<li><a id="menuHistory" href="https://stendhalgame.org/wiki/StendhalHistory">History</a></li>
</ul>
</li>

<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Players <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a id="menuPlayerOnline" href="/world/online.html">Online players</a></li>
<li><a id="menuPlayerHalloffame" href="/world/hall-of-fame/active_overview.html">Hall Of Fame</a></li>
<li><a id="menuPlayerEvents" href="/world/events.html">Recent Events</a></li>
<li><a id="menuPlayerKillstats" href="/world/kill-stats.html">Kill stats</a></li>
</ul>
</li>


<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">Development <b class="caret"></b></a>
<ul class="dropdown-menu">
<li><a id="menuContribChat" href="/chat/">Chat</a></li>
<li><a id="menuContribWiki" href="https://stendhalgame.org/wiki/Stendhal">Wiki</a></li>
<li><a id="menuContribBugs" href="/development/bug.html">Report Bug</a></li>
<li><a id="menuContribRequests" href="https://sourceforge.net/p/arianne/feature-requests/new/">Suggest Feature</a></li>
<li><a id="menuContribHelp" href="https://sourceforge.net/p/arianne/patches/new/">Submit Patch</a></li>
<li><a id="menuContribQuests" href="https://stendhalgame.org/wiki/Stendhal_Quest_Contribution">Quests</a></li>
<li><a id="menuContribTesting" href="https://stendhalgame.org/wiki/Stendhal_Testing">Testing</a></li>
<li><a id="menuContribHistory" href="/development/sourcelog.html">Changes</a></li>
<li><a id="menuContribDownload" href="https://sourceforge.net/projects/arianne/files/stendhal">All Downloads</a></li>
<li><a id="menuContribDevelopment" href="/development">Development</a></li>
</ul>
</li>



</ul>
         
            <ul class="nav navbar-nav navbar-right">
              <li class="active"><a href="./">Default</a></li>
              <li><a href="../navbar-static-top/">Static top</a></li>
              <li><a href="../navbar-fixed-top/">Fixed top</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>

	<div id="header">
		<?php
		echo '<form id="headersearchform" action="'.rewriteURL('/search').'" method="GET">';
		if (!STENDHAL_MODE_REWRITE) {
			echo '<input type="hidden" name="id" value="content/game/search">';
		}
		echo '<div>';
		echo '<input id="headersearchforminput" name="q" id="q" placeholder="Search"><button><img src="https://stendhalgame.org/w/skins/vector/images/search-ltr.png?303" alt=""></button></form>';
		echo '</div>';
		?>
	</div>

	<div id="topMenu"></div>
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
			<a href="<?php echo $protocol;?>://stendhalgame.org/wiki/Stendhal_Videos"><img src="<?php echo STENDHAL_FOLDER;?>/images/video.jpeg" style="border: 0;" title="Stendhal videos &amp; video tutorials" alt="A screenshot of Stendhal in Semos Bank with a bank chest window open showing lots if items. In the middle of the screenshow a semitransparent play-icon is painted, indicating this image links to a video."></a>
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


	</div>
	</div>

	<div id="contentArea">
		<?php
			// The central area of the website.
			$page->writeContent();
		?>
	</div>
	
	<div id="footerArea">
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
}
$frame = new StendhalFrame();
