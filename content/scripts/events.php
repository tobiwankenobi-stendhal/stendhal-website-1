<?php

class EventsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Recent Events'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		global $cache;
		$events = $cache->fetchAsArray('stendhal_events');
		if (!isset($events)) {
			$events=array_merge(getKillEvents(),
					getQuestEvents(),
					getLevelEvents(),
					getSignEvents(),
					getPoisonEvents(),
					getChangeZoneEvents(),
					getOutfitEvents(),
					getEquipEvents());
			$cache->store('stendhal_events', new ArrayObject($events), 60);				
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

foreach($events as $e) {
    echo $e->getHtml();
}

echo '</div>';
endBox();
	}
}
$page = new EventsPage();
?>