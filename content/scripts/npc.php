<?php

class NPCPage extends Page {
	private $name;
	private $npcs;

	public function __construct() {
		$this->name = $_REQUEST["name"];
		$this->npcs = NPC::getNPCs('where name="'.mysql_real_escape_string($this->name).'"', 'name');
	}

	public function writeHtmlHeader() {
		echo '<title>NPC '.htmlspecialchars($this->name).STENDHAL_TITLE.'</title>';
		if(sizeof($this->npcs)==0) {
			echo '<meta name="robots" content="noindex">';
		}
	}

	function writeContent() {
		

if(sizeof($this->npcs)==0) {
  startBox("No such NPC");
  ?>
  There is no such NPC at Stendhal.<br>
  Please make sure you spelled it correctly.
  <?php
  endBox();
  return;
}
$npc=$this->npcs[0];
?>

<?php startBox('NPC info for '.$npc->name); ?>
<div class="table">
  <div class="title">Details</div>
  <img class="bordered_image" src="<?php echo $npc->imagefile ?>" alt="">
  <div class="statslabel">Name:</div><div class="data"><?php echo $npc->name; ?></div>
  <div class="statslabel">Zone:</div><div class="data"><?php echo $npc->zone . ' ' . $npc->pos; ?></div>
  <?php if ($npc->level > 0) {?>
	  <div class="statslabel">Level:</div><div class="data"><?php echo $npc->level; ?></div>
	  <div class="statslabel">HP:</div><div class="data"><?php echo $npc->hp . '/' . $npc->base_hp; ?></div>
  <?php }?>
  
  <?php if ((isset($npc->job) && strlen($npc->job) > 0)) {?>
  	<div class="sentence"><?php echo str_replace('#', '', $npc->job); ?></div> 
  <?php }?>
  <?php if ((isset($npc->description) && strlen($npc->description) > 0)) {?>
	<div class="sentence"><?php echo $npc->description; ?></div> 
  <?php }?>
</div>

<?php
endBox();
	}
}
$page = new NPCPage();
?>