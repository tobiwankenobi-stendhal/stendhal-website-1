<?php
class AdminEMailPage extends Page {
	function writeContent() {

		if(getAdminLevel() < 400) {
			die("Ooops!");
		}

		if (isset($_POST['body'])) {
			$this->sendMail();
		} else {
			$this->renderForm();
		}
	}

	function renderForm() {
		startBox("Email");
		?>
<form method="POST">
<table>
<tr><td><label for="subject">Subject:</label></td><td><input name="subject" value="<?php echo htmlspecialchars($_REQUEST['subject']); ?>"></td></tr>
<tr><td><label for="to">To:</label></td><td><input name="to" value="<?php echo htmlspecialchars($_REQUEST['to']); ?>"></td></tr>
<!-- <tr><td><label for"cc">Cc:</label></td><td><input name="cc"></td></tr>  -->
</table>
<textarea name="body" cols="70" rows="20"><?php echo htmlspecialchars($_REQUEST['body']); ?></textarea>
<input type="submit">
</form>
		<?php
		endBox();
	}

	function sendMail() {
		startBox("Email");
		$res = mail($_REQUEST['to'], $_REQUEST['subject'], $_REQUEST['body'], 
			'Cc: '.STENDHAL_GM_EMAIL."\r\nFrom: ".STENDHAL_GM_EMAIL,
			'-f '.STENDHAL_GM_ADDRESS);
		if ($res) {
			echo 'Mail sent.';
		} else {
			echo 'Failed.';
		}
		endBox();
	}
}
$page = new AdminEMailPage();