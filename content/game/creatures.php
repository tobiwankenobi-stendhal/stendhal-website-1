<?php

$monsters=getMonsters();
$classes=Monster::getClasses();

startBox('Creatures');
foreach($classes as $class=>$idontcare) {
  echo '<div><h1>'.$class.'</h1></div>';
  foreach($monsters as $m) {
    if($class==$m->class) {
      echo '<div>';
      echo '  <img src="'.$m->gfx.'" alt="'.$m->name.'"/>';
      echo '  <div>'.$m->name.'</div>';
      echo '  <div>'.$m->description.'</div>';
      echo '  <div>'.$m->level.'</div>';
      echo '  <div>';
      foreach($m->attributes as $k=>$v) {
        echo '  <div><span>'.$k.'</span><span>'.$v.'</span></div>';
      }
      echo '  </div>';
      echo '</div>';
    }
  }
}

endBox();
?>
