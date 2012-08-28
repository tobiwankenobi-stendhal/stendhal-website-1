<?php
class VerifyPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Verify email address'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		// If the token parameter is missing, something went wrong
		if(!isset($_REQUEST["token"])) {
			startBox("Error");
			echo 'The address is incomplete. Please check that the your mail client did not wrap parts of the address into the next line.';
			endBox();
			return;
		}

		$token = $_REQUEST["token"];
		
		// Does the token exist?
		$query = "SELECT address FROM email WHERE token='".mysql_real_escape_string($token)."'";
		$result = mysql_query($query, getGameDB());
		if(mysql_numrows($result) < 1) {
			startBox("Error");
			echo 'The security token is invalid. Please check that the your mail client did not wrap parts of the address into the next line.';
			endBox();
			return;
		}

		// Was the token already used successfully?
		$row = mysql_fetch_assoc($result);
		if (isset($row['confirmed'])) {
			startBox("Already confirmed.");
			echo 'The email address has already been confirmed successfully.';
			endBox();
			return;
		}

		// Update
		$query = "UPDATE email SET address='".mysql_real_escape_string($_SERVER['REMOTE_ADDR'])
				."', confirmed=NOW() where token = '".mysql_real_escape_string($token)."'";
		mysql_query($query, getGameDB());

		// Show success
		startBox("Confirmed");
		echo 'Your email address was confirmed successfully.';
		endBox();
	}
}

$page = new VerifyPage();
