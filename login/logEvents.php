<?php

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
function logUserPasswordChange($user, $ip, $oldpass)
{
     $userid = getUserID($user);

     if ( $userid === false )
     {
          return false;
     }

     $q = "INSERT INTO passwordChange (player_id,address,oldpassword,service)".
          " values (".$userid.",'".$ip."','".$oldpass."','website')";

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
          "(".$userid.",'".$ip."',".($success ? '1' : '0').",'website')";

     $result = mysql_query($q, getGameDB());

     return $result !== false;
}