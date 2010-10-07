<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008  Miguel Angel Blanch Lardin
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

include_once('scripts/mysql.php');





/**
 * Checks whether or not the given email is in the
 * database.
 */
function existsUser($email){
	$conn=getGameDB();

	/* Verify that user email is in database */
	$q = "select * from account where email = '".mysql_real_escape_string($email)."'";
	$result = mysql_query($q,$conn);

	return $result and mysql_numrows($result)==1;
}

/**
 * checkLogin - Checks if the user has already previously
 * logged in, and a session with the user has already been
 * established. Returns true if the user has logged in.
 */
function checkLogin(){
	return (isset($_SESSION['account']));
}


function getAdminLevel() {
	if(!checkLogin()) {
		return -1;
	}

	$sql = "select max(admin) As adminlevel FROM character_stats, characters, account "
		. " WHERE character_stats.name=characters.charname AND characters.player_id=account.id "
		. " AND account.username='".mysql_real_escape_string($_SESSION['account']->username)."'";
	$result = mysql_query($sql, getGameDB());
	while($row=mysql_fetch_assoc($result)) {
		return (int)$row['adminlevel'];
	}
}

function getUser($email) {
	$result = mysql_query('select username from account where email="'.mysql_real_escape_string($email).'"', getGameDB());
	while($row=mysql_fetch_assoc($result)) {
		return $row['username'];
	}
}


// Returns user id for username or false
function getUserID($username) {
	$q = "SELECT id FROM account WHERE username = '".
	mysql_real_escape_string($username)."'";

	$result = mysql_query($q, getGameDB());

	if (!$result || mysql_num_rows($result) !== 1) {
		/* Couldn't find the userid or DB failure */
		return false;
	}

	$row = mysql_fetch_assoc($result);
	return $row['id'];
}




/**
 * verifies that thae specified characer belongs to the specified account
 *
 * @param string $username name of account
 * @param string $charname name of character
 * @return boolean
 */
function verifyCharacterBelongsToUsername($username, $charname) {
	$sql = "SELECT player_id "
	. "FROM account, characters "
	. "WHERE account.username='".mysql_real_escape_string($username)
	. "' AND account.id=characters.player_id "
	. "AND characters.charname='".mysql_real_escape_string($charname)."'";
	$result = mysql_query($sql, getGameDB());
	$res = mysql_numrows($result) > 0;
	mysql_free_result($result);
	return $res;
}


function storeSeed($username, $ip, $seed, $authenticated) {
	$query = 'INSERT INTO loginseed(player_id, address, seed, complete, used)'
	." SELECT id, '".mysql_real_escape_string($ip)."', '".mysql_real_escape_string($seed)."', '"
	.mysql_real_escape_string($authenticated)."', 0 FROM account WHERE username='".mysql_real_escape_string($username)."'";

	mysql_query($query, getGameDB());
}


/**
 * merges two accounts
 * @param string $oldUsername
 * @param string $newUsername
 */
function mergeAccount($oldUsername, $newUsername) {
	$oldAccountId = getUserID($oldUsername);
	$newAccountId = getUserID($newUsername);
	mysql_query("UPDATE account SET status='merged' WHERE id='".mysql_real_escape_string($oldAccountId)."'", getGameDB());
	$result = mysql_query("SELECT charname FROM characters WHERE player_id='".mysql_real_escape_string($oldAccountId)."'", getGameDB());
	while($row = mysql_fetch_assoc($result)) {
		PlayerLoginEntry::logAccountMerge($row['charname'], $oldAccountId, $oldUsername, $newUsername);
	}
	mysql_free_result($result);
	mysql_query("UPDATE characters SET player_id='".mysql_real_escape_string($newAccountId)."' WHERE player_id='".mysql_real_escape_string($oldAccountId)."'", getGameDB());
	mysql_query("UPDATE accountLink SET player_id='".mysql_real_escape_string($newAccountId)."' WHERE player_id='".mysql_real_escape_string($oldAccountId)."'", getGameDB());
}


/**
 * adds an account link (like an openid)
 *
 * @param string $username the name of the logged in user to add the link to
 * @param $identifier the openid identifier
 * @param $type "openid" or "connect"
 * @param $email email address provided by the identity provider
 * @param $nickname nick name
 */
