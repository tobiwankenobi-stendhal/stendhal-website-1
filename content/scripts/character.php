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
  <img class="bordered_image" src="<?php echo rewriteURL('/images/outfit/'.urlencode($choosen->outfit).'.png')?>" alt="Player outfit"/>
  <div class="statslabel">Name:</div><div class="data"><?php echo htmlspecialchars(utf8_encode($choosen->name)); ?></div>
  <div class="statslabel">Age:</div><div class="data"><?php echo htmlspecialchars(printAge($choosen->age)); ?> hours</div>
  <div class="statslabel">Level:</div><div class="data"><?php echo htmlspecialchars($choosen->level); ?></div>
  <div class="statslabel">XP:</div><div class="data"><?php echo htmlspecialchars($choosen->xp); ?></div>
  <div class="statslabel">DM Score:</div><div class="data"><?php echo htmlspecialchars($choosen->getDMScore()); ?></div>
  <?php if ($account["status"] == "active") {
  	echo '<div class="sentence">' . htmlspecialchars(utf8_encode($choosen->sentence)). '</div>';
  }?>
</div>

<div class="table">
  <div class="title">Account information</div>
  <div class="register">Registered at <?php echo htmlspecialchars($account["register"]); ?></div>
  <div class="account_status">
    This account is <span class="<?php echo htmlspecialchars($account["status"]); ?>"><?php echo htmlspecialchars($account["status"]); ?></span>
  </div> 
  <?php if (($account["status"]) == 'active' && ($choosen->adminlevel > 0) && ($choosen->name != 'postman')) {
  	if ($choosen->adminlevel > 100) {
			echo '<div class="admin">This account is a game master with adminlevel <a href="/wiki/index.php/Stendhal:Administration#Required_adminlevel">' . htmlspecialchars($choosen->adminlevel). '</a>.</div>';
		} else {
			echo '<div class="admin">This player volunteered to answer support questions about Stendhal.</div>';
		}
  }
  ?>
</div>


<div class="table">
  <div class="title">Attributes and statistics</div>

  <?php 
  foreach($choosen->attributes as $key=>$value) {  
    $old = array("atk", "def", "hp", "karma");
    $new = array("Attack Level", "Defense level", "Current health", "Karma");
    $key = str_replace($old, $new, $key);
    ?>
    <div class="statslabel"><?php echo htmlspecialchars(ucwords($key)) ?>:</div>
    <div class="data"><?php echo htmlspecialchars(ucwords($value)) ?></div>
    <?php } ?>
</div>

<div class="table">
  <div class="title">Equipment</div>
  <?php
  foreach($choosen->equipment as $slot=>$content) { 
	$old = array("head", "lhand", "rhand", "legs", "feet", "cloak", "finger");
	$new = array("head", "left hand", "right hand", "legs", "feet", "cloak", "finger");
	$slot = str_replace($old, $new, $slot);
    ?>
    <div class="row">
      <?php 
      if($content!="") {
      	$item = getItem($content);
        echo '<a href="'.rewriteURL('/item/'.htmlspecialchars($item->class).'/'.htmlspecialchars($content).'.html').'">'; ?>
        <img src="<?php echo htmlspecialchars($item->showImage()); ?>" alt=" "/>
        <div class="label"><?php echo htmlspecialchars(ucwords($slot)) ?></div>
        <div class="data"><?php echo htmlspecialchars(ucfirst($content)); ?></div>
        </a>
        <?php 
      } else {
        ?>
        <div class="emptybox"></div>
        <div class="label"><?php echo htmlspecialchars(ucwords($slot)) ?></div>
        <div class="data">Empty</div>
        <?php
      }
    ?>
    </div>
    <?php 
    } 
   ?>
</div>

<div class="table">
  <div class="title">Deaths</div>
 <?php
  /*
   * Let people know that this data is fake and it is a known bug.
   */
   showKnownBugNotice();

 ?>
 <?php
  $deaths=$choosen->getDeaths();

  if(count($deaths)==0) {
  	?>
  	Not recently killed.
  	<?php
  }
  
  foreach($deaths as $date=>$source) {
    if(existsMonster($source)) {
      /*
       * It was killed by a monster.
       */
      $monsters=getMonsters();
      foreach($monsters as $monster) {
        if($monster->name==$source) {
          ?>
          <div class="row">
            <?php echo '<a href="'.rewriteURL('/creature/'.htmlspecialchars($monster->name).'.html').'">'; ?>
            <img class="creature" src="<?php echo htmlspecialchars($monster->showImage()); ?>" alt=""/>
            Killed by a <div style="display: inline;" class="label"><?php echo htmlspecialchars($monster->name); ?></div>
            <div class="data">Happened at <?php echo htmlspecialchars($date); ?>.</div>
            <div style="margin-bottom: 50px;"></div>
            </a>
          </div>
          <?php
        }
      }
    } else {
      /*
       * It was killed by a player.
       */
      ?>
      <div class="row">
        <?php echo '<a href="'.rewriteURL('/character/'.htmlspecialchars(urlencode($source)).'.html').'">'?>
        <?php
        $killer=getPlayer($source);
        ?>
        <img class="creature" src="createoutfit.php?outfit=<?php echo htmlspecialchars($killer->outfit); ?>" alt="<?php echo utf8_encode($source); ?>"/>
        Killed by <div style="display: inline;" class="label"><?php echo htmlspecialchars(utf8_encode($source)); ?></div>
        <div class="data">Happened at <?php echo htmlspecialchars($date); ?>.</div>
        <div style="margin-bottom: 50px;"></div>
        </a>
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
