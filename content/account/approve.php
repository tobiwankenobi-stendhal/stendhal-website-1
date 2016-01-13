<?php

function createRandomPassword() {
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	$i = 0;
	$pass = '' ;
	$amount=strlen($chars);

	while ($i <= 7) {
		$num = rand() % $amount;
		$pass = $pass . $chars[$num];
		$i++;
	}

	return $pass;
}

class ApprovePage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Approve Password Reset'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {

		if(!isset($_GET["sign"])) {
			die('You didn\'t fill in a required field.');
		}

		$signature=$_GET["sign"];

		// Get the user name from the username<->hash relation
		$sql='select username from remind_password where confirmhash=:confirmhash';
		$stmt = DB::web()->prepare($sql);
		$stmt->execute(array(':confirmhash' => $signature));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$username=$row["username"];

			// Remove the entry or anything 48 hours old.
			$sql = "delete from remind_password where username = :username or datediff(now(),requested)>2";
			$stmt = DB::web()->prepare($sql);
			$stmt->execute(array(':username' => $username));

			// Create a random password for it and set it.
			$newpassword=createRandomPassword();
			$hash = Account::sha512crypt(strtoupper(md5($newpassword)));
			$sql = "update account set password=:password where username = :username";
			$stmt = DB::game()->prepare($sql);
			$stmt->execute(array(
				':password' => $hash,
				':username' => $username
			));

			// Show user the new password.
			startBox("New password generated");
			?>
			Per your request we have reset the password of your account "<b><?php echo $username; ?></b>".<br>
			Your new password is "<b><?php echo $newpassword; ?></b>".
			<p>Store it on a secure place.
			<?php
			endBox();
		} else {
			startBox("No such username");
			?>
			We are unable to find a valid username associated to that email account.
			<p>Your password can not be reset.
			<p>Back to <a href="/">Main</a>
			<?php
			endBox();
		}						
	}
}

$page = new ApprovePage();
