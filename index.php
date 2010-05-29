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

if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == "on")) {
	$protocol = 'https';
	ini_set('session.cookie_secure', 1);
	session_start();
} else {
	$protocol = 'http';
}


require_once('scripts/website.php');
require_once('scripts/account.php');
require_once('scripts/authors.php');
require_once('scripts/urlrewrite.php');

/*
 * Open connection to both databases.
 */
connect();

/**
 * Scan the name module to load and reset it to safe default if something strange appears.
 *
 * @param string $url The name of the module to load without .php
 * @return string the name of the module to load.
 */
function decidePageToLoad($url) {
  $ERROR="content/main";
  
  if(strpos($url,".")!==false) {
    return $ERROR;
  }
  
  if(strpos($url,"//")!==false) {
    return $ERROR;
  }
  
  if(strpos($url,":")!==false) { // http://, https://, ftp://
    return $ERROR;
  }
  
  if(strpos($url,"/")==0) {
    return $ERROR;
  }
  
  if(strpos($url.'.php',".php")===false) {
    return $ERROR;
  }
 
  if(!file_exists($url.'.php')) {
    return $ERROR;
  }
  
  return $url;	
}

/*
 * This code decide the page to load.
 */ 
$page_url="content/main";
if(isset($_REQUEST["id"]))
  {  
  $page_url=decidePageToLoad($_REQUEST["id"]);  
  }

require_once("content/page.php");
require_once($page_url.'.php');

$folder = "";

if ($page->writeHttpHeader()) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
	<link rel="stylesheet" type="text/css" href="<?echo STENDHAL_FOLDER;?>/css/00000005.css">
	<!--[if lt IE 8]><link rel="stylesheet" type="text/css" href="<?echo STENDHAL_FOLDER;?>/css/ie000004.css"><![endif]-->
	<link rel="icon" type="image/png" href="<?echo STENDHAL_FOLDER;?>/favicon.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php
		/*
		 * Does another style sheet for this page exists?
		 * Yes? Load it.
		 */
		if(file_exists($page_url.'.css')) {
			?>
			<link rel="stylesheet" type="text/css" href="/<?php echo STENDHAL_FOLDER.'/'.$page_url; ?>.css">
			<?php
		}
		$page->writeHtmlHeader();
		if (!defined("STENDHAL_WANT_ROBOTS") || !constant("STENDHAL_WANT_ROBOTS")) {
			echo '<meta name="robots" content="noindex">'."\n";
		}
	?>
	</head>

