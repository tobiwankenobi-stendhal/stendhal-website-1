<?php
$name=$_REQUEST["name"];
$npcs=NPC::getNPCs('where name="'.mysql_real_escape_string($name).'"', 'name');

if(sizeof($npcs)==0) {
  startBox("No such NPC");
  ?>
  There is no such NPC at Stendhal.<br>
  Please make sure you spelled it correctly.
  <?php
  endBox();
  return;
}
$npc=$npcs[0];
?>

<?php startBox('NPC info for '.$npc->name); ?>
<div class="table">
  <div class="title">Details</div>
  <img class="bordered_image" src="<?php echo $npc->imagefile ?>" alt="NPC outfit"/>
  <div class="statslabel">Name:</div><div class="data"><?php echo $npc->name; ?></div>
  <div class="statslabel">Zone:</div><div class="data"><?php echo $npc->zone . $npc->pos; ?></div>
  <?php if ($npc->level > 0) {?>
	  <div class="statslabel">Level:</div><div class="data"><?php echo $npc->level; ?></div>
	  <div class="statslabel">HP:</div><div class="data"><?php echo $npc->hp . '/' . $npc->base_hp; ?></div>
  <?php }?>
  
  <?php if ((isset($npc->job) && strlen($npc->job) > 0)) {?>
  	<div class="sentence"><?php echo $npc->job; ?></div> 
  <?php }?>
  <?php if ((isset($npc->description) && strlen($npc->description) > 0)) {?>
	<div class="sentence"><?php echo $npc->description; ?></div> 
  <?php }?>
</div>

<?php
endBox();
?>