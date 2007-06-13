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
    
    $m->fillKillKilledData();
    startBox("Killed by Player");
      $data='';
      foreach($m->kills as $day=>$amount) {
        $data=$data.$amount.',';
      }
      
      echo '<img style="padding: 4px; border: 1px solid black;" src="bargraph.php?data='.$data.'"/>';
    endBox();

    startBox("Killed by ".$m->name);
      $data='';
      foreach($m->killed as $day=>$amount) {
        $data=$data.$amount.',';
      }
      
      echo '<img style="padding: 4px; border: 1px solid black;" src="bargraph.php?data='.$data.'"/>';
    endBox();
  }
}

?>