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
    echo '  <a href="?id=content/scripts/character&amp;name='.urlencode($p->name).'">';
    echo '  <div class="name">'.htmlspecialchars(utf8_encode($p->name)).'</div>';
    echo ' </a>';
    echo '</div>';
}
echo '</div>';
endBox();
?>