<body>
<div id="container">
	<div id="header">
		<a href="<?echo STENDHAL_FOLDER;?>/"><img style="border: 0;" src="<?echo STENDHAL_FOLDER;?>/images/logo.gif" title="Stendhal Logo" alt="The Stendhal logo shows the word &quot;Stendhal&quot;in large blue letters."></a>
	</div>

	<div id="account">
		<?php displayLogin(); ?>
	</div>

	<div id="topMenu"></div>

	<div id="leftArea">
		<?php 
		startBox('Screenshot');
		// Return the latest screenshot added to the webpage.
		$screen=getLatestScreenshot();
		?>
		<a href="<? echo rewriteURL(STENDHAL_FOLDER.'/images/image/'.htmlspecialchars($screen->url)); ?>">
			<?php $screen->showThumbnail(); ?>
		</a>
		<?php endBox() ?>

		<?php startBox('Movie'); ?>
			<a href="<?php echo $protocol;?>://stendhalgame.org/wiki/Stendhal_Videos"><img src="<?echo STENDHAL_FOLDER;?>/images/video.jpeg" width="99%" style="border: 0;" title="Stendhal videos &amp; video tutorials" alt="A screenshot of Stendhal in Semos Bank with a bank chest window open showing lots if items. In the middle of the screenshow a semitransparent play-icon is painted, indicating this image links to a video."></a>
		<?php endBox() ?>

		<?php startBox('Game System'); ?>
		<ul id="gamemenu" class="menu">
			<?php 
			echo '<li><a id="menuAtlas" href="'.$protocol.'://stendhalgame.org/wiki/StendhalAtlas">Atlas</a></li>'."\n";
			echo '<li><a id="menuNPCs" href="'.rewriteURL('/npc/').'">NPCs</a></li>'."\n";
			echo '<li><a id="menuCreatures" href="'.rewriteURL('/creature/').'">Creatures</a></li>'."\n";
			echo '<li><a id="menuItems" href="'.rewriteURL('/item/').'">Items</a></li>'."\n";
			?>
			<li><a id="menuQuests" href="<?php echo $protocol;?>://stendhalgame.org/wiki/StendhalQuest">Quests</a></li>
			<li><a id="menuHistory" href="<?php echo $protocol;?>://stendhalgame.org/wiki/StendhalHistory">History</a></li>
		</ul>
		<?php endBox(); ?>

		<?php startBox('Help'); ?>
		<ul id="helpmenu" class="menu">
			<li><a id="menuHelpManual" href="<?php echo $protocol;?>://stendhalgame.org/wiki/StendhalManual">Manual</a></li>
			<li><a id="menuHelpFAQ" href="<?php echo $protocol;?>://stendhalgame.org/wiki/StendhalFAQ">FAQ</a></li>
			<li><a id="menuHelpBeginner" href="<?php echo $protocol;?>://stendhalgame.org/wiki/BeginnersGuide">Beginner's Guide</a></li>
			<li><a id="menuHelpAsk" href="<?php echo $protocol;?>://stendhalgame.org/wiki/AskForHelp">Ask For Help</a></li>
			<li><a id="menuHelpChat" href="<?php echo rewriteURL('/chat/');?>">Chat</a></li>
			<li><a id="menuHelpSupport" href="<?php echo $protocol;?>://sourceforge.net/tracker/?func=add&amp;group_id=1111&amp;atid=201111">Support Ticket</a></li>
			<li><a id="menuHelpForum" href="<?php echo $protocol;?>://sourceforge.net/forum/forum.php?forum_id=3190">Forum</a></li>
			<li><a id="menuHelpRules" href="<?php echo $protocol;?>://stendhalgame.org/wiki/StendhalRuleSystem">Rules</a></li>
		</ul>
		<?php endBox() ?>

	</div>

	<div id="rightArea">
		<a href="http://arianne.sourceforge.net/jws/stendhal.jnlp"><span class="block" id="playArea"></span></a>
		<a href="http://downloads.sourceforge.net/arianne/stendhal-FULL-<?php echo STENDHAL_VERSION; ?>.zip"><span class="block" id="downloadArea"></span></a>


		<?php 
		startBox('Best Player');
		$player=getBestPlayer(REMOVE_ADMINS_AND_POSTMAN);
		if($player!=NULL) {
			$player->show();
		} else {
			?>
			<div class="small_notice">
				No players registered.
			</div>
			<?php
		}
		endBox(); ?>

		<?php startBox('Players'); ?>
		<ul class="menu">
			<li style="white-space: nowrap"><a id="menuPlayerOnline" href="<?php echo rewriteURL('/world/online.html');?>"><b style="color: #00A; font-size:16px;"><?php echo getAmountOfPlayersOnline(); ?></b>&nbsp;Players&nbsp;Online</a></li>
			<li><a id="menuPlayerHalloffame" href="<?php echo rewriteURL('/world/hall-of-fame.html')?>">Hall Of Fame</a></li>
			<li><a id="menuPlayerKillstats" href="<?php echo rewriteURL('/world/kill-stats.html')?>">Kill stats</a></li>
		</ul>
		<form method="get" action="/" accept-charset="iso-8859-1">
			<input type="hidden" name="id" value="content/scripts/character">
			<input type="text" name="name" maxlength="30" style="width:9.8em">
			<input type="submit" name="search" value="Player search">
		</form>
		<?php endBox(); ?>


		<?php 
		/*
		 * Show the admin menu only if player is really an admin.
		 * Admins are designed using the stendhal standard way.
		 */
		if(getAdminLevel()>=100) {
			startBox('Administration'); ?>
			<ul id="adminmenu" class="menu">
				<?php 
				if(getAdminLevel()>=400) { ?>
					<li><a id="menuAdminNews" href="/?id=content/admin/news">News</a></li>
					<li><a id="menuAdminScreenshots" href="/?id=content/admin/screenshots">Screenshots</a></li>
				<?php } ?>
				<li><a id="menuAdminSupportlog" href="/?id=content/admin/logs">Support Logs</a></li>
				<li><a id="menuAdminPlayerhistory" href="/?id=content/admin/playerhistory">Player History</a></li>
			</ul>
			<?php endBox();
		}?>