function addAccountLink($username, $type, $identifier, $email, $nickname) {
	$accountId = getUserID($username);
	$sql = "INSERT INTO accountLink (player_id, type, username, nickname, email) VALUES ('"
	. mysql_real_escape_string($accountId)."', '"
	. mysql_real_escape_string($type)."', '"
	. mysql_real_escape_string($identifier)."', '"
	. mysql_real_escape_string($email)."', '"
	. mysql_real_escape_string($nickname)."');";
	mysql_query($sql, getGameDB());
}


/**
 * A class that represent a player history entry
 */
class PlayerLoginEntry {
	/* date and time of event */
	public $timedate;
	/* name of ip-address */
	public $address;
	/* name of service */
	public $service;
	/* type of event */
	public $eventType;
	/* success */
	public $success;

	function __construct($timedate, $address, $service, $eventType, $success) {
		$this->timedate = $timedate;
		$this->address = $address;
		$this->service = $service;
		$this->eventType = $eventType;
		$this->success = $success;
	}


	/**
	 * gets a list of recent login events for that player
	 */
	public static function getLoginHistory($playerId) {
		$sql = "SELECT address, timedate, service, event, result FROM "
		. "(SELECT address, timedate, service, 'login' As event, result FROM loginEvent "
		. "WHERE player_id=".mysql_real_escape_string($playerId)." AND timedate > DATE_SUB(CURDATE(),INTERVAL 7 DAY) "
		. "UNION SELECT address, timedate, service, 'password change' As event, result FROM passwordChange "
		. "WHERE player_id=".mysql_real_escape_string($playerId)." AND timedate > DATE_SUB(CURDATE(),INTERVAL 7 DAY)) As data "
		. "ORDER BY timedate DESC LIMIT 25;";

		$result = mysql_query($sql, getGameDB());
		$list=array();

		while($row = mysql_fetch_assoc($result)) {
			$list[] = new PlayerLoginEntry($row['timedate'],
			$row['address'], $row['service'], $row['event'],$row['result']);
		}

		mysql_free_result($result);
		return $list;
	}

	/**
	 * log password changes for user from ip
	 * returns boolean successfully logged
	 */
	public static function logUserPasswordChange($user, $ip, $oldpass, $result) {
		$userid = getUserID($user);

		if ( $userid === false) {
			return false;
		}

		$q = "INSERT INTO passwordChange (player_id, address, oldpassword, service, result)".
			" values (".$userid.", '".mysql_real_escape_string($ip)."', '".mysql_real_escape_string($oldpass)."', 'website', ".($result ? '1' : '0').")";
		$result = mysql_query($q, getGameDB());
		return $result !== false;
	}

	/**
	 * log logins for user from ip
	 * returns boolean successfully logged
	 */
	public static function logUserLogin($user, $ip, $accountLink, $success) {
		$userid = getUserID($user);

		if ( $userid === false ) {
			return false;
		}

		$q = "INSERT INTO loginEvent (player_id, address, result, service";
		if ($accountLink) {
			$q = $q .', account_link_id';
		}
		$q = $q . ") values (".$userid.", '".mysql_real_escape_string($ip)." ',".($success ? '1' : '0').", 'website'";
		if ($accountLink) {
			$q = $q . ", '".mysql_real_escape_string($accountLink)."'";
		}
		$q = $q . ")";

		$result = mysql_query($q, getGameDB());
		return $result !== false;
	}

	public static function logAccountMerge($character, $oldAccountId, $oldUsername, $newUsername) {
		$q = "INSERT INTO gameEvents (source, event, param1, param2) values ".
			"('".mysql_real_escape_string($character)."', 'accountmerge', '".mysql_real_escape_string($oldAccountId)."', '"
			.mysql_real_escape_string($oldUsername). "-->". mysql_real_escape_string($newUsername) ."')";
			$result = mysql_query($q, getGameDB());
			return $result !== false;
	}
}


/**
 * A class that represents a StoredMessage
 */
class StoredMessage {
	/* source of message (who sent it) */
	public $source;
	/* target of message (who it was sent to) */
	public $target;
	/* date and time of event */
	public $timedate;
	/* content of message */
	public $message;
	/* type of message: S (Support); P (player); N (NPC)  */
	public $messageType;
	/* whether it was delivered */
	public $delivered;

	function __construct($source, $target, $timedate, $message, $messageType, $delivered) {
		$this->source = $source;
		$this->target = $target;
		$this->timedate = $timedate;
		$this->message = $message;
		$this->messageType = $messageType;
		$this->delivered = $delivered;
	}

