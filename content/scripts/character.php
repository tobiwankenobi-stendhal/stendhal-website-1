<?php
function printAge($minutes) {
	return round($minutes/60,2);
}

class CharacterPage extends Page {
	private $name;
	private $players;

	public function __construct() {
		$this->name=$_REQUEST["name"];
		$this->players=getPlayers('where name="'.addslashes($this->name).'"', 'name');
	}

	public function writeHtmlHeader() {
		if(sizeof($this->players) > 0) {
			$choosen = $this->players[0];
			$account = $choosen->getAccountInfo();
			if (($account["status"] != 'active') || ($account["charstatus"] != 'active')) {
				echo '<meta name="robots" content="noindex">'."\n";
			}
		} else {
			echo '<meta name="robots" content="noindex">'."\n";
		}
		echo '<title>Player '.htmlspecialchars($this->name).STENDHAL_TITLE.'</title>';
		echo '<script type="text/javascript" src="'.STENDHAL_FOLDER.'/css/overlib.js"></script>';
	}

	function writeContent() {


if(sizeof($this->players)==0) {
	startBox("No such player");
	?>
	There is no such player at Stendhal.<br>
	Please make sure you spelled it correctly.
	<?php
	endBox();
	return;
}
$choosen=$this->players[0];
$account=$choosen->getAccountInfo();
?>

<?php startBox('Character info for '.htmlspecialchars($choosen->name)); ?>
<div class="table">
	<div class="title">Details</div>
	<div style="float: right">
		<?php if (($account["status"] == 'active') && ($account["charstatus"] == 'active')) {?>
		<img class="bordered_image" src="<?php echo rewriteURL('/images/outfit/'.surlencode($choosen->outfit).'.png')?>" alt="Player outfit"/>
		<?php }?>
	</div>
	<div><span class="statslabel">Name:</span><span class="data"><?php echo htmlspecialchars($choosen->name); ?></span></div>
	<div><span class="statslabel">Age:</span><span class="data"><?php echo htmlspecialchars(printAge($choosen->age)); ?> hours</span></div>
	<div><span class="statslabel">Level:</span><span class="data"><?php echo htmlspecialchars($choosen->level); ?></span></div>
	<div class="married">
		<?php if(!empty($choosen->married)) {
			echo htmlspecialchars($choosen->name); ?> is married to <a href="<?php 
			echo rewriteURL('/character/'.htmlspecialchars($choosen->married).'.html');
			?>"><?php echo  htmlspecialchars($choosen->married);
		} ?></a> 
	</div>
	<?php if ($account["status"] == "active" && $account["charstatus"] == 'active' && $choosen->sentence != '') {
		echo '<div class="sentence">' . htmlspecialchars($choosen->sentence). '</div>';
	}?>
</div>
<div style="height:220px">
<div class="table" style ="float:left; height:190px; margin-right: 12px;">
  <div class="title"><a name="attributes">Attributes and statistics</a></div>

  <?php
  foreach($choosen->attributes as $key=>$value) {
    $old = array("atk", "def", "hp", "karma");
    $new = array("Attack Level", "Defense level", "Current health", "Karma");
    $key = str_replace($old, $new, $key);
    ?>
    <div><span class="statslabel"><?php echo htmlspecialchars(ucwords($key)) ?>:</span>
    <span class="data"><?php echo htmlspecialchars(ucwords($value)) ?></span></div>
    <?php } ?>
	<div><span class="statslabel">XP:</span><span class="data"><?php
	if ($choosen->xp > 100000000) {
		echo htmlspecialchars(intval(intval($choosen->xp) / 1000000)).'m';
	} else if ($choosen->xp > 10000) {
		echo htmlspecialchars(intval(intval($choosen->xp) / 1000)).'k';
	} else {
		echo htmlspecialchars($choosen->xp);
	}
	?></span></div>
    <div><span class="statslabel">DM Score:</span><span class="data"><?php echo htmlspecialchars($choosen->getHallOfFameScore('D')); ?></span></div>
    <div><span class="statslabel">Maze Score:</span><span class="data"><?php echo htmlspecialchars($choosen->getHallOfFameScore('M')); ?></span></div>
    <?php $tradescore = $choosen->getHallOfFameScore('T');
    	  if ($tradescore > 0) {
    			echo '<div><span class="statslabel">Trading Score:</span><span class="data">'.htmlspecialchars($tradescore).'</span></div>';
    	  }
    ?>
</div>
<div class="table" style = "float:left; width:115px; height:190px; margin-right: 12px;">
<div class="title"><a name="equipment">Equipment</a></div>
<div class ="equipment">
<?php
foreach($choosen->equipment as $slot=>$content) {
	$old = array("head", "armor", "lhand", "rhand", "legs", "feet", "cloak");
	$new = array("head", "armor", "left hand", "right hand", "legs", "feet", "cloak"); 

	?>
	<div class="equiprow <?php echo $slot;?>">
	<?php 
	if($content!="") {
		$item = getItem($content);
		if (isset($item)) {
			$item->showImageWithPopup(ucfirst(str_replace($old, $new, $slot))). ': ';
		}
	} else {
		?>
	<div class="emptybox"></div>
		<?php
	}
	?>
	</div>
	<?php
}
	?>
</div>
</div>
<div class="table" style ="float:left; height:190px; margin-right: 12px;">
<div class="title"><a name="rank">Rank</a></div>

	<?php
	$ranks = getCharacterRanks($choosen->name);
	if ($choosen->adminlevel >= 600) {
		echo 'Game masters <br>are not normal<br> players and<br> therefore <br>don\'t appear in <br>the hall of fame.';
	} else if (count($ranks) == 1 && $ranks['__']) {
		echo htmlspecialchars($choosen->name). ' is <br>new in Stendhal.<br><br>Please check back <br>tomorrow because <br>ranks are only <br>calculated once a day.';
	} else {
		$names = array('Best', 'Strongest', 'Richest', 'Eldest', 'Deathmatch', 'Attackers', 'Defenders', 'Maze Runner');
		$fametypes = array('B', 'X', 'W', 'A', 'D', 'T', 'F', 'M');
		for ($i = 0; $i < count($names); $i++) {
			echo '<div><span class="statslabel">'.$names[$i].'</span><span class="data">';
			if (isset($ranks[$fametypes[$i]])) {
				echo htmlspecialchars($ranks[$fametypes[$i]]);
			} else {
				echo '-';
			}
			echo '</span></div>';
		}
	}
	?>
</div>

</div>

<?php 
	if(STENDHAL_ACHIEVEMENTS) {
		$this->renderAchievements();
	}
?>

<div class="table">
  <div class="title"><a name="death">Deaths</a></div>
 <?php
  $deaths=$choosen->getDeaths();

	if(count($deaths)==0) {
		?>
		Not recently killed.
		<?php
	}

	foreach($deaths as $date=>$source) {
		if(existsMonster($source)) {

			// It was killed by a monster.
			$monsters=getMonsters();
			foreach($monsters as $monster) {
				if($monster->name==$source) {
			?>
	<div class="row">
		<?php 
		$monster->showImageWithPopup();
		?>
		Killed by <?php echo a_an($monster->name) ?> <span class="label"><?php echo htmlspecialchars($monster->name); ?></span>
		<span class="block data">Happened at <?php echo htmlspecialchars($date); ?>.</span>
		<div style="margin-bottom: 50px;"></div>
	</div>
			<?php
				}
			}
		} else {

			// It was killed by a player.
			?>
	<div class="row">
		<?php
		echo '<a href="'.rewriteURL('/character/'.surlencode($source).'.html').'">';
		$killer=getPlayer($source);
		?>
		<img class="creature" src="<?php echo rewriteURL('/images/outfit/'.htmlspecialchars($killer->outfit).'.png'); ?>" alt="<?php echo htmlspecialchars($source); ?>"/>
		Killed by <span class="label"><?php echo htmlspecialchars($source); ?></span>
		<span class="block data">Happened at <?php echo htmlspecialchars($date); ?>.</span></a>
		<div style="margin-bottom: 50px;"></div>
	</div>
<?php
		}
	}
?>
</div>

<div class="table">
  <div class="title"><a name="account">Account information</a></div>
  <div class="register">Registered at <?php echo htmlspecialchars($account["register"]); ?></div>
  <div class="account_status">
  <?php
	$status = $account["status"];
	if ($account["charstatus"] != 'active') {
		$status=$account["charstatus"];
	}
  ?>
    This account is <span class="<?php echo htmlspecialchars($status); ?>"><?php echo htmlspecialchars($status); ?></span>
  </div>
  <?php if (($account["status"]) == 'active' && ($choosen->adminlevel > 0) && ($choosen->name != 'postman')) {
    if ($choosen->adminlevel < 300) {
            echo '<div class="admin">This player volunteered to answer support questions about Stendhal.</div>';
        } else if ($choosen->adminlevel < 500) {
            echo '<div class="admin">This player has adminlevel <a href="/wiki/Stendhal:Administration#Required_adminlevel">' . htmlspecialchars($choosen->adminlevel). '</a> to provide support for Stendhal players.</div>';
        } else {
            echo '<div class="admin">This account is a game master with adminlevel <a href="/wiki/Stendhal:Administration#Required_adminlevel">' . htmlspecialchars($choosen->adminlevel). '</a>.</div>';
        }
    }
  ?>
</div>

<?php

endBox();
	}

