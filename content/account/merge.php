<?php
require_once('scripts/account.php');

class AccountMerge extends Page {

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Account Merging'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if (!isset($_SESSION['username'])) {
			startBox("Account Merging");
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url=/index.php%3Fid=content/account/merge">login</a> first to merge accounts.</p>';
			endBox();
		} else {
			$this->process();
		}
	}

	function process() {
		// TODO
	}
}
$page = new AccountMerge();
?>
