<?php
function getVariable($xmlStats, $type) {
  foreach($xmlStats['statistics'][0]['attrib'] as $i=>$j) {
    if(is_array($j) and $j['name']==$type) {
	  return $j['value'];
	}
  }
  
  return 0;
}

function getServerUptime($xmltree) {
	return $xmltree['statistics'][0]['uptime']['0 attr']['value'];
}

$content=implode("",file(STENDHAL_SERVER_STATS_XML));
$xmlStats = XML_unserialize($content);

startBox("Detailed statistics");
?>
<div class="uptime">
  <?php echo getServerUptime($xmlStats); ?> seconds since last server reset.
</div>
<div class="variable">
  <div class="title">Bytes managed</div>
  <div class="table">
    <div class="label">Received</div>
    <div class="data"><?php echo getVariable($xmlStats,"Bytes recv"); ?></div>
  </div>
  <div class="table">
    <div class="label">Send</div>
    <div class="data"><?php echo getVariable($xmlStats,"Bytes send"); ?></div>
  </div>
</div>

<div class="variable">
  <div class="title">Messages managed</div>
  <div class="table">
    <div class="label">Received</div>
    <div class="data"><?php echo getVariable($xmlStats,"Message recv"); ?></div>
  </div>
  <div class="table">
    <div class="label">Send</div>
    <div class="data"><?php echo getVariable($xmlStats,"Message send"); ?></div>
  </div>
</div>

<div class="variable">
  <div class="title">Players handled</div>
  <?
  $list=array("login","invalid login","logout","timeout");
  
  foreach($list as $action) {
    ?>
    <div class="table">
      <div class="label"><?php echo ucfirst($action); ?></div>
      <div class="data"><?php echo getVariable($xmlStats,"Players ".$action); ?></div>
    </div>
  <?php  
  } 
  ?>
</div>

<div class="variable">
  <div class="title">Actions managed</div>
  <div class="table">
    <div class="label">Total</div>
    <div class="data"><?php echo getVariable($xmlStats,"Actions added"); ?></div>
  </div>
  <?
  $list=array("move","chat","attack","inspect","who","where");
  
  foreach($list as $action) {
    ?>
    <div class="table">
      <div class="label"><?php echo ucfirst($action); ?></div>
      <div class="data"><?php echo getVariable($xmlStats,"Actions ".$action); ?></div>
    </div>
  <?php  
  } 
  ?>
</div>
<?php
endBox();
?>