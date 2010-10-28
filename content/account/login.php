<?php

require_once('scripts/account.php');

class LoginPage extends Page {
	
	public function writeHttpHeader() {
		return $this->handleRedirectIfAlreadyLoggedIn();
	}

	public function writeHtmlHeader() {
		echo '<title>Login'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		
		/**
		 * Checks to see if the user has submitted his
		 * username and password through the login form,
		 * if so, checks authenticity in database and
		 * creates session.
		 */
		$showLoginForm = true;
		if(isset($_POST['sublogin'])) {
			$showLoginForm = ! $this->checkLoginForm();
		}
		if ($showLoginForm) {
			$this->displayLoginForm();
		}
	}

	function handleRedirectIfAlreadyLoggedIn() {
		if (isset($_REQUEST['url']) && checkLogin()) {
			header('Location: '.STENDHAL_LOGIN_TARGET.$this->getUrl());
			return false;
		}
		return true;
	}


	function checkLoginForm() {
			/* Check that all fields were typed in */
		if(!$_POST['user'] || !$_POST['pass']) {
			startBox("Login failed");
			echo "<span class=\"error\">You didn't fill in a required field.</span>";
			endBox();
			return false;
		}

		/* Spruce up username, check length */
		$username = trim($_POST['user']);
		$password = trim($_POST['pass']);

		$result = Account::tryLogin("password", $username, $password);

		if (! ($result instanceof Account)) {
			startBox("Login failed");
			echo '<span class="error">'.htmlspecialchars($result).'</span>';
			endBox();
			return false;
		}

		/* Username and password correct, register session variables */
		$_SESSION['account'] = $result;
		$_SESSION['csrf'] = createRandomString();

		echo "<meta http-equiv=\"Refresh\" content=\"1;url=".htmlspecialchars($this->getUrl())."\">";
		startBox("Login");
		echo '<h1>Login correct.</h1> Please wait...';
		endBox();
		return true;
	}

	function getUrl() {
		$url = $_REQUEST['url'];
		if (!isset($url)) {
			$url = rewriteURL('/account/myaccount.html');
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
		</div>
		<form action="" method="post">
			<table>
				<tr><td><label for="user">Username:</label></td><td><input type="text" id="user" name="user" maxlength="30"></td></tr>
				<tr><td><label for="pass">Password:</label></td><td><input type="password" id="pass" name="pass" maxlength="30"></td></tr>
				<!-- <tr><td colspan="2" align="left"><input type="checkbox" id="remember" name="remember">
				<label for="remember"><font size="2">Remember me next time</font></label></td></tr> -->
				<tr><td colspan="2" align="right"><input type="submit" name="sublogin" value="Login"></td></tr>
			</table>

			<?php
			if (isset($_REQUEST['url'])) {
				echo '<input type="hidden" name="url" value="'.htmlspecialchars($_REQUEST['url']).'">';
			}
			?>
		</form>

		<?php
		endBox();

	}
}
$page = new LoginPage();
?>
