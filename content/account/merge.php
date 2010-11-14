<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2010 The Arianne Project

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once('scripts/account.php');
require_once('content/account/openid.php');

class AccountMerge extends Page {
	private $error;
	private $openid;

	public function writeHttpHeader() {
		// force SSL if supported
		if (strpos(STENDHAL_LOGIN_TARGET, 'https://') !== false) {
			if (!isset($_SERVER['HTTPS']) || ($_SERVER['HTTPS'] != "on")) {
				header('Location: '.STENDHAL_LOGIN_TARGET.rewriteURL('/account/merge.html'));
				return false;
			}
		}

		// redirect to openid provider?
		$this->openid = new OpenID();
		$this->openid->doOpenidRedirectIfRequired();
		if ($this->openid->isAuth && !$this->openid->error) {
			return false;
		}

		return true;
	}

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Account Merging'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if (!isset($_SESSION['account'])) {
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
		account will be available in this account.
		<?php
		if ($_SESSION['account']->password) {
			echo 'The other account will be disabled.';
		}?>
		</p>
		<p class="warn">Merging accounts cannot be undone.</p>
		<?php endBox();
	}

	function processMerge() {
		if (! isset($_POST['submerge'])) {
			return;
		}

		startBox("Result");
		if ($_SESSION['account']->username == trim($_POST['user'])) {
			echo '<p class="error">You need to enter the username and password of another account you own.</p>';
			endBox();
			return;
		}

		if (!isset($_POST['confirm'])) {
			echo '<p class="error">You need to tick the confirm-checkbox.</p>';
			endBox();
			return;
		}

		if ($_POST['csrf'] != $_SESSION['csrf']) {
			echo '<p class="error">Session information was lost.</p>';
			endBox();
			return;
		}

		$result = Account::tryLogin("password", $_POST['user'], $_POST['pass']);

		if (! ($result instanceof Account)) {
			echo '<span class="error">'.htmlspecialchars($result).'</span>';
			endBox();
			return;
		}

		if ($_SESSION['account']->password) {
			mergeAccount($_POST['user'], $_SESSION['account']->username);
			echo '<p class="success">Your old account <i>'.htmlspecialchars($_POST['user'])
				.'</i> was integrated into your account <i>'.htmlspecialchars($_SESSION['account']->username).'</i>.</p>';
		} else {
			$oldUsername = $_SESSION['account']->username;
			mergeAccount($oldUsername, $_POST['user']);
			$_SESSION['account'] = Account::readAccountByName($_POST['user']);
			echo '<p class="success">Your accounts <i>'.htmlspecialchars($_POST['user'])
				.'</i> and <i>'.htmlspecialchars($oldUsername)
				.'</i> have been merged.</p>';
		}
		endBox();
	}

	function displayForm() {
		startBox("Account to merge"); ?>
		<p>You are currently logged into the account <b><?php echo htmlspecialchars($_SESSION['account']->username) ?></b>.</p>

		<form action="" method="post">
			<input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf'])?>">
			<table>
				<tr><td><label for="user">Username:</label></td><td><input type="text" id="user" name="user" maxlength="30"></td></tr>
				<tr><td><label for="pass">Password:</label></td><td><input type="password" id="pass" name="pass" maxlength="30"></td></tr>
				<tr><td colspan="2" align="left"><input type="checkbox" id="confirm" name="confirm">
				<label for="confirm">I really want to merge these accounts.</label></td></tr>
				<tr><td colspan="2" align="right"><input type="submit" name="submerge" value="Merge"></td></tr>
			</table>
		</form>

		<?php endBox();

			if ($_REQUEST['test']) {
			echo '<br>';
			startBox("External Account");
			?>
				<form id="openid_form" action="" method="post">
		<input id="oauth_version" name="oauth_version" type="hidden">
		<input id="oauth_server" name="oauth_server" type="hidden">

		<div id="openid_choice">
			<p>Do you already have an account on one of these sites?</p>
			<div id="openid_btns"></div>
		</div>

		<div>
			<noscript>
				<p>OpenID is a service that allows you to log on to many different websites using a single identity.</p>
			</noscript>
		</div>
		<table id="openid-url-input">
		<tbody><tr>
			<td class="vt large">
				<input id="openid_identifier" name="openid_identifier" class="openid-identifier" style="height: 28px; width: 450px;" tabindex="100" type="text">
			</td>

			<td class="vt large">
				<input id="submit-button" style="margin-left: 5px; height: 36px;" value="Log in" tabindex="101" type="submit">
			</td>
		</tr>
		</tbody>
		</table>
		<input type="hidden" id="merge" name="merge" value="true">
	</form>

	<script type="text/javascript">
		$().ready(function() {
			openid.init('openid_identifier');
		});
	</script>

<?php

	if (isset($this->openid->error)) {
		echo '<div class="error">'.htmlspecialchars($this->openid->error).'</div>';
	}

		endBox();
		}

	}
}
$page = new AccountMerge();
?>
