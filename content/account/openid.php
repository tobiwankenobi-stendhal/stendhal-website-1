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

require_once('lib/openid/lightopenid.php');


class OpenidPage extends Page {
	private $error;

	public function writeHttpHeader() {
		if (!isset($_GET['openid_mode'])) {
			if (isset($_POST['openid_identifier'])) {
				$openid = new LightOpenID;
				$openid->identity = $_POST['openid_identifier'];
				$openid->required = array('contact/email', 'namePerson/friendly');
				$openid->realm     = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
				$openid->returnUrl = $_SERVER['SCRIPT_URI'].'?id='.$_REQUEST['id'];
				if ($_REQUEST['merge']) {
					$_SESSION['merge'] = true;
				} else {
					unset($_SESSION['merge']);
				}
				try {
					header('Location: ' . $openid->authUrl());
					return false;
				} catch (ErrorException $e) {
					$this->error = $e->getMessage();
				}
			}
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Openid'.STENDHAL_TITLE.'</title>';
		?><style type="text/css">

	</style>
	<script src="/css/jquery-00000001.js" type="text/javascript"></script>
	<script src="/css/openid-00000002.js" type="text/javascript"></script>
		<?php 
	}

	function writeContent() {
		try {
			if (!isset($_GET['openid_mode'])) {
				startBox("Open ID");
?>

	<form id="openid_form" action="" method="post">
		<input id="oauth_version" name="oauth_version" type="hidden">
		<input id="oauth_server" name="oauth_server" type="hidden">
		<?php
		if ($_REQUEST['merge']) {
			echo '<input id="merge" name="merge" type="hidden" value="true">';
		}
		?>

		<div id="openid_choice">
			<p>Do you already have an account on one of these sites?</p>
			<div id="openid_btns"></div>
		</div>

		<div id="openid_input_area"></div>
		<div>
			<noscript>
				<p>OpenID is a service that allows you to log on to many different websites using a single identity.</p>
			</noscript>
		</div>

		<p>Or, you can manually enter your OpenID</p>
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
	</form>

	<script type="text/javascript">
		$().ready(function() {
			openid.init('openid_identifier');
		});
	</script>

<?php

	if (isset($this->error)) {
		echo '<div class="error">'.htmlspecialchars($this->error).'</div>';
	}

		endBox();
			} elseif($_GET['openid_mode'] == 'cancel') {
				startBox('OpenID-Authentication');
				echo 'OpenID-Authentication was canceled.';
				endBox();
			} else {
				$accountLink = $this->createAccountLink();
				if (!$accountLink) {
					startBox('OpenID-Authentication');
					echo 'OpenID-Authentication failed.';
					endBox();
				} else {
					if (isset($_SESSION['account']) && isset($_SESSION['merge'])) {
						$this->succesfulOpenidAuthWhileLoggedIn($accountLink);
					} else {
						$this->succesfulOpenidAuthWhileNotLoggedIn($accountLink);
					}
					$target = '/account/mycharacters.html';
					$players = getCharactersForUsername($_SESSION['account']->username);
					if(sizeof($players)==0) {
						$target = '/account/create-character.html';
					}
					echo "<meta http-equiv=\"Refresh\" content=\"1;url=".htmlspecialchars(rewriteURL($target))."\">";
					startBox("Login");
					echo '<h1>Login correct.</h1> Please wait...';
					endBox();

				}
			}
		} catch(ErrorException $e) {
			echo htmlspecialchars($e->getMessage());
		}
	}

	/**
	 * creates an AccountLink object based on the openid identification
	 * 
	 * @return AccountLink or <code>FALSE</code> if  the validation failed
	 */
	public function createAccountLink() {
		$openid = new LightOpenID;
		$openid->realm     = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
		$openid->returnUrl = $_SERVER['SCRIPT_URI'].'?id='.$_REQUEST['id'];
		if (!$openid->validate()) {
			return false;
		}
		$attributes = $openid->getAttributes();
		$accountLink = new AccountLink(null, null, 'openid', $openid->identity, 
			$attributes['namePerson/friendly'], $attributes['contact/email'], $secret);
		return $accountLink;
	}

	/**
	 * handles a succesful openid authentication
	 * 
	 * @param AccountLink $accountLink the account link created for the login
	 */
	public function succesfulOpenidAuthWhileLoggedIn($accountLink) {
		$oldAccount = $_SESSION['account'];
		$newAccount = Account::tryLogin('openid', $accountLink->username, null);

		if (!$newAccount || is_string($newAccount)) {
			$accountLink->playerId = $oldAccount->id;
			$accountLink->insert();
		} else {
			if ($oldAccount->username != $newAccount->username) {
				mergeAccount($newAccount->username, $oldAccount->username);
			}
		}
	}

	public function succesfulOpenidAuthWhileNotLoggedIn($accountLink) {
		unset($_SESSION['account']);
		$account = Account::tryLogin('openid', $accountLink->username, null);

		if (!$account || is_string($account)) {
			$account = $accountLink->createAccount();
		}
		$_SESSION['account'] = $account;
		$_SESSION['csrf'] = createRandomString();
	}
}
$page = new OpenidPage();
