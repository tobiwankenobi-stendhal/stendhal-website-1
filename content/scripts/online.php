<?php

class OnlinePage extends Page {
	private $isOnline = true;

	public function __construct() {
		$stats = getServerStats();
		$this->isOnline = $stats->isOnline();
	}

	public function writeHtmlHeader() {
		echo '<title>Online Players'.STENDHAL_TITLE.'</title>';
		if (!$this->isOnline) {
			echo '<meta name="robots" content="noindex">'."\n";
		}
	}

	/**
	 * writes the page content.
	 */
	function writeContent() {
		if ($this->isOnline) {
			$this->writeOnlinePlayers();
		} else {
			$this->writeOfflineHint();
		}
	}

	/**
	 * writes an informative text about the server being offline.
	 */
	function writeOfflineHint() {
		startBox("Server is offline");
		echo '<p>We are sorry! The server is offline right now.</p>';
		echo '<p>This may be the desired behaviour, in case of an update, or it may be the result of some kind of problem.</p>';
		echo '<p>Please join our IRC channel <a href="http://webchat.freenode.net/?channels='.substr(MAIN_CHANNEL, 1).'">'.MAIN_CHANNEL.'</a> ';
		echo 'for updates on the situation.</p>';
		endBox();
	}

	/**
	 * writes the list of online players.
	 */
	function writeOnlinePlayers() {
		$players=getOnlinePlayers();
		startBox('Online Players');
		
		if(sizeof($players)==0) {
			echo 'There are no players logged in at the moment.';
		}
		echo '<div style="height: '.(ceil(count($players) / 7) * 80 + 10) .'px">';
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