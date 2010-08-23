<?php

class EventsPage extends Page {
	
	public function __construct() {
		$this->setupFilter();
	}
	
	function writeHttpHeader() {
		if ($this->filter=="friends" && !isset($_SESSION['username'])) {
			header('Location: '.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&url='.urlencode(rewriteURL('/world/events/'.urlencode($this->filter).'.html')));
			return false;
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Recent Events'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		// $this->writeTabs();
		$this->printRecentEvents();
		// $this->closeTabs();
	}
	
	function writeTabs() {
        ?>
        <br>
        <table width="100%" border="0" cellpadding="0" cellspacing="0"><tr>
        <td class="barTab" width="2%"> &nbsp;</td>
        <?php echo '<td class="'.$this->getTabClass('all').'" width="25%"><a class="'.$this->getTabClass('all').'A" href="'.htmlspecialchars(rewriteURL('/world/events/all.html')).'">All</a></td>';?>
        <td class="barTab" width="2%"> &nbsp;</td>
        <?php echo '<td class="'.$this->getTabClass('friends').'" width="25%"><a class="'.$this->getTabClass('friends').'A" href="'.htmlspecialchars(rewriteURL('/world/events/friends.html')).'">Friends</a></td>';?>
        <td class="barTab">&nbsp;</td>
        </tr>
        <tr><td colspan="7" class="tabPageContent">
        <br>
        <?php
    }


    function closeTabs() {
        ?></td></tr></table><?php 
    }

    function getTabClass($tab) {
        if ($this->filter == $tab) {
            return 'activeTab';
        } else {
            return 'backgroundTab';
        }
    }
	
	function setupFilter() {
		$this->filter = 'all';
		if (isset($_REQUEST['filter'])) {
			$this->filter = urlencode($_REQUEST['filter']);
		}
 	}
	
	function printRecentEvents(){
		global $cache;
		$events = $cache->fetchAsArray('stendhal_events_'.$this->filter);
		if (!isset($events)) {
			$events=array_merge(getKillEvents($this->filter),
				getQuestEvents($this->filter),
				getLevelEvents($this->filter),
				getSignEvents($this->filter),
				getPoisonEvents($this->filter),
				getChangeZoneEvents($this->filter),
				getOutfitEvents($this->filter),
				getEquipEvents($this->filter));
			$cache->store('stendhal_events_'.$this->filter, new ArrayObject($events), 60);				
		}
		
		
		function cmp($a, $b)
			{
			if ($a->timedate == $b->timedate) {
				return 0;
			}
			return ($a->timedate < $b->timedate) ? 1 : -1;
		}
		
		usort($events,"cmp");
		
		startBox('A selection of recent events');
		
		if(sizeof($events)==0) {
			echo 'There are no recent events to report on.';
		}
		echo '<div>';
		$players = array();
		foreach($events as $e) {
			$e->addPlayersToList($players);
		}
		
		$outfits = getOutfitsForPlayers($players);
		
		foreach($events as $e) {
			echo $e->getHtml($outfits);
		}
		
		echo '</div>';
		endBox();
	}
}

$page = new EventsPage();
?>