<?php

class Facebook {
	public $isAuth = false;
	
	public function doRedirect() {
		header('Location: https://www.facebook.com/dialog/oauth?client_id=' . FACEBOOK_APP_ID
			.'&redirect_uri=' . urlencode(Account::createReturnUrl()));
	}
	
	public function doRedirectWithCSRFToken($token) {
		header('Location: https://www.facebook.com/dialog/oauth?client_id=' . FACEBOOK_APP_ID
		.'&redirect_uri=' . urlencode(Account::createReturnUrl())
		.'&state=' . urlencode(token));
	}

	/**
	 * creates an AccountLink object based on the facebook identification
	 * 
	 * @return AccountLink or <code>FALSE</code> if  the validation failed
	 */
	public function createAccountLink() {
		$tokenUrl = "https://graph.facebook.com/oauth/access_token?"
			. "client_id=" . FACEBOOK_APP_ID 
			. "&redirect_uri=" . urlencode(Account::createReturnUrl())
			. "&client_secret=" . FACEBOOK_APP_SECRET 
			. "&code=" . $_REQUEST['code'];
		$response = file_get_contents($tokenUrl);
		$params = null;
		parse_str($response, $params);
		$url = "https://graph.facebook.com/me?fields=id,username,name&access_token=".$params['access_token'];
		$data = file_get_contents($url);
		$user = json_decode($data, true);
		if (!isset($user) || !isset($user['id'])) {
			return false;
		}
		if (isset($user['username'])) {
			$nickname = strtolower($user['username']);
		} else {
			$nickname = str_replace(" ", "", strtolower($user['name']));
		}

		// ["id"]=> "100001372455913" ["name"]=>"Petra Portal" ["first_name"]=>"Petra" ["last_name"]=>"Portal"
		
		$accountLink = new AccountLink(null, null, 'facebook', $user['id'], $nickname, null, null);
		return $accountLink;
	}
	
	/**
	 * handles an requested account merge
 	 *
	 * @param AccountLink $accountLink the account link created for the login
	 */
	public function merge($accountLink) {
		$oldAccount = $_SESSION['account'];
		$newAccount = Account::readAccountByLink('facebook', $accountLink->username, null);
	
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
		$account = Account::tryLogin('facebook', $accountLink->username, null);
	
		if (!$account || is_string($account)) {
			$account = $accountLink->createAccount();
		}
		$_SESSION['account'] = $account;
		$_SESSION['csrf'] = createRandomString();
		$_SESSION['marauroa_authenticated_username'] = $account->username;
		fixSessionPermission();
	}
}
