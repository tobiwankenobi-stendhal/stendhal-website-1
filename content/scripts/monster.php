<?php 

function printRespawn($turns) {
  return round($turns *0.3/60,2);
}

function renderAmount($amount) {
  $amount=str_replace("[","",$amount);
  $amount=str_replace("]","",$amount);
  list($min,$max)=explode(",",$amount);
  
  if($min!=$max) {
    return "between $min and $max.";
  } else {
  	return "exactly $min.";
  }
}

class MonsterPage extends Page {
	private $name;
	private $monsters;
	private $isExact;
	private $found;

	public function __construct() {
		$this->name = preg_replace('/_/', ' ', $_REQUEST['name']);
		$this->isExact = isset($_REQUEST['exact']);
		$this->monsters = getMonsters();

		// does this name exist?
		foreach($this->monsters as $m) {
			if($m->name==$this->name) {
				$this->found = true;
			}
		}
		
	}


	public function writeHttpHeader() {
		if (!$this->found) {
			header('HTTP/1.0 404 Not Found');
			return true;
		}

		if ($this->isExact && strpos($_REQUEST['name'], ' ') !== FALSE) {
			header('HTTP/1.0 301 Moved permanently.');
			header('Location: '.preg_replace('/[ +]/', '_', $_SERVER['PHP_SELF']));
			return false;
		}

		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Creature '.htmlspecialchars($this->name).STENDHAL_TITLE.'</title>'."\n";
		if (!$this->found) {
			echo '<meta name="robots" content="noindex">'."\n";
		}
	}

	function writeContent() {

if (!$this->found) {
	startBox("No such Creature");
	?>
	There is no such creature at Stendhal.<br>
	Please make sure you spelled it correctly.
	<?php
	endBox();
	return;
}

foreach($this->monsters as $m) {
  /*
   * If name of the creature match or contains part of the name.
   */
  if($m->name==$this->name || (!$this->isExact and strpos($m->name, $this->name) != false)) {
    startBox("Detailed information");
    ?>
    <div class="creature">
      <div class="name"><?php echo ucfirst($m->name); ?></div>
      <img class="creature" src="<?php echo $m->gfx; ?>" alt="">
      <div class="level">Level <?php echo $m->level; ?></div>
      <div class="xp">Killing it will give you <?php echo $m->xp; ?> XP.</div>
      <div class="respawn">Respawns on average in <?php echo printRespawn($m->respawn); ?> minutes.</div>
      <div class="description">
        <?php 
          if($m->description=="") {
            echo "No description. Would you like to write one?";
          } else {
            echo $m->description;
          }
        ?>
      </div>
      
      <div class="table">
        <div class="title">Attributes</div>
          <?php
          foreach($m->attributes as $label=>$data) {
            ?>
            <div class="row">
              <div class="label"><?php echo strtoupper($label); ?></div>
              <div class="data"><?php echo $data; ?></div>
            </div>
            <?php
          }
          ?>
        </div>      
      
      <div class="table">
        <div class="title">Creature drops</div>
          <?php
          foreach($m->drops as $k) {
          	?>
            <div class="row">
              <?php
                $item = getItem($k["name"]);
                echo '<a href="'.rewriteURL('/item/'.surlencode($item->class).'/'.surlencode($k["name"]).'.html').'">';
              ?>
              <img src="<?php echo $item->showImage(); ?>" alt="<?php echo ucfirst($k["name"]); ?>"/>
              <span class="block label"><?php echo ucfirst($k["name"]); ?></span>
              </a>
              <div class="data">Drops <?php echo renderAmount($k["quantity"]); ?></div>
              <div class="data">Probability: <?php echo $k["probability"]; ?>%</div>
            </div>
            <?php 
          }
          ?>
        </div>
      </div>

    <?php
    endBox();


    /*
     * Obtain data from database
     */
    $m->fillKillKilledData();   
    
    startBox(ucfirst($m->name)." killed by players, per day");
      $data='';
      foreach($m->kills as $day=>$amount) {
	$date = date('M-d', time() - $day * 86400);
	$data .= $date . '_' . $amount . ',';
      }
    ?>  
    <img style="padding: 4px; border: 1px solid black;" src="/bargraph.php?data=<?php echo $data; ?>"/>
    <?php
    endBox();

    startBox("Players killed by ".$m->name.", per day");
      $data='';
      foreach($m->killed as $day=>$amount) {
        $date = date('M-d', time() - $day * 86400);
	$data.= $date . '_' . $amount . ','; 
      }
    ?>  
    <img style="padding: 4px; border: 1px solid black;" src="/bargraph.php?data=<?php echo $data; ?>"/>
    <?php
    endBox();
    ?>
    <div style="margin-bottom: 48px;"></div>
    <?php
  }
}

	}
}
$page = new MonsterPage();
?>