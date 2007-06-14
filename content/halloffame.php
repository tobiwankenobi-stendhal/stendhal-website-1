<?php startBox("Best player"); 
  $choosen=getBestPlayer();
 ?>
    <div class="hofBest">
    <img src=createoutfit.php?outfit=<?php echo $choosen->outfit; ?>" alt="Player outfit"/>
    <div>
      <div class="bestName"><?php echo $choosen->name; ?></div>
      <div class="bestLevel">Level: <?php echo $choosen->level; ?></div>
      <div class="bestXP">XP: <?php echo $choosen->xp; ?></div>
      <div class="bestSentence"><?php echo $choosen->sentence; ?></div>
    </div>
    </div>    
<?php endBox(); ?>


<div style="float: left; width: 34%">
<?php

startBox("Strongest players");
$players= getPlayers('','xp desc', 'limit 10');

$i=1;
foreach($players as $player) {
  echo '<div class="hofLine">';  
  echo '<div class="hofPosition">'.$i.'</div>';
  echo '<div class="hofPlayer">';
  echo '<a href="?id=content/scripts/character&name='.$player->name.'">';
  echo ' <div class="hofOutfit"><img src="createoutfit.php?outfit='.$player->outfit.'" alt="outfit"/></div>';
  echo ' <div class="hofName">'.$player->name.'</div>';
  echo '</a>';
  echo ' <div class="hofVariable">Level: '.$player->level.'</div>';
  echo '</div>';
  echo '</div>';
  $i++;  
}
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php

startBox("Richest players");
$players= getPlayers('','money desc', 'limit 10');

$i=1;
foreach($players as $player) {
  echo '<div class="hofLine">';  
  echo '<div class="hofPosition">'.$i.'</div>';
  echo '<div class="hofPlayer">';
  echo '<a href="?id=content/scripts/character&name='.$player->name.'">';
  echo ' <div class="hofOutfit"><img src="createoutfit.php?outfit='.$player->outfit.'" alt="outfit"/></div>';
  echo ' <div class="hofName">'.$player->name.'</div>';
  echo '</a>';
  echo ' <div class="hofVariable">Money: '.$player->money.'</div>';
  echo '</div>';
  echo '</div>';
  $i++;  
}
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php

startBox("Oldest players");
$players= getPlayers('','age desc', 'limit 10');

$i=1;
foreach($players as $player) {
  echo '<div class="hofLine">';  
  echo '<div class="hofPosition">'.$i.'</div>';
  echo '<div class="hofPlayer">';
  echo '<a href="?id=content/scripts/character&name='.$player->name.'">';
  echo ' <div class="hofOutfit"><img src="createoutfit.php?outfit='.$player->outfit.'" alt="outfit"/></div>';
  echo ' <div class="hofName">'.$player->name.'</div>';
  echo '</a>';
  echo ' <div class="hofVariable">Age: <small>'.printAge($player->age).'</small></div>';
  echo '</div>';
  echo '</div>';
  $i++;  
}
endBox();

function printAge($minutes) {
  $h=$minutes/60;
  $m=$minutes%60;
  
  return round($h).':'.round($m);
}
?>
</div>