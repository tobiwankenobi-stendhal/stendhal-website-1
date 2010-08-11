<?php
class CreateAccountPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Create Account'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		startBox("Create Account");
?>

<form name="createaccount" action="">



<label for="name">Name: </label><input id="name" name="name" type="text" maxlength="20" onchange="key(this)" onkeyup="key(this)">
<div id="namewarn" class="warn"></div>

<label for="pw">Password: </label><input id="pw" name="name" type="text" maxlength="20">
<div id="pwwarn" class="warn"></div>

<label for="pr">Password Repeat: </label><input id="pr" name="name" type="text" maxlength="20">
<div id="prwarn" class="warn"></div>

<label for="email">E-Mail: </label><input id="email" name="name" type="text" maxlength="50">
<div id="emailwarn" class="warn"></div>

<input "name="submit" style="margin-top: 2em" type="submit" value="Create Account">
</form>
<script type="text/javascript">

<!-- TODO: check all fields -->
function validate() {
	//document.createcharacter.submit.disabled = (document.createcharacter.name.value.length < 4);
	if (document.createcharacter.name.value.length < 4) {
		document.getElementById("warn").innerHTML = "Name must be more than 4 letters.";
	} else {
		document.getElementById("warn").innerHTML = "";
	}
}

function key(field) {
	field.value = field.value.toLowerCase().replace(/[^a-z]/g,"");
	validate();
}
</script>
<?php
		endBox();
	}
}

$page = new CreateAccountPage();

?>