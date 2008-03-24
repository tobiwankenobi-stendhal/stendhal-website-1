<?php
function getVariable($xmlStats, $type) {
  foreach($xmlStats['statistics'][0]['attrib'] as $i=>$j) {
    if(is_array($j) and $j['name']==$type) {
	  return $j['value'];
	}
  }
  
  return 0;
}


$content=implode("",file(STENDHAL_SERVER_STATS_XML));
$xmlStats = XML_unserialize($content);

startBox("Killed monsters on this server run");
$monsters=getMonsters();

foreach($monsters as $m) {
  $amount=getVariable($xmlStats,"Killed ".$m->name);
  ?>
  <a class="nodeco" href="?id=content/scripts/monster&name=<?php echo $m->name; ?>&exact">
  <div class="row">
    <img src="<?php echo $m->showImage(); ?>" alt="<?php echo $m->name; ?>"/>
    <div class="name"><?php echo $m->name; ?></div>
    <div class="amount"><?php echo $amount; ?> killed</div>
    <div style="clear: left;"></div>
  </div>
  </a>
  <?php
}
echo '<div style="clear: left;"></div>';
endBox();

?>