<?php
$name=$_REQUEST["name"];
$players=getPlayers('where name="'.addslashes($name).'"', 'name');

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
      <div class="extendedplayerName"><b>Name:</b> <?php echo $choosen->name; ?></div>
      <div><b>Level:</b> <?php echo $choosen->level; ?></div>
      <div><b>XP:</b> <?php echo $choosen->xp; ?></div>
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
	<?php 
	//replace text
	$old = array("atk", "def", "hp", "karma");
	$new = array("Attack Level", "defense level", "max health", "karma");
	$key = str_replace($old, $new, $key);
	
	// "_" -> " "
	$value = str_replace("_", " ", $value);
	
	//tada! 
	?>
        <div><span><b><?php echo ucwords($key) ?>:</b> </span><span><i><?php echo ucwords($value) ?></i></span></div>
      <?php } ?>
  <?php endBox(); ?>

  <?php startBox('Equipment');?>
      <div class="extendedplayerStats">Equipment</div>
      <?php foreach($choosen->equipment as $key=>$value) { 
              if($value!="null") {?>
	<?php 
	//replace text
	$old = array("head", "lhand", "rhand", "legs", "feet", "cloak", "finger");
	$new = array("head", "left hand", "right hand", "legs", "feet", "cloak", "finger");
	$key = str_replace($old, $new, $key);
	
	// "_" -> " "
	$value = str_replace("_", " ", $value);
	
	//tada! 
	?>

                 <div><span><b><?php echo ucwords($key) ?>:</b> </span><span><i><?php echo ucwords($value) ?></i></span></div>
      <?php 
                 }
              } 
       ?>
  <?php endBox(); ?>
