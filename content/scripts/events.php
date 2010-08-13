<?php

class EventsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Recent Events'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

$events=array_merge(getKillEvents(),getQuestEvents(),getLevelEvents(),getSignEvents(),getPoisonEvents(),getChangeZoneEvents());
$outfitevents=getOutfitEvents();

function cmp($a, $b)
{
    if ($a->timedate == $b->timedate) {
        return 0;
    }
    return ($a->timedate > $b->timedate) ? 1 : -1;
}

usort($events,"cmp");

startBox('Recent Events');

if(sizeof($events)+sizeof($outfitevents)==0) {
  echo 'There are no recent events to report on';
}
echo '<div>';

foreach($events as $e) {
    echo $e->getHtml();
}
foreach($outfitevents as $o) {
	echo '<p><a href="'.rewriteURL('/character/'.surlencode($o->source).'.html').'">'.htmlspecialchars($o->source).'</a> changed outfit at '.htmlspecialchars($o->timedate); 
}

echo '</div>';
endBox();
	}
}
$page = new EventsPage();
?>