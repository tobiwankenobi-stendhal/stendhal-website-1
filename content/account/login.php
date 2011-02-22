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

class LoginPage extends Page {
	private $error;
	private $openid;

	public function writeHttpHeader() {
		if ($this->handleRedirectIfAlreadyLoggedIn()) {
			return false;
		}

		// force SSL if supported
		if (strpos(STENDHAL_LOGIN_TARGET, 'https://') !== false) {
			if (!isset($_SERVER['HTTPS']) || ($_SERVER['HTTPS'] != "on")) {
				header('Location: '.STENDHAL_LOGIN_TARGET.rewriteURL('/account/login.html'));
				return false;
			}
		}

		// redirect to openid provider?
		$this->openid = new OpenID();
		$this->openid->doOpenidRedirectIfRequired();
		if ($this->openid->isAuth && !$this->openid->error) {
			return false;
		}

		if ($this->verifyLoginByPassword()) {
			return false;
		}

		if ($this->verifyLoginByOpenid()) {
			return false;
		}

		return true;
	}

	public function verifyLoginByPassword() {
		if (!isset($_POST['sublogin'])) {
			return false;
		}

		if( !$_POST['user'] || !$_POST['pass']) {
			$this->error = "You didn't fill in a required field.";
			return false;
		}

		$username = trim($_POST['user']);
		$password = trim($_POST['pass']);
		$result = Account::tryLogin("password", $username, $password);
		if (! ($result instanceof Account)) {
			$this->error = $result;
			return false;
		}

		/* Username and password correct, register session variables */
		$_SESSION['account'] = $result;
		$_SESSION['marauroa_authenticated_username'] = $result->username;
		$_SESSION['csrf'] = createRandomString();
		fixSessionPermission();
		header('Location: '.STENDHAL_LOGIN_TARGET.$this->getUrl());
		return true;
	}

	public function verifyLoginByOpenid() {
		if (!isset($_GET['openid_mode'])) {
			return false;
		}

		if($_GET['openid_mode'] == 'cancel') {
			$this->openid->error = 'OpenID-Authentication was canceled.';
			return false;
		}

		$accountLink = $this->openid->createAccountLink();
		if (!$accountLink) {
			$this->openid->error = 'OpenID-Authentication failed.';
			return false;
		}
		$this->openid->succesfulOpenidAuthWhileNotLoggedIn($accountLink);
		header('Location: '.STENDHAL_LOGIN_TARGET.$this->getUrl());
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Login'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<script src="'.STENDHAL_FOLDER.'/css/jquery-00000001.js" type="text/javascript"></script>';
		echo '<script src="'.STENDHAL_FOLDER.'/css/openid-00000002.js" type="text/javascript"></script>';
	}

	function writeContent() {
		$this->displayLoginForm();
	}

	function handleRedirectIfAlreadyLoggedIn() {
		if (checkLogin()) {
			if (isset($_REQUEST['url'])) {
				header('Location: '.STENDHAL_LOGIN_TARGET.$this->getUrl());
			} else {
				header('Location: '.STENDHAL_LOGIN_TARGET.rewriteURL('/account/mycharacters.html'));
			}
			return true;
		}
		return false;
	}

	function getUrl() {
		$url = $_REQUEST['url'];
		if (!isset($url)) {
			$url = rewriteURL('/account/mycharacters.html');
			$players = getCharactersForUsername($_SESSION['account']->username);
			if(sizeof($players)==0) {
				$url = rewriteURL('/account/create-character.html');
			}
		}
		if (strpos($url, '/') !== 0) {
			$url = '/'.$url;
		}
		// prevent header splitting
		if (strpos($url, '\r') || strpos($url, '\n')) {
			$url = '/';
		}
		return $url;
	}

	function displayLoginForm() {
		startBox("Login");
	?>

		<div class="bubble">
			Remember not to disclose your username or password to anyone, not even friends or administrators.<br>
			Check that this webpage URL matches your game server name.
		</div><br>

		<?php
		if ($this->error) {
			echo "<p class=\"error\">".htmlspecialchars($this->error)."</p>";
		}?>

		<form action="" method="post">
			<table>
				<tr><td><label for="user">Username:</label></td><td><input type="text" id="user" name="user" maxlength="30"></td></tr>
				<tr><td><label for="pass">Password:</label></td><td><input type="password" id="pass" name="pass" maxlength="30"></td></tr>
				<tr><td colspan="2" align="right"><input type="submit" name="sublogin" value="Login"></td></tr>
			</table>
			<?php
			if (isset($_REQUEST['url'])) {
				echo '<input type="hidden" name="url" value="'.htmlspecialchars($_REQUEST['url']).'">';
			}
			?>
		</form>
		<br>

		<p style="text-align: center">New? <b><a href="<?php echo rewriteURL('/account/create-account.html')?>">Create account...</a></b></p>
		<br>
		<?php
		endBox();

			echo '<br>';
			startBox("External Account");
			?>
				<form id="openid_form" action="<?php echo STENDHAL_FOLDER;?>/?id=content/account/login" method="post">
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
		</tr></tbody>
		</table>
		<?php 
		if (isset($_REQUEST['url'])) {
			echo '<input type="hidden" name="url" value="'.htmlspecialchars($_REQUEST['url']).'">';
		}
		?>
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
$page = new LoginPage();
?>
