<?php

require_once('scripts/account.php');

function validateParameters() {
	$username = $_SESSION['username'];

	if(!checkLogin()){
		return "You need to login first";
	}
	/* Check that all fields were typed in */
	if(!$_SESSION['username'] || !$_POST['pass']){
		return 'You didn\'t fill in a required field.';
	}

	/* Checks that username is in database and password is correct */
	$md5pass = strtoupper(md5($_POST['pass']));
	$result = confirmUser($username, $md5pass);

	if ($result === 2) {
		/* We need to check the pre-Marauroa 2.0 passwords */
		$md5pass = strtoupper(md5(md5($_POST['pass'],true)));
		$result = confirmUser($username, $md5pass);
	}

	/* Check error codes */
	if($result != 0){
		logUserPasswordChange($username, $_SERVER['REMOTE_ADDR'], '', 0);
		return 'Incorrect password, please try again.';
	}

	if($_POST['newpass']!=$_POST['newpass_retype']) {
		return 'Password incorrectly typed.';
	}

	if(strlen($_POST['newpass']) < 6) {
		return 'The password needs to be at least 6 characters long.';
	}
	
	return "";
}

function changePassword() {
	$username = $_SESSION['username'];
	
	/* Verify that user is in database */
	$md5newpass = strtoupper(md5($_POST['newpass']));
	$q = "update account set password='".mysql_real_escape_string($md5newpass)."' where username = '".mysql_real_escape_string($username)."'";
	$result = mysql_query($q,getGameDB());
	
	if(mysql_affected_rows()!=1) {
		die('Problem updating database');
	}

	/* Here we log the pw change, with user id, IP and hash of the old pass */
	logUserPasswordChange($username, $_SERVER['REMOTE_ADDR'], $md5pass, 1);

	/* Username and password correct, register session variables */
	$_POST['user'] = $username;
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $md5newpass;

	echo "<meta http-equiv=\"Refresh\" content=\"5;url=?\">";
	startBox("Password Change");
		echo '<h1>Your password has been changed successfully.</h1> <h4>Remember to update and re-save any login profile you may have stored.</h4> Moving to main page.';
	endBox();
}

function handleValidationError($error) {
	startBox("Password Change Failed");
		echo '<p>'.htmlspecialchars($error).'</p>';
	endBox();
}


class ChangePasswordPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Change Password'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {

/**
 * Checks to see if the user has submitted his
 * username and password through the login form,
 * if so, checks authenticity in database and
 * creates session.
 */
if(isset($_POST['sublogin'])){

	$error = validateParameters();
	if ($error == '') {
		changePassword();
	} else {
		handleValidationError($error);
	}
	
} else {
startBox("Change password");
?>

<form action="" method="post">
<table>
  <tr><td>Old Password:</td><td><input type="password" name="pass" maxlength="30"></td></tr>
  <tr><td>New Password:</td><td><input type="password" name="newpass" maxlength="30"></td></tr>
  <tr><td>Retype new Password:</td><td><input type="password" name="newpass_retype" maxlength="30"></td></tr>
  <tr><td colspan="2" align="right"><input type="submit" name="sublogin" value="Change Password"></td></tr>
</table>
</form>

<?php
endBox();

}
	}
}
$page = new ChangePasswordPage();
?>
