<?php

require_once('scripts/account.php');

class LoginPage extends Page {

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


	function checkLoginForm() {
			/* Check that all fields were typed in */
		if(!$_POST['user'] || !$_POST['pass']) {
			startBox("Login failed");
			echo "<span class=\"error\">You didn't fill in a required field.</span>";
			endBox();
			return false;
		}

		/* Spruce up username, check length */
		$_POST['user'] = trim($_POST['user']);

		/* We first check that the username is not banned. */
		$result = checkAccount($_POST['user'], $_POST['pass']);

		/* Check error codes */
		if($result == 3) {
			startBox("Login failed");
			echo "<span class=\"error\">Sorry. Your account is blocked by multiple passwords failures or it has been banned.</span>";
			endBox();
			return false;
		}

		/* Here we log the login attempt, with username, IP and whether failed or successful */
		logUserLogin($_POST['user'], $_SERVER['REMOTE_ADDR'], $result == 0);

		/* Check error codes */
		if($result != 0){
			startBox("Login failed");
			echo "<span class=\"error\">Sorry. You misspelled either username or password.<br>Please make sure you have an account at Stendhal.</span>";
			endBox();
			return false;
		}

		/* Username and password correct, register session variables */
		$_SESSION['username'] = $_POST['user'];
		
		/**
		 * This is the cool part: the user has requested that we remember that
		 * he's logged in, so we set two cookies. One to hold his username,
		 * and one to hold his md5 encrypted password. We set them both to
		 * expire in 100 days. Now, next time he comes to our site, we will
		 * log him in automatically.
		 */
		if(isset($_POST['remember'])){
			setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
			$md5pass = strtoupper(md5($_POST['pass']));
			setcookie("cookpass", $md5pass, time()+60*60*24*100, "/");
		}

		$url = $_POST['url'];
		if (!isset($url)) {
			$url = '/';
		}
		if (strpos($url, '/') !== 0) {
			$url = '/'.$url;
		}
		echo "<meta http-equiv=\"Refresh\" content=\"1;url=".htmlspecialchars($url)."\">";
		startBox("Login");
		echo '<h1>Login correct.</h1> Moving to main page.';
		endBox();
		return true;
	}


	function displayLoginForm() {
		startBox("Login");
	?>

		<div class="bubble">
			Remember not to disclose your username or password to anyone, not even friends or administrators.<br>
			Check that this webpage URL matchs your game server name.
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