	private function renderAchievements() {
		?>
	<style type="text/css">
		.achievementOpen{
			filter:alpha(opacity=30);
			-moz-opacity:0.3;
			-khtml-opacity: 0.3;
			opacity: 0.3;
		}
	</style>
	<div class="table">
	<div class="title"><a name="achievements">Achievements</a></div>
	<?php
		if($this->players[0]->lastseen < '2011-02-26 11:35') {
	?>
		<div class="bubble">This detail page previews achievements. Reached achievements will be updated on next login.</div>
	<?php
		}
	?> 
		<?php
		$list = Achievement::getAchievementForCharacter($this->name);
		$lastCategory = '';
		foreach ($list as $achievement) {
			if ($achievement->category != $lastCategory) {
				if ($lastCategory != '') {
					echo "</div>\n";
				}
				echo '<div>';
				$lastCategory = $achievement->category;
			}
			if ($achievement->count > 0) {
				$class = "achievementDone";
			} else {
				$class = "achievementOpen";
			}
			echo '<img class="'.$class.'" src="/images/achievements/'.htmlspecialchars(strtolower($achievement->category)).'.png" title="'.htmlspecialchars($achievement->title).': '.htmlspecialchars($achievement->description).'"> ';
		}
		echo '</div>';
		?>
		</div>
		<?php
	}
}
$page = new CharacterPage();
?>
