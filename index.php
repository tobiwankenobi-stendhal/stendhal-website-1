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
        <ul id="layer1">
          <li id="manual_button"><a href="#"><img src="images/blob.png" alt="Manual">Manual</a></li>
          <li id="support_button"><a href="#"><img src="images/blob.png" alt="Support">Support</a></li>
          <li id="forum_button"><a href="#"><img src="images/blob.png" alt="Forum">Forum</a></li>
        </ul>
        <ul id="layer2">
          <li id="downloads_button"><a href="#"><img src="images/blob.png" alt="Download">Downloads</a></li>
          <li id="hof_button"><a href="#"><img src="images/blob.png" alt="Hall of Fame">Hall of Fame</a></li>
          <li id="stats_button"><a href="#"><img src="images/blob.png" alt="StatisticsS">Statistics</a></li>
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
          <ul id="leftMenu">

            <li><a href="#"><img src="images/vertical_menu_button.png">History</a></li>
            <li><a href="#"><img src="images/vertical_menu_button.png">Atlas</a></li>
            <li><a href="#"><img src="images/vertical_menu_button.png">RP System</a></li>
            <li><a href="#"><img src="images/vertical_menu_button.png">Creatures</a></li>
            <li><a href="#"><img src="images/vertical_menu_button.png">Items</a></li>
            <li><a href="#"><img src="images/vertical_menu_button.png">Quests</a></li>
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
        <ul  id="rightMenu">
          <li><a href="#"><img src="images/vertical_menu_button.png">Maps</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Quests</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Creatures</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Items</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Graphics</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Sounds &amp; Music</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Webpage</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Code</a></li>
          <li><a href="#"><img src="images/vertical_menu_button.png">Donations</a></li>
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
