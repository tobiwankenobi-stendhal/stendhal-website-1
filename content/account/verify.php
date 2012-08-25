<?php

/**
 * verifies the email token
 */
class VerifyPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>E-Mail verification'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		startBox('E-Mail verification');
		if (!isset($_REQUEST['token'])) {
			echo '<p>Error: The token is missing.</p>';
		} else {
			$res = Account::verifyEMail($_REQUEST['token']);
			if ($res) {
				echo '<p>Your email address was confirmed successfully.</p>';
			} else {
				echo '<p>Error: The token is invalid.</p>';
			}
		}
		endBox();
	}
}

$page = new VerifyPage();
