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
			if ($account["status"] != 'active') {
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

<?php startBox('Character info for '.htmlspecialchars(utf8_encode($choosen->name))); ?>
<div class="table">
	<div class="title">Details</div>
	<div style="float: right">
		<?php if ($account["status"] == 'active') {?>
		<img class="bordered_image" src="<?php echo rewriteURL('/images/outfit/'.surlencode($choosen->outfit).'.png')?>" alt="Player outfit"/>
		<?php }?>
	</div>
	<div><span class="statslabel">Name:</span><span class="data"><?php echo htmlspecialchars(utf8_encode($choosen->name)); ?></span></div>
	<div><span class="statslabel">Age:</span><span class="data"><?php echo htmlspecialchars(printAge($choosen->age)); ?> hours</span></div>
	<div><span class="statslabel">Level:</span><span class="data"><?php echo htmlspecialchars($choosen->level); ?></span></div>
	<div class="married">
		<?php if(!empty($choosen->married)) {
			echo htmlspecialchars($choosen->name); ?> is married to <a href="<?php 
			echo rewriteURL('/character/'.htmlspecialchars($choosen->married).'.html');
			?>"><?php echo  htmlspecialchars($choosen->married);
		} ?></a> 
	</div>
	<?php if ($account["status"] == "active" && $choosen->sentence != '') {
		echo '<div class="sentence">' . htmlspecialchars(utf8_encode($choosen->sentence)). '</div>';
	}?>
</div>
<div style="height:220px">
<div class="table" style ="float:left; height:190px; margin-right: 12px;">
  <div class="title">Attributes and statistics</div>

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
	if ($choosen->xp > 10000) {
		echo htmlspecialchars(intval(intval($choosen->xp) / 1000)).'k';
	} else {
		echo htmlspecialchars($choosen->xp);
	}
	?></span></div>
    <div><span class="statslabel">DM Score:</span><span class="data"><?php echo htmlspecialchars($choosen->getHallOfFameScore('D')); ?></span></div>
    <div><span class="statslabel">Maze Score:</span><span class="data"><?php echo htmlspecialchars($choosen->getHallOfFameScore('M')); ?></span></div>
</div>
<div class="table" style = "float:left; width:115px; height:190px; margin-right: 12px;">
<div class="title">Equipment</div>
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
		$item->showImageWithPopup(ucfirst(str_replace($old, $new, $slot))). ': ';
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
<div class="title">Rank</div>

	<?php
	$ranks = getCharacterRanks($choosen->name);
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
	?>
</div>

</div>

<div class="table">
  <div class="title">Account information</div>
  <div class="register">Registered at <?php echo htmlspecialchars($account["register"]); ?></div>
  <div class="account_status">
    This account is <span class="<?php echo htmlspecialchars($account["status"]); ?>"><?php echo htmlspecialchars($account["status"]); ?></span>
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

<div class="table">
  <div class="title">Deaths</div>
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
		<img class="creature" src="<?php echo rewriteURL('/images/outfit/'.htmlspecialchars($killer->outfit).'.png'); ?>" alt="<?php echo utf8_encode($source); ?>"/>
		Killed by <span class="label"><?php echo htmlspecialchars(utf8_encode($source)); ?></span>
		<span class="block data">Happened at <?php echo htmlspecialchars($date); ?>.</span></a>
		<div style="margin-bottom: 50px;"></div>
	</div>
			<?php
		}
	}
?>
</div>

<?php
endBox();
	}
}
$page = new CharacterPage();
?>
