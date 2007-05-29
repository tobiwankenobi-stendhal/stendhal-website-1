<?php 
include('website.php');

/*
 * This code decide the page to load.
 */ 
$page_url="content/main";

if(isset($_REQUEST["arianne_url"]))
  {  
  $page_url=$_REQUEST["arianne_url"];
  
  if(!(
      (strpos($page_url,".")===false)&&
      (strpos($page_url,"//")===false)&&
      (strpos($page_url,"http")===false)&&
      (strpos($page_url,"/")!=1))
      )
    {    
    $page_url="content/main";
    }
  }

?>
<html>
  <head>
    <title>Stendhal</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div id="container">
      <div id="header">
        <img src="images/logo.gif" alt="Logotype"/>
      </div>
      <div id="account">
        <a href="">Login</a> - <a href="">Create account</a>
      </div>
      <div id="topMenu">
        <ul>
          <!-- These links doesn't work -->
          <li id="downloads_button" class="button">Downloads</li>
          <li id="manual_button" class="button">Manual</li>
          <li id="support_button" class="button">Support</li>
          <li id="forum_button" class="button">Forum</li>
          <li id="hof_button" class="button">Hall of Fame</li>
          <li id="stats_button" class="button">Statistics</li>
        </ul>
      </div>
      <div id="leftArea">
        <?php 
          startBox('Screenshot');
          $screen=getLatestScreenshot();
          echo '<img src="'.$screen.'" alt="screenshot"/>';
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
          <ul>
          <!-- These li should be links, that doesn't work yet -->
            <li id="game_history">History</li>
            <li id="game_atlas">Atlas</li>
            <li id="game_rp">RP System</li>
            <li id="game_creatures">Creatures</li>
            <li id="game_items">Items</li>
            <li id="game_quests">Quests</li>
          </ul>
        <?php endBox(); ?>
      </div>
      
      <div id="rightArea">
        <a href="http://mblanch.homeip.net/jnlp/stendhal-cvs.jnlp"><div id="playArea"></div></a>        
        <a href="http://downloads.sourceforge.net/arianne/stendhal-FULL-<?php echo $STENDHAL_VERSION; ?>.zip?use_mirror=mesh"><div id="downloadArea"></div></a>

        <?php 
          startBox('<span style="font-size: 90%;">Player of the week</span>');
          $player=getPlayerOfTheWeek();
          $player->show();
          endBox(); 
        ?>
        
        <?php 
          startBox('Poll');
          $poll=getLatestPoll();
          $poll->show();
          endBox(); 
        ?>

        <?php startBox('Collaborate'); ?>
        <ul>
          <!-- These li should be links, that doesn't work yet -->
          <li><span id="maps_coll">Maps</span></li>
          <li><span id="quests_coll">Quests</span></li>
          <li><span id="creatures_coll">Creatures</span></li>
          <li><span id="items_coll">Items</span></li>
          <li><span id="gfx_coll">Graphics</span></li>
          <li><span id="snd_coll">Sounds and Music</span></li>
          <li><span id="webpage_coll">Webpage</span></li>
          <li><span id="code_coll">Code</span></li>
          <li><span id="donations_coll">Donations</span></li>
        </ul>          
        <?php endBox(); ?>
      </div>
      
      <div id="contentArea">
        <?php include($page_url.".php");  ?>
      </div>
      <div id="footerArea">
        © 1999-2007 Arianne RPG
      </div>
    </div>
  </body>
</html>