	/**
	 * gets a list of recent messages for that player
	 */
	public static function getCountUndeliveredMessages($playerId, $where) {
		$sql = "SELECT count(*) as count "
		. " FROM postman , characters "
		. " WHERE " . $where
		. " AND characters.player_id=".mysql_real_escape_string($playerId)
		. " AND delivered = 0;";
		$result = mysql_query($sql, getGameDB());

		while($row = mysql_fetch_assoc($result)) {
			$count = $row['count'];
		}

		mysql_free_result($result);
		return $count;
	}

	/**
	 * gets a list of recent messages for that player
	 */
	public static function getStoredMessages($playerId, $where) {
		$sql = "SELECT postman.source, postman.target, postman.timedate, postman.message, postman.messageType, postman.delivered "
		. " FROM postman , characters "
		. " WHERE " . $where
		. " AND  characters.player_id=".mysql_real_escape_string($playerId)
		. " AND postman.timedate > DATE_SUB(CURDATE(),INTERVAL 3 MONTH) "
		. " ORDER BY postman.timedate DESC LIMIT 100;";
		// echo $sql;
		$result = mysql_query($sql, getGameDB());
		$list=array();

		while($row = mysql_fetch_assoc($result)) {
			$list[] = new StoredMessage($row['source'], $row['target'], $row['timedate'],
			$row['message'], $row['messageType'], $row['delivered']);
		}

		mysql_free_result($result);
		return $list;
	}
}



/**
 * Account
 *
 * @author hendrik
 */
class Account {
	public $id;
	public $username;
	public $password;
	public $email;
	public $timedate;
	public $status;
	public $banMessage;
	public $banExpire;
	public $links;
	public $usedAccountLink;

	public function __construct($id, $username, $password, $email, $timedate, $status) {
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->timedate = $timedate;
		$this->status = $status;
	}

	public static function tryLogin($type, $username, $password) {
		if (!Account::checkIpBan()) {
			return "Your IP Address has been banned.";
		}

		// ask database
		if ($type == 'password' || $type == 'passwordchange') {
			// TODO: check account block because of too many wrong logins
			$account = Account::readAccountByName($username);
			if (isset($account)) {
				$success = $account->checkPassword($password);
			} else {
				$success = false;
			}
		} else {
			$account = Account::readAccountByLink($type, $username, $password);
			if (isset($account)) {
				$success = true;
			}
		}

		if ($account instanceof Account) {
			$account->readAccountBan();
			$banMessage = $account->getAccountStatusMessage();
			if (isset($banMessage)) {
				$success = false;
			}
			$passhash = $account->password;
			$usedAccountLink = $account->usedAccountLink;
		}

		
		// Log loginEvent or passwordChange
		if ($type != 'passwordchange') {
			PlayerLoginEntry::logUserLogin($username, $_SERVER['REMOTE_ADDR'], $usedAccountLink, $success);
		} else {
			PlayerLoginEntry::logUserPasswordChange($username, $_SERVER['REMOTE_ADDR'], $passhash, $success);
		}
		
		// if the account does not exist or the password was wrong
		if (!($account instanceof Account) || (!$success && !isset($banMessage))) {
			return "Invalid username or password";
		}
		
		// if the account is banned
		if (isset($banMessage)) {
			return $banMessage;
		}
		return $account;
	}

	private static function checkIpBan() {
		// TODO: implement me
		return true;
	}

	/**
	 * reads an account object based on the username.
	 *
	 * @param string $username username
	 */
	private static function readAccountByName($username) {
		$sql = "SELECT id, username, password, email, timedate, status "
		. " FROM account WHERE username='".mysql_real_escape_string($username)."'";
		$result = mysql_query($sql, getGameDB());
		$list=array();

		$row = mysql_fetch_assoc($result);
		if ($row) {
			$res = new Account($row['id'], $row['username'], $row['password'], $row['email'], $row['timedate'], $row['status']);
		}

		mysql_free_result($result);
		return $res;
	}

