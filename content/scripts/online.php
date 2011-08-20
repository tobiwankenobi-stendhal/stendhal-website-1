<?php

class OnlinePage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Online Players'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

		$players=getOnlinePlayers();
		startBox('Online Players');

		if(sizeof($players)==0) {
			echo 'There are no players logged in at the moment.';
		}
		echo '<div style="height: '.((floor(count($players) / 7) + 1) * 80 + 10) .'px">';
		foreach($players as $p) {
			echo '<div class="onlinePlayer onlinePlayerHeight">';
			echo '  <a class = "onlineLink" href="'.rewriteURL('/character/'.surlencode($p->name).'.html').'">';
			echo '  <img src="'.rewriteURL('/images/outfit/'.surlencode($p->outfit).'.png').'" alt="" width="48" height="64">';
			echo '  <span class="block onlinename">'.htmlspecialchars($p->name).'</span></a>';
			echo '</div>';
		}
		echo '</div>';

		endBox();
	}
}
$page = new OnlinePage();
?>