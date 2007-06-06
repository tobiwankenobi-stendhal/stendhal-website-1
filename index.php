<?php 
include('website.php');

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
     * If page_url contains something suspicius we reset it to main page.
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
	    echo '	<link rel="stylesheet" type="text/css" href="'.$page_url.'.css" />';
	  }
	?>
  </head>
  <body>
    <div id="container">
      <div id="header">
        <a href="?"><img style="border: 0;" src="images/logo.gif" alt="Logotype"/></a>
      </div>
      <div id="account">
        <a href="">Login</a> - <a href="">Create account</a>
      </div>
      <div id="topMenu">
        <ul>
          <li id="manual_button"><a href="?id=content/manual">Manual</a></li>
          <li id="support_button"><a href="?id=content/support"><img src="images/blob.png" alt="Support">Support</a></li>
          <li id="forum_button"><a href="?id=content/forum"><img src="images/blob.png" alt="Forum">Forum</a></li>
          <li id="downloads_button"><a href="?id=content/download"><img src="images/blob.png" alt="Download">Downloads</a></li>
          <li id="hof_button"><a href="?id=content/halloffame"><img src="images/blob.png" alt="Hall of Fame">Hall of Fame</a></li>
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
          <ul id="menu">
            <li><a href="?id=content/game/history"><img src="images/buttons/history_button.png">History</a></li>
            <li><a href="?id=content/game/atlas"><img src="images/buttons/atlas_button.png">Atlas</a></li>
            <li><a href="?id=content/game/rp"><img src="images/buttons/rpsystem_button.png">RP System</a></li>
            <li><a href="?id=content/game/creatures"><img src="images/buttons/creatures_button.png">Creatures</a></li>
            <li><a href="?id=content/game/items"><img src="images/buttons/items_button.png">Items</a></li>
            <li><a href="?id=content/game/quests"><img src="images/buttons/quests_button.png">Quests</a></li>
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
        <ul  id="menu">
          <li><a href="?id=content/dev/maps"><img src="images/buttons/atlas_button.png">Maps</a></li>
          <li><a href="?id=content/dev/quests"><img src="images/buttons/quests_button.png">Quests</a></li>
          <li><a href="?id=content/dev/creatures"><img src="images/buttons/creatures_button.png">Creatures</a></li>
          <li><a href="?id=content/dev/items"><img src="images/buttons/items_button.png">Items</a></li>
          <li><a href="?id=content/dev/graphics"><img src="images/buttons/c_gfx_button.png">Graphics</a></li>
          <li><a href="?id=content/dev/music"><img src="images/buttons/c_snd_button.png">Sounds &amp; Music</a></li>
          <li><a href="?id=content/dev/web"><img src="images/buttons/c_website_button.png">Webpage</a></li>
          <li><a href="?id=content/dev/code"><img src="images/buttons/c_code_button.png">Code</a></li>
          <li><a href="?id=content/donations"><img src="images/buttons/donate_button.png">Donations</a></li>
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

<?php
disconnect();
?>