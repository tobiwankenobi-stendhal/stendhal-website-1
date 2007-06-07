<?php 
session_start();

include('website.php');
include('login/login_function.php');

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
        <?php displayLogin(); ?>
      </div>
      <div id="topMenu">
        <ul>
          <li id="manual_button"><a href="?id=content/manual">Manual</a></li>
          <li id="support_button"><a href="?id=content/support">Support</a></li>
          <li id="forum_button"><a href="?id=content/forum">Forum</a></li>
          <li id="downloads_button"><a href="?id=content/download">Downloads</a></li>
          <li id="hof_button"><a href="?id=content/halloffame">Hall of Fame</a></li>
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
          startBox('Movie');
          echo '<object width="162" height="130"><param name="movie" value="http://www.youtube.com/v/U5JaD4qlmwM"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/U5JaD4qlmwM" type="application/x-shockwave-flash" wmode="transparent" width="162" height="130"></embed></object>';
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
            <li><a href="?id=content/FAQ"><img src="images/buttons/faq_button.png">FAQ</a></li>
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
        <div class="copyright">© 1999-2007 Arianne RPG</div>
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
</div></noscript>
        </span>
      </div>
    </div>
  </body>
</html>

<?php
disconnect();
?>