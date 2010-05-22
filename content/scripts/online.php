<?php

class OnlinePage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Online Players'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

$players=getOnlinePlayers();
startBox('Online Players');

if(sizeof($players)==0) {
  echo 'There are no players logged in';
}
echo '<div style="height: 700px;">';
foreach($players as $p) {
    echo '<div class="onlinePlayer">';
    echo '  <a href="'.rewriteURL('/character/'.surlencode($p->name).'.html').'">';
    echo '  <img src="'.rewriteURL('/images/outfit/'.surlencode($p->outfit).'.png').'" alt=""></a>';
    echo '  <div class="onlinename"><a href="'.rewriteURL('/character/'.surlencode($p->name).'.html').'">';
    echo htmlspecialchars(utf8_encode($p->name)).'</a></div>';
    echo '</div>';
}
echo '</div>';
endBox();
	}
}
$page = new OnlinePage();
?>