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
    echo '  <a href="'.rewriteURL('/character/'.urlencode($p->name).'.html').'">';
    echo '  <img src="'.rewriteURL('/images/outfit/'.urlencode($p->outfit).'.png').'" alt="">';
    echo '  <span class="name">'.htmlspecialchars(utf8_encode($p->name)).'</span>';
    echo ' </a>';
    echo '</div>';
}
echo '</div>';
endBox();
	}
}
$page = new OnlinePage();
?>