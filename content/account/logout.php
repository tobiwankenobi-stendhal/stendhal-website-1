<?php

require_once('scripts/account.php');

class LogoutPage extends Page {

	public function writeHttpHeader() {
		/**
		 * Delete cookies - the time must be in the past,
		 * so just negate what you added when creating the
		 * cookie.
		 */
		if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
			setcookie("cookname", "", time()-60*60*24*100, "/");
			setcookie("cookpass", "", time()-60*60*24*100, "/");
		}

		// Kill session variables
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		$_SESSION = array(); // reset session array
		session_destroy();   // destroy session.
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Logout'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<meta http-equiv="Refresh" content="0;url=/">';
	}

	function writeContent() {
		startBox("Logging Out");
		echo 'You have successfully <b>logged out</b>.<p>Back to <a href="/">start page</a>';
		endBox();
	}
}
$page = new LogoutPage();
?>
