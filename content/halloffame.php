<?php 

define('TOTAL_HOF_PLAYERS',10);

startBox("Best player"); 
  $choosen=getBestPlayer();
 ?>
  <div class="bubble">The best player is decided based on the relation between XP and age, so the best players are those the spend most time earning XP instead of being idle around in game.</div>    
  <div class="best">
    <a href="?id=content/scripts/character&name=<?php echo $choosen->name; ?>">
    <div class="statslabel">Name:</div><div class="data"><?php echo $choosen->name; ?></div>
    <div class="statslabel">Age:</div><div class="data"><?php echo printAge($choosen->age); ?> hours</div>
    <div class="statslabel">Level:</div><div class="data"><?php echo $choosen->level; ?></div>
    <div class="statslabel">XP:</div><div class="data"><?php echo $choosen->xp; ?></div>
    <div class="sentence"><?php echo $choosen->sentence; ?></div>
    </a>
  </div> 
  <img class="bordered_image" src="createoutfit.php?outfit=<?php echo $choosen->outfit; ?>" alt="Player outfit"/>
 <?php endBox(); ?>


<div style="float: left; width: 34%">
<?php

function renderListOfPlayers($list, $f, $postfix='') {
  $i=1;
  foreach($list as $player) {
    ?>
    <div class="row">
      <div class="position"><?php echo $i; ?></div>
      <a href="?id=content/scripts/character&name=<?php echo $player->name; ?>">
      <img class="small_image" src="createoutfit.php?outfit=<?php echo $player->outfit; ?>" alt="Player outfit"/>
      <div class="label"><?php echo $player->name; ?></div>
      <?php
      $var=$f($player);
      ?>
      <div class="data"><?php echo $var.$postfix; ?></div>    
      </a>
      <div style="clear: left;"></div>
    </div>

    <?php
    $i++;  
  }	
}

function getXP($player) {
  return $player->xp;
}

startBox("Strongest players");
  ?>
  <div class="bubble">Based on the amount of XP</div>
  <?php
  $players= getPlayers('','xp desc', 'limit '.TOTAL_HOF_PLAYERS);
  renderListOfPlayers($players, 'getXP', " xp");
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php
function getWealth($player) {
  return $player->money;
}

startBox("Richest players");
  ?>
  <div class="bubble">Based on the amount of money</div>
  <?php
  $players= getPlayers('','money desc', 'limit '.TOTAL_HOF_PLAYERS);
  renderListOfPlayers($players, 'getWealth', ' coins');
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php

startBox("Eldest players");
  ?>
  <div class="bubble">Based on the age in hours</div>
  <?php
  $players= getPlayers('','age desc', 'limit '.TOTAL_HOF_PLAYERS);
  renderListOfPlayers($players, 'getAge', ' hours');
endBox();

function getAge($player) {
  return round($player->age/60,2);
}

function printAge($minutes) {
  $h=$minutes;
  $m=$minutes%60;
  
  return round($h).':'.round($m);
}
?>
</div>