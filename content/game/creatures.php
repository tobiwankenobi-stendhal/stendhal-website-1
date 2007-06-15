<?php

define('AMOUNT',24);

if(isset($_REQUEST['base'])) {
  $base=$_REQUEST['base'];
} else {
  $base=0;
}

$monsters=getMonsters();
$classes=Monster::getClasses();
?>

<div style="float: left; width: 50%">

<?php
startBox('Most killed');
    list($m, $amount)=getMostKilledMonster($monsters);
    echo '<div><a class="creature" href="?id=content/scripts/monster&name='.$m->name.'">';
    echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
    echo '  <div class="creature_name">'.$m->name.'</div>';
    echo ' </a>';
    echo '  <div class="creature_killed">It was killed '.$amount.' times</div>';
    echo '</div>';
endBox();
?>
</div>
<div style="float: left; width: 50%">

<?php
startBox('Best killer');
    list($m, $amount)=getBestKillerMonster($monsters);
    echo '<div><a class="creature" href="?id=content/scripts/monster&name='.$m->name.'">';
    echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
    echo '  <div class="creature_name">'.$m->name.'</div>';
    echo ' </a>';
    echo '  <div class="creature_killed">It has killed '.$amount.' players</div>';
    echo '</div>';
endBox();
?>
</div>
<div style="float: left; width: 100%">
<?php

startBox('Creatures');
$i=0;
while($i<sizeof($monsters)) {
  echo '<div style="display: inline;" class="creatures_less">
      <a href="?id=content/game/creatures&base='.$i.'">'.($i==$base?'<b>':'').$i.($i==$base?'</b>':'').'</a>
      </div>';
  $i=$i+AMOUNT;
}

echo '<div style="position: relative; min-height: auto;">';

  for($i=$base;$i<min(sizeof($monsters),$base+AMOUNT);$i++) {
      $m=$monsters[$i];
      
      echo '<div class="creature"><a class="creature" href="?id=content/scripts/monster&name='.$m->name.'">';
      echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
      echo '  <div class="creature_name">'.$m->name.'</div>';
      echo ' </a>';
      echo '  <div class="creature_level">Level '.$m->level.'</div>';
      echo '</div>';
  }

echo '</div><div style="clear: left;"></div>';

endBox();
?>

</div>