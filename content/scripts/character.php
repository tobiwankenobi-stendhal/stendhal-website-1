<?php
$name=$_REQUEST["name"];
$players=getPlayers('where name="'.$name.'"', 'name');

if(sizeof($players)==0) {
  die();
}
$choosen=$players[0];
?>

  <?php startBox('Character info for '.$choosen->name); ?>
    <div>
    <div class="extendedplayerBoxImage">
      <img src=createoutfit.php?outfit=<?php echo $choosen->outfit; ?>" alt="Player outfit"/>
    </div>
    <div>
      <div class="extendedplayerName"><?php echo $choosen->name; ?></div>
      <div>Level: <?php echo $choosen->level; ?></div>
      <div>XP: <?php echo $choosen->xp; ?></div>
      <div class="extendedplayerBoxQuote"><?php echo $choosen->sentence; ?></div>
    </div>
    </div>    
  <?php endBox(); ?>

  <?php startBox('Account information');?>
  <?php endBox(); ?>

  <?php 
    startBox('Deaths');
    $deaths=$choosen->getDeaths();
    foreach($deaths as $date=>$source) {
      if(existsMonster($source)) {
        $source='<a class="creature" href="?id=content/scripts/monster&name='.$source.'">'.$source.'</a>';
      } else {
        $source='<a href="?id=content/scripts/character&name='.$source.'">'.$source.'</a>';
      }
      
      echo '<div>Killed by '.$source.' at '.$date.'</div>';
    }

  ?>
  
  <?php endBox(); ?>
  
  <?php startBox('Attributes and statistics');?>
      <div class="extendedplayerStats">Statistics and attributes</div>
      <?php foreach($choosen->attributes as $key=>$value) { ?>
        <div><span><?php echo $key ?></span><span><?php echo $value ?></span></div>
      <?php } ?>
  <?php endBox(); ?>

  <?php startBox('Equipment');?>
      <div class="extendedplayerStats">Equipment</div>
      <?php foreach($choosen->equipment as $key=>$value) { 
              if($value!="null") {?>
                 <div><span><?php echo $key ?></span><span><?php echo $value ?></span></div>
      <?php 
                 }
              } 
       ?>
  <?php endBox(); ?>
