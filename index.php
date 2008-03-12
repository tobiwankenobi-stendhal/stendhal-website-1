<?php 
/*
    Stendhal website - a website to manage and ease playing of Stendhal game
    Copyright (C) 2008  Miguel Angel Blanch Lardin

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

session_start();

$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
   
include('website.php');
include('login/login_function.php');
include('authors.php');

connect();

/*
 * This code decide the page to load.
 */ 
$page_url="content/main";

if(isset($_REQUEST["id"]))
  {  
  $page_url=$_REQUEST["id"];
  
  if(!(
      (strpos($page_url,".")===false)||
      (strpos($page_url,"//")===false)||
      (strpos($page_url,"http")===false)||
      (strpos($page_url,"/")!=1))||
      !file_exists($page_url.'.php')
      )
    {    
    /*
     * If page_url contains something suspicious we reset it to main page.
     */
    $page_url="content/main";
    }
  }

?>
<html>
  <head>
    <title>Stendhal an open source online multiplayer adventures games</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<?php
	  /*
	   * Does exists another style sheet for this page?
	   * Yes? Load it.
	   */
	  if(file_exists($page_url.'.css')) {
	    ?>
	    <link rel="stylesheet" type="text/css" href="<?php echo $page_url; ?>.css" />
	    <?php
	  }
	?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <a href="?"><img style="border: 0;" src="images/logo.gif" alt="Logotype"/></a>
      </div>
      <div id="account">
        <?php displayLogin(); ?>
      </div>
      <div id="topMenu">
        <ul>
          <li id="manual_button"><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalManual"><img src="images/menu/manual.png" alt="Manual"/></a></li>
          <li id="support_button"><a href="http://sourceforge.net/tracker/?func=add&group_id=1111&atid=201111"><img src="images/menu/support.png" alt="Support"/></a></li>
          <li id="forum_button"><a href="http://sourceforge.net/forum/forum.php?forum_id=3190"><img src="images/menu/forum.png" alt="Forum"/></a></li>
          <li id="downloads_button"><a href="http://sourceforge.net/project/platformdownload.php?group_id=1111&sel_platform=410"><img src="images/menu/download.png" alt="Downloads"/></a></li>
          <li id="hof_button"><a href="?id=content/halloffame"><img src="images/menu/halloffame.png" alt="Hall of Fame"/></a></li>
        </ul>
      </div>
      <div id="leftArea">
        <?php 
          startBox('Screenshot');
          $screen=getLatestScreenshot();
          
          echo '<a href="'.$screen->url.'" target="_blank">';
            $screen->showThumbnail();
          echo '</a>';
          endBox() 
        ?>
        
        <?php 
          startBox('Movie');
          ?>
          <object width="162" height="130"><param name="movie" value="http://www.youtube.com/v/U5JaD4qlmwM"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/U5JaD4qlmwM" type="application/x-shockwave-flash" wmode="transparent" width="162" height="130"></embed></object>
        <?php
          endBox() 
        ?>

        <?php 
          startBox('Events');
          $events=getEvents();
          foreach($events as $i) {
            $i->show();
          }
          endBox(); 
        ?>
        
        <?php startBox('Game System'); ?>
          <ul id="menu">
            <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalFAQ"><img src="images/buttons/faq_button.png">FAQ</a></li>
            <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalHistory"><img src="images/buttons/history_button.png">History</a></li>
            <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalAtlas"><img src="images/buttons/atlas_button.png">Atlas</a></li>
            <li><a href="?id=content/game/creatures"><img src="images/buttons/creatures_button.png">Creatures</a></li>
            <li><a href="?id=content/game/items"><img src="images/buttons/items_button.png">Items</a></li>
            <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalQuest"><img src="images/buttons/quests_button.png">Quests</a></li>
            <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalRuleSystem"><img src="images/buttons/rules_button.png">Rules</a></li>
            <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=AskForHelp"><img src="images/buttons/help_button.png">Help</a></li>
          </ul>
        <?php endBox(); ?>
      </div>
      
      <div id="rightArea">
        <a href="http://arianne.sourceforge.net/jws/stendhal.jnlp"><div id="playArea"></div></a>        
        <a href="http://downloads.sourceforge.net/arianne/stendhal-FULL-<?php echo $STENDHAL_VERSION; ?>.zip"><div id="downloadArea"></div></a>

        <?php 
          startBox('Server stats');
          $stats=getServerStats();
          if(!$stats->isOnline()) {
            echo '<div class="status">Server is offline</div>';
          }
          
          echo '<a href="?id=content/scripts/online"><span class="stats">'.getAmountOfPlayersOnline().'</span> players online.</a>';
          endBox(); 
        ?>

        <?php 
          startBox('<span style="font-size: 90%;">Player of the week</span>');
          $player=getPlayerOfTheWeek();
          $player->show();
          endBox(); 
        ?>
        
        <?php 
        if(getAdminLevel()>=400) {
          startBox('Administration'); ?>
          <ul id="menu">
            <li><a href="?id=content/admin/news"><img src="images/buttons/news_button.png">News</a></li>
            <li><a href="?id=content/admin/events"><img src="images/buttons/events_button.png">Events</a></li>
            <li><a href="?id=content/admin/screenshots"><img src="images/buttons/screenshots_button.png">Screenshots</a></li>
            <li><a href="?id=content/admin/movies"><img src="images/buttons/movies_button.png">Movies</a></li>
          </ul>
        <?php 
          endBox();
          }       
        ?>
