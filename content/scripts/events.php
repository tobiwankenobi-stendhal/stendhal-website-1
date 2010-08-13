<?php

class EventsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Recent Events'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

$killevents=getKillEvents();
$outfitevents=getOutfitEvents();
$questevents=getQuestEvents();
$levelevents=getLevelEvents();
$signevents=getSignEvents();
startBox('Recent Events');

if(sizeof($killevents)+sizeof($outfitevents)+sizeof($questevents)+sizeof($levelevents)+sizeof($levelevents)==0) {
  echo 'There are no recent events to report on';
}
echo '<div>';
foreach($killevents as $k) {
    echo $k->getHtml();
}
foreach($outfitevents as $o) {
	echo '<p><a href="'.rewriteURL('/character/'.surlencode($o->source).'.html').'">'.htmlspecialchars($o->source).'</a> changed their outfit at '.htmlspecialchars($o->timedate); 
}
foreach($questevents as $q) {
	echo $q->getHtml(); 
}	
foreach($levelevents as $l) {
	echo $l->getHtml(); 
}
foreach($signevents as $s) {
	echo $s->getHtml(); 
}
echo '</div>';
endBox();
	}
}
$page = new EventsPage();
?>