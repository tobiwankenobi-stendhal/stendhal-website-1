<?php

require_once('lib/openid/lightopenid.php');


class OpenidPage extends Page {

	public function writeHttpHeader() {
		if (!isset($_GET['openid_mode'])) {
			if (isset($_POST['openid_identifier'])) {
				$openid = new LightOpenID;
				$openid->identity = $_POST['openid_identifier'];
				$openid->required = array('contact/email');
				$openid->realm     = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
				$openid->returnUrl = $openid->realm . $_SERVER['REQUEST_URI'];
				header('Location: ' . $openid->authUrl());
				return false;
			}
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Openid'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {



		try {
			if (!isset($_GET['openid_mode'])) {
		startBox("Open ID");
?>
<form action="" method="post">
    OpenID: <input type="text" name="openid_identifier" /> <button>Submit</button>
</form>
<?php
		endBox();
			} elseif($_GET['openid_mode'] == 'cancel') {
				echo 'User has canceled authentication!';
			} else {
				startBox("Result");
				$openid = new LightOpenID;
				echo 'Validate: ' . $openid->validate() . '<br>';
				echo 'Identity: ' . htmlspecialchars($openid->identity) . '<br>';
				$attributes = $openid->getAttributes();
				echo 'E-Mail: ' . htmlspecialchars($attributes['contact/email']);
				endBox();
			}
		} catch(ErrorException $e) {
			echo $e->getMessage();
		}

		endBox();
	}
}
$page = new OpenidPage();
?>

