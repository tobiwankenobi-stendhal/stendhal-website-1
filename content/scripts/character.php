<?php
function printAge($minutes) {
  return round($minutes/60,2);
}

$name=$_REQUEST["name"];
$players=getPlayers('where name="'.addslashes($name).'"', 'name');

if(sizeof($players)==0) {
  startBox("No such player");
  ?>
  There is no such player at Stendhal.<br>
  Please make sure you spelled it correctly.
  <?php
  endBox();
  return;
}
$choosen=$players[0];
?>

<?php startBox('Character info for '.$choosen->name); ?>
<div class="table">
  <div class="title">Details</div>
  <img class="bordered_image" src="createoutfit.php?outfit=<?php echo $choosen->outfit; ?>" alt="Player outfit"/>
  <div class="statslabel">Name:</div><div class="data"><?php echo $choosen->name; ?></div>
  <div class="statslabel">Age:</div><div class="data"><?php echo printAge($choosen->age); ?> hours</div>
  <div class="statslabel">Level:</div><div class="data"><?php echo $choosen->level; ?></div>
  <div class="statslabel">XP:</div><div class="data"><?php echo $choosen->xp; ?></div>
  <div class="sentence"><?php echo $choosen->sentence; ?></div> 
</div>

<div class="table">
  <div class="title">Account information</div>
  <?php
  $account=$choosen->getAccountInfo();
  ?>
  <div class="register">Registered at <?php echo $account["register"]; ?></div>
  <div class="account_status">
    This account is <span class="<?php echo $account["status"]; ?>"><?php echo $account["status"]; ?></span>
  </div> 
</div>


<div class="table">
  <div class="title">Attributes and statistics</div>

  <?php 
  foreach($choosen->attributes as $key=>$value) {  
    $old = array("atk", "def", "hp", "karma");
    $new = array("Attack Level", "Defense level", "Max health", "Karma");
    $key = str_replace($old, $new, $key);
    ?>
    <div class="statslabel"><?php echo ucwords($key) ?>:</div>
    <div class="data"><?php echo ucwords($value) ?></div>
    <?php } ?>
</div>

<div class="table">
  <div class="title">Equipment</div>
  <?php
  foreach($choosen->equipment as $slot=>$content) { 
	$old = array("head", "lhand", "rhand", "legs", "feet", "cloak", "finger");
	$new = array("head", "left hand", "right hand", "legs", "feet", "cloak", "finger");
	$slot = str_replace($old, $new, $slot);
    ?>
    <div class="row">
      <?php 
      if($content!="") { ?>
        <a href="?id=content/scripts/item&name=<?php echo $content; ?>&exact">
        <img src="<?php echo getItem($content)->showImage(); ?>" alt="<?php echo ucfirst($content); ?>"/>
        <div class="label"><?php echo ucwords($slot) ?></div>
        <div class="data"><?php echo ucfirst($content); ?></div>
        </a>
        <?php 
      } else {
        ?>
        <div class="emptybox"></div>
        <div class="label"><?php echo ucwords($slot) ?></div>
        <div class="data">Empty</div>
        <?php
      }
    ?>
    </div>
    <?php 
    } 
   ?>
</div>

<div class="table">
  <div class="title">Deaths</div>
 <?php
  /*
   * Let people know that this data is fake and it is a known bug.
   */
   showKnownBugNotice();

 ?>
 <?php
  $deaths=$choosen->getDeaths();

  if(count($deaths)==0) {
  	?>
  	You have never been killed.
  	<?php
  }
  
  foreach($deaths as $date=>$source) {
    if(existsMonster($source)) {
      /*
       * It was killed by a monster.
       */
      $monsters=getMonsters();
      foreach($monsters as $monster) {
        if($monster->name==$source) {
          ?>
          <div class="row">
            <a href="?id=content/scripts/monster&name=<?php echo $monster->name; ?>&exact">
            <img class="creature" src="<?php echo $monster->showImage(); ?>" alt="<?php echo $monster->name; ?>"/>
            Killed by a <div style="display: inline;" class="label"><?php echo $monster->name; ?></div>
            <div class="data">Happened at <?php echo $date; ?>.</div>
            <div style="margin-bottom: 50px;"></div>
            </a>
          </div>
          <?php
        }
      }
    } else {
      /*
       * It was killed by a player.
       */
      ?>
      <div class="row">
        <a href="?id=content/scripts/character&name=<?php echo $source; ?>">
        <?php
        $killer=getPlayer($source);
        ?>
        <img class="creature" src="createoutfit.php?outfit=<?php echo $killer->outfit; ?>" alt="<?php echo $source; ?>"/>
        Killed by <div style="display: inline;" class="label"><?php echo $source; ?></div>
        <div class="data">Happened at <?php echo $date; ?>.</div>
        <div style="margin-bottom: 50px;"></div>
        </a>
      </div>
    <?php
    }
  }
?>
</div>
  

<?php
endBox();
?>