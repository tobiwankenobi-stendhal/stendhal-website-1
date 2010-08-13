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
	echo '<p><a href="'.rewriteURL('/character/'.surlencode($q->source).'.html').'">'.htmlspecialchars($q->source).'</a> completed the '.htmlspecialchars(ucfirst(str_replace('_',' ',$q->quest))).' quest at '.htmlspecialchars($q->timedate); 
}	
foreach($levelevents as $l) {
	echo '<p><a href="'.rewriteURL('/character/'.surlencode($l->source).'.html').'">'.htmlspecialchars($l->source).'</a> changed level to '.htmlspecialchars($l->level).' at '.htmlspecialchars($l->timedate); 
}
foreach($signevents as $s) {
	echo '<p><a href="'.rewriteURL('/character/'.surlencode($s->source).'.html').'">'.htmlspecialchars($l->source).'</a> rented a sign saying: "'.htmlspecialchars($s->text).'" at '.htmlspecialchars($s->timedate); 
}
echo '</div>';
endBox();
	}
}
$page = new EventsPage();
?>