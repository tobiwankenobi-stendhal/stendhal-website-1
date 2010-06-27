<?php 

define('TOTAL_HOF_PLAYERS', 10);

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

	private $filter;
	private $detail;

	private $loginRequired = false;
	
	public function __construct() {
		$this->setupFilter();
		$this->setupDetail();
	}


	// ------------------------------------------------------------------------
	// ------------------------------------------------------------------------


	function writeHttpHeader() {
		if ($this->loginRequired) {
			header('Location: '.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&url=/world/hall-of-fame/'.urlencode($filter).'_'.urlencode($detail).'.html');;
			return false;
		}
		return true;
	}


	public function writeHtmlHeader() {
		echo '<title>Hall of Fame'.STENDHAL_TITLE.'</title>';
	}


	function writeContent() {
		$this->writeTabs();
		if ($this->detail == "overview") {
			$this->renderOverview();
		} else {
			$this->renderDetails($detail);
		}
		$this->closeTabs();
	}


	// ------------------------------------------------------------------------
	// ------------------------------------------------------------------------
	
	function setupFilter() {
		$this->filter = 'active';
		if (isset($_REQUEST['filter'])) {
			$this->filter = urlencode($_REQUEST['filter']);
		}
		if ($this->filter=="alltimes") {
			$this->filterWhere='';
		} else if ($this->filter=="active") {
			$this->filterWhere = ' AND character_stats.lastseen>date_sub(CURRENT_TIMESTAMP, interval 1 month)';
		} else if ($this->filter=="friends") {
			if (!isset($_SESSION['username'])) {
				$loginRequired = true;;
				return;
			}
			$this->filterFrom = ", (select characters.charname As charname from account, characters "
				. "WHERE username='".mysql_real_escape_string($_SESSION['username'])."' AND account.id=characters.player_id "
				. "UNION SELECT buddy.buddy FROM account, characters, buddy "
				. "WHERE username='".mysql_real_escape_string($_SESSION['username'])."' AND account.id=characters.player_id AND characters.charname=buddy.charname) As x ";
			$this->filterWhere = ' AND character_stats.name=x.charname';
		}
		// TODO: 404 on invalid filter variable
		return;
	}

	function setupDetail() {
		$this->detail = 'overview';
		if (isset($_REQUEST['detail'])) {
			$this->detail == urlencode($_REQUEST['detail']);
		}
		// TODO: 404 on invalid detail variable
	}


	// ------------------------------------------------------------------------
	// ------------------------------------------------------------------------

	function writeTabs() {
		if (isset($_REQUEST['dev'])) {
		?>
		<br>

<style type="text/css">
.activeTab {padding: 0.5em; background-color: #CFC; line-height: 0.95em; border-width: 2px 2px 0pt; border-style: solid solid none; border-color: rgb(163, 177, 191) rgb(163, 177, 191) -moz-use-text-color; font-weight: bold; white-space: nowrap;}
.activeTabA {color: #000; text-decoration: none; display: block}
.backgroundTab {padding: 0.5em; background-color: #7A7; font-size: 90%; line-height: 0.95em; border: 2px solid rgb(163, 177, 191); border-bottom: 2px solid #000; white-space: nowrap;}
.backgroundTabA {color: #FFF; text-decoration: none; display: block}
.backgroundTabA:hover {font-weight:bold; text-decoration: underline}
.barTab{border-bottom: 2px solid #000; }
.tabPageContent{background-color: #CFC; border-left: 2px solid #000; border-right: 2px solid #000; border-bottom: 2px solid #000;}
</style>
<table style="text-align: center;" width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
<td class="barTab" width="2%"> &nbsp;</td>
<?php echo '<td class="'.$this->getTabClass('active').'" width="25%"><a class="'.$this->getTabClass('active').'A" href="'.rewriteURL('/world/hall-of-fame/active_'.$this->detail.'.html').';">Active</a></td>';?>
<td class="barTab" width="2%"> &nbsp;</td>
<?php echo '<td class="'.$this->getTabClass('alltimes').'" width="25%"><a class="'.$this->getTabClass('alltimes').'A" href="'.rewriteURL('/world/hall-of-fame/alltimes_'.$this->detail.'.html').'">All times</a></td>';?>
<td class="barTab" width="2%">&nbsp;</td>
<?php echo '<td class="'.$this->getTabClass('friends').'" width="25%"><a class="'.$this->getTabClass('friends').'A" href="'.rewriteURL('/world/hall-of-fame/friends_'.$this->detail.'.html').'">Me &amp; my friends</a></td>';?>
<td class="barTab"> &nbsp;</td>
</tr>
<tr><td colspan="7" class="tabPageContent">

<br>

		<?php
		}
	}


	function closeTabs() {
		if (isset($_REQUEST['dev'])) {
			?></td></tr></table><?php 
		}
	}

	function getTabClass($tab) {
		if ($this->filter == $tab) {
			return 'activeTab';
		} else {
			return 'backgroundTab';
		}
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
					<?php $var=$f($player); ?>
					<span class="block data"><?php echo $var.$postfix; ?></span>
				</a>
				<div style="clear: left;"></div>
			</div>
	
			<?php
			$i++;
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
		$choosen = getBestPlayer($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere);
		?>
		<div class="bubble">The best player is decided based on the relation between XP and age, so the best players are those the spend most time earning XP instead of being idle around in game.</div>    
		<div class="best">
			<a href="<?php echo rewriteURL('/character/'.surlencode($choosen->name).'.html'); ?>">
				<span class="block statslabel">Name:</span><span class="block data"><?php echo htmlspecialchars(utf8_encode($choosen->name)); ?></span>
				<span class="block statslabel">Age:</span><span class="block data"><?php echo getAge($choosen); ?> hours</span>
				<span class="block statslabel">Level:</span><span class="block data"><?php echo $choosen->level; ?></span>
				<span class="block statslabel">XP:</span><span class="block data"><?php echo $choosen->xp; ?></span>
			</a>
		</div>
		<a href="<?php echo rewriteURL('/character/'.surlencode($choosen->name).'.html'); ?>">
		<img class="bordered_image" src="<?php echo rewriteURL('/images/outfit/'.surlencode($choosen->outfit).'.png')?>" alt="">
		</a>
		<?php if ($choosen->sentence != '') {
			echo '<div class="sentence">'.htmlspecialchars(utf8_encode($choosen->sentence)).'</div>';
		}?>
		<?php endBox(); ?>

		<div style="float: left; width: 34%">
			<?php startBox("Strongest players"); ?>
			<div class="bubble">Based on XP and Karma</div>
			<?php
			$players = getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'xp DESC, karma DESC', 'limit '.TOTAL_HOF_PLAYERS);
			$this->renderListOfPlayers($players, 'getXP', " xp");
			##echo '<a href="'.rewriteURL('/world/hall-of-fame-strongest.html').'">More</a>';
			endBox();
			?>
		</div>

		<div style="float: left; width: 33%">
			<?php startBox("Richest players"); ?>
			<div class="bubble">Based on the amount of money</div>
			<?php
			$players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'money desc', 'limit '.TOTAL_HOF_PLAYERS);
			$this->renderListOfPlayers($players, 'getWealth', ' coins');
			endBox();
			?>
		</div>

		<div style="float: left; width: 33%">
			<?php startBox("Eldest players"); ?>
			<div class="bubble">Based on the age in hours</div>
			<?php
			$players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'age desc', 'limit '.TOTAL_HOF_PLAYERS);
			$this->renderListOfPlayers($players, 'getAge', ' hours');
			endBox();
			?>
		</div>

		<div style="float: left; width: 33%">
			<?php startBox("Deathmatch heroes"); ?>
			<div class="bubble">Based on the deathmatch score</div>
			<?php
			$players=getDMHeroes($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere.' and', 'limit '.TOTAL_HOF_PLAYERS);
			$this->renderListOfPlayers($players, 'getDMScore',' points');
			endBox();
			?>
		</div>

		<div style="float: left; width: 33%">
			<?php startBox("Best attackers"); ?>
			<div class="bubble">Based on atk*(1+0.03*level)</div>
			<?php
			$players= getPlayers($this->filterFrom.REMOVE_ADMINS_AND_POSTMAN.$this->filterWhere, 'atk*(1+0.03*level) desc', 'limit '.TOTAL_HOF_PLAYERS);
			$this->renderListOfPlayers($players, 'getTotalAtk', " total atk");
			endBox();
			?>
		</div>

		<div style="float: left; width: 33%">
			<?php startBox("Best defenders"); ?>
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