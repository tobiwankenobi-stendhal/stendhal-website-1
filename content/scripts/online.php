<?php
$players=getOnlinePlayers();
startBox('Online Players');

if(sizeof($players)==0) {
  echo 'There are no logged players';
}
echo '<div style="height: 700px;">';
foreach($players as $p) {
    echo '<div class="onlinePlayer">';
    echo '  <img src="createoutfit.php?outfit='.$p->outfit.'" alt="Player outfit"/>';
    echo '  <a href="?id=content/scripts/character&name='.$p->name.'">';
    echo '  <div class="name">'.$p->name.'</div>';
    echo ' </a>';
    echo '</div>';
}
echo '</div>';
endBox();
?>