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
			echo '<p>Please <a href="'.STENDHAL_LOGIN_TARGET.'/index.php?id=content/account/login&amp;url=/account/merge.html">login</a> first to merge accounts.</p>';
			endBox();
		} else {
			$this->process();
		}
	}

	function process() {
		$this->displayHelp();
		$this->processMerge();
		$this->displayForm();
	}

	function displayHelp() {
		startBox("Account Merging");?>
		<p>With the form below you can merge your other accounts. &nbsp;&nbsp;&ndash;&nbsp;&nbsp;
		(<a href="https://stendhalgame.org/wiki/Stendhal_Account_Merging">Help</a>)</p>
		<p>This means that all characters previously associated with the other 
		account will be available in this account. The other account will be 
		disabled.</p>
		<p class="warn">Merging accounts cannot be undone.</p>
		<?php endBox();
	}

	function processMerge() {
		if (! isset($_POST['submerge'])) {
			return;
		}

		startBox("Result");
		if ($_SESSION['username'] == trim($_POST['user'])) {
			echo '<p class="error">You need to enter the username and password of another account you own.</p>';
			endBox();
			return;
		}

		if (!isset($_POST['confirm'])) {
			echo '<p class="error">You need to tick the confirm-checkbox.</p>';
			endBox();
			return;
		}

		$checkResult = checkAccount($_POST['user'], $_POST['pass']);
		if ($checkResult == 3) {
			echo '<p class="error">The account is temporary blocked or banned.</p>';
			endBox();
			return;
		}
		
		if ($checkResult == 4) {
			echo '<p class="error">The account was already merged and does not exist anymore.</p>';
			endBox();
			return;
		}

		if ($checkResult != 0) {
			echo '<p class="error">Username or password wrong.</p>';
			endBox();
			return;
		}
		
		mergeAccount($_POST['user'], $_SESSION['username']);
		echo '<p class="success">Your old account <i>'.htmlspecialchars($_POST['user'])
			.'</i> was integrated into your account <i>'.htmlspecialchars($_SESSION['username']).'</i>.</p>';
		endBox();
	}

	function displayForm() {
		startBox("Account to merge in"); ?>
		<p>You are currently logged into the account <b><?php echo htmlspecialchars($_SESSION['username']) ?></b>.</p>

		<form action="" method="post">
			<table>
				<tr><td><label for="user">Username:</label></td><td><input type="text" id="user" name="user" maxlength="30"></td></tr>
				<tr><td><label for="pass">Password:</label></td><td><input type="password" id="pass" name="pass" maxlength="30"></td></tr>
				<tr><td colspan="2" align="left"><input type="checkbox" id="confirm" name="confirm">
				<label for="confirm">I really want to merge these accounts.</label></td></tr>
				<tr><td colspan="2" align="right"><input type="submit" name="submerge" value="Merge"></td></tr>
			</table>
		</form>

		<?php endBox();
		
	}
}
$page = new AccountMerge();
?>