<?php 
/*
          startBox('Server stats');
          // Load server stats about online players, bytes send/recv and other from database.
          $stats=getServerStats();
          if(!isset($stats) || !$stats->isOnline()) {
            ?>
            <div class="status"><a href="/?id=content/offline">Server is offline</a></div>
            <?php
          } else {
            echo '<a href="'.rewriteURL('/world/online.html').'">';
            ?>
            <div class="stats"><?php echo getAmountOfPlayersOnline(); ?></div> players online.
            </a>
            <div class="small_notice">
              <?php
              echo '<a href="'.rewriteURL('/world/server-stats.html').'">[Detailed stats]</a><br>'."\n";
              echo '<a href="'.rewriteURL('/world/kill-stats.html').'">[Killed stats]</a>'."\n";
              ?>
            </div>
          <?php
          }
          endBox(); 
*/        ?>

		<?php startBox('Contribute'); ?>
		<ul id="contribmenu" class="menu">
			<?php
			echo '<li><a id="menuContribChat" href="'.rewriteURL('/chat/').'">Chat</a></li>'."\n";
			echo '<li><a id="menuContribWiki" href="'.$protocol.'://stendhalgame.org/wiki/Stendhal">Wiki</a></li>'."\n";
			echo '<li><a id="menuContribBugs" href="'.rewriteURL('/development/bug.html').'">Report Bug</a></li>'."\n";
			echo '<li><a id="menuContribQuests" href="'.$protocol.'://stendhalgame.org/wiki/Stendhal_Quest_Contribution">Quests</a></li>'."\n";
			echo '<li><a id="menuContribHelp" href="'.$protocol.'://sourceforge.net/tracker/?func=add&amp;group_id=1111&amp;atid=301111">Submit Patch</a></li>'."\n";
			echo '<li><a id="menuContribTesting" href="'.$protocol.'://xplanner.homelinux.net">Testing</a></li>'."\n";
			echo '<li><a id="menuContribHistory" href="'.rewriteURL('/development/cvslog.html').'">Changes</a></li>'."\n";
			echo '<li><a id="menuContribDownload" href="'.$protocol.'://sf.net/projects/arianne/files/stendhal">All Downloads</a></li>'."\n";
			echo '<li><a id="menuContribDevelopment" href="'.rewriteURL('/development').'">Development</a></li>'."\n";
			?>
		</ul>
		<?php endBox(); ?>
	</div>

	<div id="contentArea">
		<?php
			// The central area of the website.
			$page->writeContent();
		?>
	</div>

	<div id="footerArea">
		<span class="copyright">&copy; 1999-2010 <a href="http://arianne.sourceforge.net">Arianne Project</a></span>
		<span><a id="footerSourceforge" href="http://sourceforge.net/projects/arianne">&nbsp;</a></span>
	</div>

	<div class="time">
		<?php
		// Show the server time
		echo 'Server time: '.date('G:i');
		?>
	</div>
</div>
</body>
</html>

<?php
}
// Close connection to databases.
disconnect();
?>
