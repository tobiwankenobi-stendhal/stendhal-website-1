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
   
include('scripts/website.php');
include('login/login_function.php');
include('scripts/authors.php');

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
  $result=$url;
  
  if(!(
      (strpos($url,".")===false)||
      (strpos($url,"//")===false)||
      (strpos($url,"http")===false)||
      (strpos($url,"/")!=1))||
      !file_exists($url.'.php')
      )
    {    
    /*
     * If page_url contains something suspicious we reset it to main page.
     */
    $result="content/main";
    }
    
    return $result;	
}

/*
 * This code decide the page to load.
 */ 
$page_url="content/main";
if(isset($_REQUEST["id"]))
  {  
  $page_url=decidePageToLoad($_REQUEST["id"]);  
  }

?>
<html>
  <head>
    <title>Stendhal an open source online multiplayer adventures games</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="icon" type="image/png" href="images/favicon.png">
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
          /*
           * Return the latest screenshot added to the webpage.
           */
          $screen=getLatestScreenshot();
          
          ?>
          <a href="<? echo $screen->url; ?>" target="_blank">
            <?php $screen->showThumbnail(); ?>
          </a>
          <?php
          endBox() 
        ?>
        
        <?php 
          startBox('Movie');
          // TODO: Refactor so it uses a screenshot-like method.
          ?>
          <object width="162" height="130"><param name="movie" value="http://www.youtube.com/v/U5JaD4qlmwM"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/U5JaD4qlmwM" type="application/x-shockwave-flash" wmode="transparent" width="162" height="130"></embed></object>
        <?php
          endBox() 
        ?>

        <?php 
          startBox('Events');
          /*
           * Return the two latest events added to the website.
           * Even if they have already happened.
           */
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
            <li><a href="?id=content/game/atlas"><img src="images/buttons/atlas_button.png">Atlas</a></li>
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
        <a href="http://downloads.sourceforge.net/arianne/stendhal-FULL-<?php echo STENDHAL_VERSION; ?>.zip"><div id="downloadArea"></div></a>

        <?php 
          startBox('Server stats');
          /*
           * Load server stats about online players, bytes send/recv and other from database.
           */
          $stats=getServerStats();
          if(!$stats->isOnline()) {
            ?>
            <div class="status"><a href="?id=content/offline">Server is offline</a></div>
            <?php
          } else {
            ?>
            <a href="?id=content/scripts/online">
              <div class="stats"><?php echo getAmountOfPlayersOnline(); ?></div> players online.
            </a>
            <div class="small_notice">
              <a href="?id=content/scripts/serverstats">[Detailed stats]</a><br>
              <a href="?id=content/scripts/killedstats">[Killed stats]</a>
            </div>
          <?php
          }
          endBox(); 
        ?>

        <?php 
          startBox('Best Player');
          /*
           * Get the best player related to the amount of XP earn against its age.
           */
          $player=getBestPlayer(REMOVE_ADMINS_AND_POSTMAN);
          $player->show();
          endBox(); 
        ?>
        
        <?php 
          startBox('Who is...');
          ?>
          <form method="get" action="">
            <input type="hidden" name="id" value="content/scripts/character">
            <input type="text" name="name" maxlength="32">
            <input type="submit" name="search" value="Search">
          </form>
          <?php
          endBox(); 
        ?>
        
        <?php 
        /*
         * Show the admin menu only if player is really an admin.
         * Admins are designed using the stendhal standard way.
         */
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

       <?php startBox('Collaborate'); ?>
        <ul  id="menu">
          <li><a href="http://xplanner.homelinux.net"><img src="images/buttons/test_button.png">Testing</a></li>
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
        <?php
        $cache=new Cache(array(
            'login',
            '/online',
            '/admin',
          ));
        
        $isCached=false;
        
        if(STENDHAL_CACHE_ENABLED) {
          $isCached=$cache->start($page_url);
        }
        
        if(!$isCached) {
          /*
           * The central area of the website.
           * We append .php so that we avoid easy hacks on this.
           */ 
          include($page_url.".php");
        } else {
		  ?>
		  <div class="notice">Using a cached webpage.</div>
		  <?php        	
        }
        $cache->end();          
        ?>
      </div>
      
      <div id="footerArea">
        <?php
        /*
         * Compute how much time we took to render the page.
         */
        $mtime = explode(' ', microtime());
        $totaltime = $mtime[0] + $mtime[1] - $starttime;
        printf(' (Page generated in %.3f seconds.)', $totaltime);
        ?>
        <span class="copyright">&copy; 1999-2008 <a href="http://arianne.sourceforge.net">Arianne RPG</a></span>
        <span><a href="http://sourceforge.net"><img style="border: 1px solid black;" src="http://sflogo.sourceforge.net/sflogo.php?group_id=1111&amp;type=4" width="125" height="37" border="0" alt="SourceForge.net Logo" /></a></span>
        <span><?php include('counter.php'); ?></span>
      </div>
      
	   <div class="time">
 		<?php
 		/*
 	     * Show the server time
 	     */
     	echo 'Server time: '.date('G:i');
 		?>      
	  </div>
 
      <div class="author">
        <?php
        /*
         * Shows a little chara of the authors.
         */
        $name=array_rand($authors);
        ?>
        <a href="http://arianne.sourceforge.net"><img src="createoutfit.php?outfit=<?php echo $authors[$name]; ?>" alt="<?php echo $name; ?>"/><br><?php echo $name; ?></a>
      </div>
    </div>
  </body>
</html>

<?php
/*
 * Close connection to databases.
 */
disconnect();
?>
