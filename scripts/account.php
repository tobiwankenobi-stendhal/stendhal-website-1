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

function checkAccount($username, $password) {
	/* Check that all fields were typed in */
	if(!$username || !$password) {
		return 1;
	}

	/* We first check that the username is not banned. */
	$result = confirmValidStatus($username);
	if($result == 2) {
		return 3;
	}
	if($result == 3) {
		return 4;
	}

	/* Checks that username is in database and password is correct */
	$md5pass = strtoupper(md5($password));
	$result = confirmUser($username, $md5pass);

	if ($result === 2) {
		/* We need to check the pre-Marauroa 2.0 passwords */
		$md5pass = strtoupper(md5(md5($password,true)));
		$result = confirmUser($username, $md5pass);
	}

	/* Here we log the login attempt, with username, IP and whether failed or successful */
	logUserLogin($$username, $_SERVER['REMOTE_ADDR'], $result == 0);

	return $result;
}

/**
 * Checks whether or not the given username is in the
 * database, if so it checks if the given password is
 * the same password in the database for that user.
 * If the user doesn't exist or if the passwords don't
 * match up, it returns an error code (1 or 2). 
 * On success it returns 0.
 */
function confirmUser($username, $password){
	$conn=getGameDB();

	/* Verify that user is in database */
	$q = "select password from account where username = '".mysql_real_escape_string($username)."'";
	$result = mysql_query($q,$conn);
	if (!$result || (mysql_numrows($result) < 1)){
		return 1; //Indicates username failure
	}

	/* Retrieve password from result, strip slashes */
	$dbarray = mysql_fetch_array($result);
	$dbarray['password']  = stripslashes($dbarray['password']);

	$password = stripslashes($password);

	/* Validate that password is correct */
	if ($password==$dbarray['password']){
		return 0; //Success! Username and password confirmed
	} else {
		return 2; //Indicates password failure
	}
}

function confirmValidStatus($username){
	$conn=getGameDB();

	/* Verify that user is in database */
	$q = "select status from account where username = '".mysql_real_escape_string($username)."'";
	$result = mysql_query($q,$conn);
	if (!$result || (mysql_numrows($result) < 1)){
		return 1; //Indicates username failure
	}

	/* Retrieve password from result, strip slashes */
	$dbarray = mysql_fetch_array($result);

	$status=$dbarray['status'];

	/* Validate that password is correct */
	if($status=='active'){
		return 0; //Success!
	} else if ($status=='merged') {
		return 3;
	} else {
		return 2; //Indicates account is blocked or inactive.
	}
}



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
 * established. Also checks to see if user has been remembered.
 * If so, the database is queried to make sure of the user's 
 * authenticity. Returns true if the user has logged in.
 */
function checkLogin(){
	/* Check if user has been remembered */
	if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
		$_SESSION['username'] = $_COOKIE['cookname'];
		if(confirmUser($_SESSION['username'], $_COOKIE['cookpass']) != 0){
			/* Variables are incorrect, user not logged in */
			unset($_SESSION['username']);
			return false;
		}
	}

	/* Username has been set */
	if (isset($_SESSION['username'])){
		/* Confirm that username and password are valid */
		return true;
	}
	/* User not logged in */
	else{
		return false;
	}
}

