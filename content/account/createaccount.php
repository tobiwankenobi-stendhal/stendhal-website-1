<?php
class CreateAccountPage extends Page {
	private $result;
	private $error;

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

		return $this->process();
	}

	function process() {
		global $protocol;
		if (!$_POST) {
			return true;
		}
		if (!$_POST['name'] || !$_POST['pw'] || !$_POST['pr']) {
			$this->error = 'One of the mandatory fields was empty.';
			return true;
		}

		if ($_POST['csrf'] != $_SESSION['csrf']) {
			$this->error = 'Session information was lost.';
			return true;
		}

		if ($_POST['pw'] != $_POST['pr']) {
			$this->error = 'Your password and repetition do not match.';
			return true;
		}

		require_once('scripts/pharauroa/pharauroa.php');
		$clientFramework = new PharauroaClientFramework(STENDHAL_MARAUROA_SERVER, STENDHAL_MARAUROA_PORT, STENDHAL_MARAUROA_CREDENTIALS);
		$template = new PharauroaRPObject();
		$template->put('outfit', $_REQUEST['outfitcode']);

		$this->result = $clientFramework->createAccount($_POST['name'], $_POST['pw'], $_POST['email']);

		if ($this->result->wasSuccessful()) {
			// redirect to my characters page
			header('HTTP/1.0 301 Moved permanently.');
			header("Location: ".$protocol."://".$_SERVER['HTTP_HOST'].preg_replace("/&amp;/", "&", rewriteURL('/account/mycharacters.html')));
			return false;
		} else {
			return true;
		}
	}

	public function writeHtmlHeader() {
		echo '<title>Create Account'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<script src="/css/jquery-00000001.js" type="text/javascript"></script>';
	}

	function writeContent() {
		$this->show();
	}

	function show() {
		if ($this->error || (isset($this->result) && !$this->result->wasSuccessful())) {
			startBox("Error");
			if ($this->error) {
				echo '<span class="error">'.htmlspecialchars($this->error).'</span>';
			} else {
				echo '<span class="error">'.htmlspecialchars($this->result->getMessage()).'</span>';
			}
			endBox();
		}

		$_SESSION['csrf'] = createRandomString();
		startBox("Create Account");
?>

<form name="createaccount" action="" method="post" onsubmit="return checkForm()">
<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf'])?>">

<table>
<tr>
<td><label for="name">Name:<sup>*</sup> </label></td>
<td><input id="name" name="name" type="text" maxlength="20" onchange="nameChanged(this)" onkeyup="nameChanged(this)" onblur="blurName(this)"></td>
<td><div id="namewarn" class="warn"></div></td>
</tr>

<tr>
<td><label for="pw">Password:<sup>*</sup> </label></td>
<td><input id="pw" name="pw" type="password" onchange="validateMinLengthOk(this)" onkeyup="validateMinLengthOk(this)" onblur="validateMinLengthFail(this)"></td>
<td><div id="pwwarn" class="warn"></div></td>
</tr>

<tr>
<td><label for="pr">Password Repeat:<sup>*</sup> </label></td>
<td><input id="pr" name="pr" type="password"></td>
<td><div id="prwarn" class="warn"></div></td>
</tr>

<tr>
<td><label for="email">E-Mail: </label></td>
<td><input id="email" name="email" type="text" maxlength="50"></td>
<td><div id="emailwarn" class="warn"></div></td>
</tr>
</table>

<input name="submit" style="margin-top: 2em" type="submit" value="Create Account">
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
function validateMinLength(field) {
	if (field.value.length >= 6) {
		document.getElementById(field.id + "warn").innerHTML = "";
		minLengthOnceReached = true;
		return true;
	} else {
		if (minLengthOnceReached) {
			document.getElementById(field.id + "warn").innerHTML = "Must be at least 6 letters long.";
		}
	}
	return false;
}

function validateMinLengthFail(field) {
	if (field.value.length < 6) {
		document.getElementById(field.id + "warn").innerHTML = "Must be at least 6 letters long.";
	}
}

function validateMinLengthOk(field) {
	if (field.value.length >= 6) {
		document.getElementById(field.id + "warn").innerHTML = "";
		return true;
	}
	return false;
}

var lastRequestedName = "";
var minLengthOnceReached = false;
function nameChanged(field) {
	field.value = field.value.toLowerCase().replace(/[^a-z]/g,"");
	if (lastRequestedName != field.value) {
		lastRequestedName = field.value;
		var res = validateMinLength(field);
		if (res) {
			$.getJSON('/index.php?id=content/scripts/api&method=isNameAvailable&param=' + escape(lastRequestedName), function(data) {
				if (lastRequestedName == data.name) {
					if (data.result) {
						document.getElementById(field.id + "warn").innerHTML = "";
					} else {
						document.getElementById(field.id + "warn").innerHTML = "This name is not available.";
					}
				}
			});
		}
	}
}

function blurName(field) {
	validateMinLengthFail(field);
	nameChanged(field);
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