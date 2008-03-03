<?php

define('AMOUNT',24);

if(isset($_REQUEST['base'])) {
  $base=$_REQUEST['base'];
} else {
  $base=0;
}

$items=getItems();
$classes=Item::getClasses();
?>

<?php

startBox('Items');
if($base-AMOUNT>=0) {
echo '<div class="items_less">
      <a href="?id=content/game/items&base='.($base-AMOUNT).'">Previous items</a>
      </div>';
}

echo '<div style="position: relative; min-height: auto;">';

  for($i=$base;$i<min(sizeof($items),$base+AMOUNT);$i++) {
      $m=$items[$i];
      
      echo '<div class="item">';
      echo '  <img class="item" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
      echo '  <div class="item_name">'.$m->name.'</div>';
      echo '  <div class="item_description">'.$m->description.'</div>';
      echo '</div>';
  }

echo '</div><div style="clear: left;"></div>';

if($base+AMOUNT<sizeof($items)) {
echo '<div class="items_more">
      <a href="?id=content/game/items&base='.($base+AMOUNT).'">Next items</a>
      </div>';
}

endBox();
?>
?>