	/**
	 * reads an account object from the database based on an account link.
	 *
	 * @param string $type "openid", "facebook"
	 * @param string $username identifier (e. g. openid url)
	 * @param string $password an optional secret
	 */
	private static function readAccountByLink($type, $username, $password) {
		$sql = "SELECT account.id As id, account.username As username, "
		. " account.password As password, account.email As email, "
		. " account.timedate As timedate, account.status As status "
		. " FROM account, accountLink WHERE account.id = accountLink.player_id "
		. " AND type='".mysql_real_escape_string($type)."'"
		. " AND username='".mysql_real_escape_string($username)."'";
		if (isset($password)) {
			$sql = $sql . " AND secret='".mysql_real_escape_string($password)."'";
		} else {
			$sql = $sql . " AND secret IS NULL";
		}
		$result = mysql_query($sql, getGameDB());
		$list=array();

		$row = mysql_fetch_assoc($result);
		if ($row) {
			$res = new Account($row['id'], $row['username'], $row['password'], $row['email'], $row['timedate'], $row['status']);
		}

		mysql_free_result($result);
		return $res;
	}


	/**
	 * checks that the password is correct
	 *
	 * @param string password
	 */
	private function checkPassword($password) {
		$md5pass = strtoupper(md5($password));
		if ($md5pass == $this->password) {
			$result = true;
		} else {
			$result = false;
		}
		
		if (!$result) {
			// We need to check the pre-Marauroa 2.0 passwords
			$md5pass = strtoupper(md5(md5($password, true)));
			if ($md5pass == $this->password) {
				$result = true;
			} else {
				$result = false;
			}
		}
		return $result;
	}

	private function readAccountBan() {
		$sql = "SELECT reason, expire FROM accountban "
			." WHERE accountban.player_id='".mysql_real_escape_string($this->id)."'"
			." AND (accountban.expire > CURRENT_TIMESTAMP OR accountban.expire IS NULL) ORDER BY ifnull(expire,'9999-12-31') desc limit 1 ";
		$result = mysql_query($sql, getGameDB());
		$list=array();

		$row = mysql_fetch_assoc($result);
		if ($row) {
			$this->banMessage = $row['reason'];
			$this->banExpire = $row['expire'];
		}

		mysql_free_result($result);
		return $res;
	}

	/**
	 * get a message telling the player why the account is not active
	 * 
	 * @return message or <code>null</code> if the account is active
	 */
	public function getAccountStatusMessage() {
		if (isset($this->banMessage)) {
			if (isset($this->banExpire)) {
				$res = "Your account is temporarily banned until " . $this->banExpire . " server time.\n";
			} else {
				$res = "Your account is banned.\n";
			}
			$res = $res . "The reason given was: " . $this->banMessage;
		} else if ($this->status == "banned") {
			$res = "Your account has been banned. Please contact support.";
		} else if ($this->status == "inactive") {
			$res = "Your account has been flagged as inactive. Please contact support.";
		} else if ($this->status == "merged") {
			$res = "Your account has been merged into another account. Please login with that account or contact support.";
		}
		return $res;
	}
}

/**
 * Account Link
 *
 * @author hendrik
 */
class AccountLink {
	public $id;
	public $playerId;
	public $type;
	public $username;
	public $nickname;
	public $email;
	public $secret;

	/**
	 * creates a new AccountLink
	 */
	public function __construct($id, $playerId, $type, $username, $nickname, $email, $secret) {
		$this->id = $id;
		$this->playerId = $playerId;
		$this->type = $type;
		$this->username = $username;
		$this->nickname = $nickname;
		$this->email = $email;
		$this->secret = $secret;
	}

	public static function getAccountLinks($playerId) {
		$links = array();
		$sql = "SELECT id, player_id, type, username, nickname, email, secret "
		. "FROM accountLink "
		. "WHERE player_id ='".mysql_real_escape_string($playerId)."'";
		$result = mysql_query($sql, getGameDB());
		while($row = mysql_fetch_assoc($result)) {
			$links[] = new AccountLink($row['id'], $row['player_id'],
			$row['type'], $row['username'], $row['nickname'],
			$row['email'], $row['secret']);
		}
		mysql_free_result($result);
		return $links;
	}

	public static function findAccountLink($type, $username) {
		$sql = "SELECT id, player_id, type, username, nickname, email, secret "
		. "FROM accountLink "
		. "WHERE username ='".mysql_real_escape_string($username)."'"
		. " AND type = '".mysql_real_escape_string($username)."'";
		$result = mysql_query($sql, getGameDB());
		while($row = mysql_fetch_assoc($result)) {
			$links[] = new AccountLink($row['id'], $row['player_id'],
			$row['type'], $row['username'], $row['nickname'],
			$row['email'], $row['secret']);
		}
		mysql_free_result($result);
		return $links;
	}
}