<!-- TODO: If there's no poll this shouldn't just fail! Fix it!
	Commenting out for now as I don't think polls work yet.
	 <?php 
          startBox('Poll');
          $poll=getLatestPoll();
          $poll->show();
          endBox(); 
        ?> -->

       <?php startBox('Collaborate'); ?>
        <ul  id="menu">
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalRefactoringAtlas"><img src="images/buttons/atlas_button.png">Maps</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalRPProposal"><img src="images/buttons/rpsystem_button.png">RP System</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalRefactoringQuests"><img src="images/buttons/quests_button.png">Quests</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalRefactoringCreatures"><img src="images/buttons/creatures_button.png">Creatures</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=HowToAddItemsStendhal"><img src="images/buttons/items_button.png">Items</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalRefactoringGraphics"><img src="images/buttons/c_gfx_button.png">Graphics</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalOpenTasks#SFX"><img src="images/buttons/c_snd_button.png">Sounds &amp; Music</a></li>
          <li><a href="http://sourceforge.net/cvs/?group_id=1111"><img src="images/buttons/history_button.png">CVS</a></li>
          <li><a href="http://arianne.sourceforge.net/wiki/index.php?title=StendhalCodeDesign"><img src="images/buttons/c_code_button.png">Code</a></li>
        </ul>          
        <?php endBox(); ?>
      </div>
      
      <div id="contentArea">
        <?php include($page_url.".php");  ?>
      </div>
      <div id="footerArea">
    <?php
    $mtime = explode(' ', microtime());
    $totaltime = $mtime[0] + $mtime[1] - $starttime;
    printf(' (Page loaded in %.3f seconds.)', $totaltime);
    ?>
    <div class="copyright">© 1999-2008 Arianne RPG</div>
        <span><a href="http://sourceforge.net"><img style="border: 1px solid black;" src="http://sflogo.sourceforge.net/sflogo.php?group_id=1111&amp;type=4" width="125" height="37" border="0" alt="SourceForge.net Logo" /></a></span>
        <span>
<div id="eXTReMe"><a href="http://extremetracking.com/open?login=mblanch">
<img src="http://t1.extreme-dm.com/i.gif" style="border: 0;"
height="38" width="41" id="EXim" alt="eXTReMe Tracker" /></a>
<script type="text/javascript"><!--
var EXlogin='mblanch' // Login
var EXvsrv='s10' // VServer
EXs=screen;EXw=EXs.width;navigator.appName!="Netscape"?
EXb=EXs.colorDepth:EXb=EXs.pixelDepth;
navigator.javaEnabled()==1?EXjv="y":EXjv="n";
EXd=document;EXw?"":EXw="na";EXb?"":EXb="na";
EXd.write("<img src=http://e1.extreme-dm.com",
"/"+EXvsrv+".g?login="+EXlogin+"&amp;",
"jv="+EXjv+"&amp;j=y&amp;srw="+EXw+"&amp;srb="+EXb+"&amp;",
"l="+escape(EXd.referrer)+" height=1 width=1>");//-->
</script><noscript><div id="neXTReMe"><img height="1" width="1" alt=""
src="http://e1.extreme-dm.com/s10.g?login=mblanch&amp;j=n&amp;jv=n" />
</noscript>
</div>
        </span>
      </div>
      <div class="author">
      <?php
        $name=array_rand($authors);
      ?>
        <a href="http://arianne.sourceforge.net"><img src="createoutfit.php?outfit=<?php echo $authors[$name]; ?>" alt="<?php echo $name; ?>"/></a>
      </div>
    </div>
  </body>
</html>

<?php
disconnect();
?>
