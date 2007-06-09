<?php 

$name=$_REQUEST['name'];

$monsters=getMonsters();
foreach($monsters as $m) {
  if($m->name==$name) {
    startBox($name);
      echo '<div class="creature">';
      echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
      echo '  <div class="creature_name">'.$m->name.'</div>';
      echo '  <div>Level '.$m->level.'</div>';
      echo '  <div>'.$m->description.'</div>';
      echo '<div>';
      foreach($m->attributes as $k=>$v) {
        echo '<div><span>'.$k.'</span><span>'.$v.'</span></div>';
      }
      echo '</div>';
      echo '</div>';
    endBox();      
  }
}

?>