function getAdminLevel() {
	if(!checkLogin()) {
		return -1;
	}

	$result = mysql_query('select max(admin) As adminlevel from character_stats, characters, account where character_stats.name=characters.charname AND characters.player_id=account.id AND account.username="'.mysql_real_escape_string($_SESSION['username']).'"', getGameDB());
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

/**
 * Determines whether or not to display the login
 * form or to show the user that he is logged in
 * based on if the session variables are set.
 */
 /*
function displayLogin(){
	if(checkLogin()){ 
		echo 'Logged in as <a href="'.rewriteURL('/account/myaccount.html').'"><b>'.$_SESSION['username'].'</b></a>. '
		.'<a href="'.rewriteURL('/account/merge.html').'">Merge Accounts</a><br>'
		.'<a href="'.rewriteURL('/account/history.html').'">Login history</a>'
		.' - <a href="'.rewriteURL('/account/change-password.html').'">Change password</a>'
		.' - <a href="'.rewriteURL('/account/logout.html').'">Logout</a>';
	} else{
		echo '<a href="'.STENDHAL_LOGIN_TARGET.''.rewriteURL('/account/login.html').'">Login</a>';
		 TODO: Reenable when sending of emails is possible on the server.
		echo '<a href="'.STENDHAL_LOGIN_TARGET.'/?id=login/login">Login</a> - <a href="/?id=login/remind">Forgot your Password?</a>';
	
	}
}*/

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

// log password changes for user from ip
// returns boolean successfully logged
function logUserPasswordChange($user, $ip, $oldpass, $result) {
	$userid = getUserID($user);

	if ( $userid === false) {
		return false;
	}

	$q = "INSERT INTO passwordChange (player_id, address, oldpassword, service, result)".
		" values (".$userid.", '".mysql_real_escape_string($ip)."', '".mysql_real_escape_string($oldpass)."', 'website',".mysql_real_escape_string($result).")";

	$result = mysql_query($q, getGameDB());

	return $result !== false;
}

// log logins for user from ip
// returns boolean successfully logged
function logUserLogin($user, $ip, $success) {
	$userid = getUserID($user);

	if ( $userid === false ) {
		return false;
	}

	$q = "INSERT INTO loginEvent (player_id,address,result,service) values ".
		"(".$userid.",'".mysql_real_escape_string($ip)."',".($success ? '1' : '0').",'website')";

	$result = mysql_query($q, getGameDB());

	return $result !== false;
}


function logAccountMerge($character, $oldAccountId, $oldUsername, $newUsername) {
	$q = "INSERT INTO gameEvents (source, event, param1, param2) values ".
		"('".mysql_real_escape_string($character)."', 'accountmerge', '".mysql_real_escape_string($oldAccountId)."', '"
		.mysql_real_escape_string($oldUsername). "-->". mysql_real_escape_string($newUsername) ."')";
	$result = mysql_query($q, getGameDB());
	return $result !== false;
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
		logAccountMerge($row['charname'], $oldAccountId, $oldUsername, $newUsername);
	}
	mysql_free_result($result);
	mysql_query("UPDATE characters SET player_id='".mysql_real_escape_string($newAccountId)."' WHERE player_id='".mysql_real_escape_string($oldAccountId)."'", getGameDB());
	mysql_query("UPDATE accountLink SET player_id='".mysql_real_escape_string($newAccountId)."' WHERE player_id='".mysql_real_escape_string($oldAccountId)."'", getGameDB());
}


/**
 * gets a list of recent login events for that player
 */
function getLoginHistory($playerId) {
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
}

/**
 * gets a list of recent messages for that player
 */
function getStoredMessages($name) {
    $sql = "SELECT source, timedate, message, messageType, delivered FROM "
		. " postman WHERE target='".mysql_real_escape_string($name)
		. "' ORDER BY timedate DESC LIMIT 100;";

    $result = mysql_query($sql, getGameDB());
    $list=array();

    while($row = mysql_fetch_assoc($result)) {
        $list[] = new StoredMessage($row['source'], $row['timedate'],
            $row['message'], $row['messageType'], $row['delivered']);
    }

    mysql_free_result($result);

    return $list;
}


/**
  * A class that represents a StoredMessage
  */
class StoredMessage {
	/* source of message (who sent it) */
	public $source;
    /* date and time of event */
    public $timedate;
    /* content of message */
    public $message;
    /* type of message: S (Support); P (player); N (NPC)  */
    public $messageType;
    /* whether it was delivered */
    public $delivered;

    function __construct($source, $timedate, $message, $messageType, $delivered) {
        $this->source = $source;
        $this->timedate = $timedate;
        $this->message = $message;
        $this->messageType = $messageType;
        $this->delivered = $delivered;
    }
}
