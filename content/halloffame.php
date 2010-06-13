<?php 

define('TOTAL_HOF_PLAYERS',10);

function getXP($player) {
  return $player->xp;
}

function getWealth($player) {
  return $player->money;
}

function getAge($player) {
  return round($player->age/60,2);
}

function printAge($minutes) {
  $h=$minutes;
  $m=$minutes%60;
  
  return round($h).':'.round($m);
}

function getDMScore($player) {
  return $player->getDMScore();
}

function getTotalAtk($player) {
  return ($player->attributes['atk'])*(1+0.03*($player->level));
}

function getTotalDef($player) {
  return ($player->attributes['def'])*(1+0.03*($player->level));
}

class HallOfFamePage extends Page {
	private $filterFrom = '';
	private $filterWhere = '';

	public function writeHtmlHeader() {
		echo '<title>Hall of Fame'.STENDHAL_TITLE.'</title>';
	}

function renderListOfPlayers($list, $f, $postfix='') {
  $i=1;
  foreach($list as $player) {
    ?>
    <div class="row">
      <div class="position"><?php echo $i; ?></div>
      <a href="<?php echo rewriteURL('/character/'.surlencode($player->name).'.html'); ?>">
      <img class="small_image" src="<?php echo rewriteURL('/images/outfit/'.surlencode($player->outfit).'.png')?>" alt="Player outfit"/>
      <span class="block label"><?php echo htmlspecialchars(utf8_encode($player->name)); ?></span>
      <?php
      $var=$f($player);
      ?>
      <span class="block data"><?php echo $var.$postfix; ?></span>    
      </a>
      <div style="clear: left;"></div>
    </div>

    <?php
    $i++;
  }
}


function writeContent() {
	$this->setupFilter();
	$detail = $_REQUEST['detail'];
	if (!isset($detail)) {
		$this->renderOverview();
	} else {
		$this->renderDetails($detail);
	}
}

function setupFilter() {
	$filter = $_REQUEST['filter'];
	if (isset($filter)) {
		if ($filter=="alltimes") {
			$this->filterWhere='';
		} else if ($filter=="recent") {
			$this->filterWhere = ' AND character_stats.lastseen>date_sub(CURRENT_TIMESTAMP, interval 1 month)';
		} else if ($filter=="friends") {
			$this->filterFrom = ", (select characters.charname As charname from account, characters "
				. "WHERE username='".mysql_real_escape_string($_SESSION['username'])."' AND account.id=characters.player_id "
				. "UNION SELECT buddy.buddy FROM account, characters, buddy "
				. "WHERE username='".mysql_real_escape_string($_SESSION['username'])."' AND account.id=characters.player_id AND characters.charname=buddy.charname) As x ";
			$this->filterWhere = ' AND character_stats.name=x.charname';
		}
	}
}

function renderDetails($detail) {
	//TODO: add more
	startBox("Strongest players");
	?>
	<div class="bubble">Based on XP and Karma</div>
	<?php
	$players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.' AND character_stats.level>=10 '.$this->filterWhere, 'xp DESC, karma DESC');
	$this->renderListOfPlayers($players, 'getXP', " xp");
	endBox();
}

function renderOverview() {
	startBox("Best player"); 
$choosen=getBestPlayer($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere);
 ?>
  <div class="bubble">The best player is decided based on the relation between XP and age, so the best players are those the spend most time earning XP instead of being idle around in game.</div>    
  <div class="best">
    <a href="<?php echo rewriteURL('/character/'.surlencode($choosen->name).'.html'); ?>">
    <span class="block statslabel">Name:</span><span class="block data"><?php echo htmlspecialchars(utf8_encode($choosen->name)); ?></span>
    <span class="block statslabel">Age:</span><span class="block data"><?php echo getAge($choosen); ?> hours</span>
    <span class="block statslabel">Level:</span><span class="block data"><?php echo $choosen->level; ?></span>
    <span class="block statslabel">XP:</span><span class="block data"><?php echo $choosen->xp; ?></span>
    <?php if ($choosen->sentence != '') {echo '<span class="block sentence">'.$choosen->sentence.'</span>';}?>
    </a>
  </div> 
  <img class="bordered_image" src="<?php echo rewriteURL('/images/outfit/'.surlencode($choosen->outfit).'.png')?>" alt="">
 <?php endBox(); ?>
<div style="float: left; width: 34%">
<?php
	
startBox("Strongest players");
  ?>
  <div class="bubble">Based on XP and Karma</div>
  <?php
  $players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'xp DESC, karma DESC', 'limit '.TOTAL_HOF_PLAYERS);
  $this->renderListOfPlayers($players, 'getXP', " xp");
  ##echo '<a href="'.rewriteURL('/world/hall-of-fame-strongest.html').'">More</a>';
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php


startBox("Richest players");
  ?>
  <div class="bubble">Based on the amount of money</div>
  <?php
  $players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'money desc', 'limit '.TOTAL_HOF_PLAYERS);
  $this->renderListOfPlayers($players, 'getWealth', ' coins');
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php

startBox("Eldest players");
  ?>
  <div class="bubble">Based on the age in hours</div>
  <?php
  $players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'age desc', 'limit '.TOTAL_HOF_PLAYERS);
  $this->renderListOfPlayers($players, 'getAge', ' hours');
endBox();
?>
</div>
<div style="float: left; width: 33%">
<?php
startBox("Deathmatch heroes");
  ?>
  <div class="bubble">Based on the deathmatch score</div>
  <?php
  // TODO: add filters
  $players=getDMHeroes($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere.' and', 'limit '.TOTAL_HOF_PLAYERS);
  $this->renderListOfPlayers($players, 'getDMScore',' points');
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php
startBox("Best attackers");
  ?>
<div class="bubble">Based on atk*(1+0.03*level)</div>
  <?php
    $players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'atk*(1+0.03*level) desc', 'limit '.TOTAL_HOF_PLAYERS);
  $this->renderListOfPlayers($players, 'getTotalAtk', " total atk");
endBox();

?>
</div>
<div style="float: left; width: 33%">
<?php
startBox("Best defenders");
  ?>
<div class="bubble">Based on def*(1+0.03*level)</div>
  <?php
   $players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'def*(1+0.03*level) desc', 'limit '.TOTAL_HOF_PLAYERS);
  $this->renderListOfPlayers($players, 'getTotalDef', " total def");
endBox();

?>
</div>
<?php
	}

}
$page = new HallOfFamePage();
?>