<?php

class OnlinePage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Online Players'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

$players=getOnlinePlayers();
startBox('Online Players');

if(sizeof($players)==0) {
  echo 'There are no logged players';
}
echo '<div style="height: 700px;">';
foreach($players as $p) {
    echo '<div class="onlinePlayer">';
    echo '  <img src="'.rewriteURL('/images/outfit/'.urlencode($p->outfit).'.png').'" alt="">';
    echo '  <a href="'.rewriteURL('/character/'.urlencode($p->name).'.html').'">';
    echo '  <div class="name">'.htmlspecialchars(utf8_encode($p->name)).'</div>';
    echo ' </a>';
    echo '</div>';
}
echo '</div>';
endBox();
	}
}
$page = new OnlinePage();
?>