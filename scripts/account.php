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
   if(!$result || (mysql_numrows($result) < 1)){
      return 1; //Indicates username failure
   }

   /* Retrieve password from result, strip slashes */
   $dbarray = mysql_fetch_array($result);
   $dbarray['password']  = stripslashes($dbarray['password']);
   
   $password = stripslashes($password);

   /* Validate that password is correct */
   if($password==$dbarray['password']){
      return 0; //Success! Username and password confirmed
   }
   else{
      return 2; //Indicates password failure
   }
}

function confirmValidStatus($username){
   $conn=getGameDB();

   /* Verify that user is in database */
   $q = "select status from account where username = '".mysql_real_escape_string($username)."'";
   $result = mysql_query($q,$conn);
   if(!$result || (mysql_numrows($result) < 1)){
      return 1; //Indicates username failure
   }

   /* Retrieve password from result, strip slashes */
   $dbarray = mysql_fetch_array($result);
   
   $status=$dbarray['status'];
   
   /* Validate that password is correct */
   if($status=='active'){
      return 0; //Success!
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
      $_SESSION['password'] = $_COOKIE['cookpass'];
   }

   /* Username and password have been set */
   if(isset($_SESSION['username']) && isset($_SESSION['password'])){
      /* Confirm that username and password are valid */
      if(confirmUser($_SESSION['username'], $_SESSION['password']) != 0){
         /* Variables are incorrect, user not logged in */
         unset($_SESSION['username']);
         unset($_SESSION['password']);
         return false;
      }
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
  
  $result = mysql_query('select admin from character_stats where name="'.mysql_real_escape_string($_SESSION['username']).'"', getGameDB());
  while($row=mysql_fetch_assoc($result)) {
    return (int)$row['admin'];
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
function displayLogin(){
   if(checkLogin()){ 
     echo 'Logged in as <b>'.$_SESSION['username'].'</b>. <a href="/account/history.html">Login history</a> - <a href="/?id=login/changepassword">Change password</a> - <a href="/?id=login/logout">Logout</a>';
   }
   else{
     echo '<a href="'.STENDHAL_LOGIN_TARGET.'/?id=login/login">Login</a>';
     /* TODO: Reenable when sending of emails is possible on the server.
     echo '<a href="'.STENDHAL_LOGIN_TARGET.'/?id=login/login">Login</a> - <a href="/?id=login/remind">Forgot your Password?</a>';
     */
   }
 }

// Returns user id for username or false
function getUserID($username)
{
	$q = "SELECT id FROM account WHERE username = '".
                           mysql_real_escape_string($username)."'";

	$result = mysql_query($q, getGameDB());

     if (!$result || mysql_num_rows($result) !== 1)
     {
          /* Couldn't find the userid or DB failure */
          return false;
     }

     $row = mysql_fetch_assoc($result);

     return $row['id'];
}

// log password changes for user from ip
// returns boolean successfully logged
function logUserPasswordChange($user, $ip, $oldpass, $result)
{
     $userid = getUserID($user);

     if ( $userid === false )
     {
          return false;
     }

     $q = "INSERT INTO passwordChange (player_id, address, oldpassword, service, result)".
          " values (".$userid.", '".mysql_real_escape_string($ip)."', '".mysql_real_escape_string($oldpass)."', 'website',".mysql_real_escape_string($result).")";

     $result = mysql_query($q, getGameDB());

     return $result !== false;
}

// log logins for user from ip
// returns boolean successfully logged
function logUserLogin($user, $ip, $success)
{
     $userid = getUserID($user);
	
     if ( $userid === false )
     {
          return false;
     }

     $q = "INSERT INTO loginEvent (player_id,address,result,service) values ".
          "(".$userid.",'".mysql_real_escape_string($ip)."',".($success ? '1' : '0').",'website')";

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
	$query = 'INSERT INTO loginseed(player_id, address, seed, complete)'
		." SELECT id, '".mysql_real_escape_string($ip)."', '".mysql_real_escape_string($seed)."', '"
		.mysql_real_escape_string($authenticated)." FROM account WHERE username='".mysql_real_escape_string($username)."'";
	mysql_query($query, getGameDB());
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
