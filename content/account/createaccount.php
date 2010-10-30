<?php
class CreateAccountPage extends Page {

	/**
	 * this method can write additional http headers, for example for cache control.
	 *
	 * @return true, to continue the rendering, false to not render the normal content
	 */
	public function writeHttpHeader() {
		if (strpos(STENDHAL_LOGIN_TARGET, 'https://') !== false) {
			if (!isset($_SERVER['HTTPS']) || ($_SERVER['HTTPS'] != "on")) {
				header('Location: '.STENDHAL_LOGIN_TARGET.rewriteURL('/account/create-account.html'));
				return false;
			}
		}
		if (isset($_SESSION['account'])) {
			header('Location: '.STENDHAL_LOGIN_TARGET.rewriteURL('/account/mycharacters.html'));
			return false;
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Create Account'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<style type="text/css">label {width: 9em; display: inline-block} .warn {margin-left: 9em; height: 1em}</style>';
	}

	function writeContent() {
		$this->show();
	}

	function show() {
		$_SESSION['csrf'] = createRandomString();
		startBox("Create Account");
?>

<form name="createaccount" action="" onsubmit="return checkForm()">
<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf'])?>">

<label for="name">Name:<sup>*</sup> </label><input id="name" name="name" type="text" maxlength="20" onchange="nameChanged(this)" onkeyup="nameChanged(this)" onblur="validateMinLengthFail(this)">
<div id="namewarn" class="warn"></div>

<label for="pw">Password:<sup>*</sup> </label><input id="pw" name="name" type="password" onchange="validateMinLengthOk(this)" onkeyup="validateMinLengthOk(this)" onblur="validateMinLengthFail(this)">
<div id="pwwarn" class="warn"></div>

<label for="pr">Password Repeat:<sup>*</sup> </label><input id="pr" name="name" type="password">
<div id="prwarn" class="warn"></div>

<label for="email">E-Mail: </label><input id="email" name="name" type="text" maxlength="50">
<div id="emailwarn" class="warn"></div>

<input "name="submit" style="margin-top: 2em" type="submit" value="Create Account">
</form>
<?php endBox(); ?>
<br><br>
<?php startBox("Logging and privacy");?>
<p>
<font size="-1">On login information which identifies your computer on the internet will be 
logged to prevent abuse (like many attempts to guess a password in order to
hack an account or creation of many accounts to cause trouble).</font></p>

<p><font size="-1">
Furthermore all events and actions that happen within the game-world 
(like solving quests, attacking monsters) are logged. This information is 
used to analyse bugs and in rare cases for abuse handling.</font></p>
<?php endBox();?>
<script type="text/javascript">

function validateMinLengthFail(field) {
	if (field.value.length < 6) {
		document.getElementById(field.id + "warn").innerHTML = "Must be at least 6 letters long.";
	}
}

function validateMinLengthOk(field) {
	if (field.value.length >= 6) {
		document.getElementById(field.id + "warn").innerHTML = "";
	}
}

function nameChanged(field) {
	field.value = field.value.toLowerCase().replace(/[^a-z]/g,"");
	validateMinLengthOk(field);
}

function checkForm() {
	var name = document.getElementById("name");
	if (name.value.length < 6) {
		name.focus();
		alert("Your account name needs to be at least 6 letters long.");
		return false;
	}

	var pw = document.getElementById("pw");
	if (pw.value.length < 6) {
		pw.focus();
		alert("Your password needs to be at least 6 letters long.");
		return false;
	}

	var pr = document.getElementById("pr");
	if (pw.value != pr.value) {
		pw.focus();
		alert("Your password and repetition do not match.");
		return false;
	}

	return true;
}
</script>
<?php
	}
}

$page = new CreateAccountPage();

?>