<?php 
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
   
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$username = addslashes($username);
   }

   /* Verify that user is in database */
   $q = "select password from account where username = '$username'";
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
   
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$username = addslashes($username);
   }

   /* Verify that user is in database */
   $q = "select status from account where username = '$username'";
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
   
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$email = addslashes($email);
   }

   /* Verify that user email is in database */
   $q = "select * from account where email = '$email'";
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
  }}

/**
 * Determines whether or not to display the login
 * form or to show the user that he is logged in
 * based on if the session variables are set.
 */
function displayLogin(){
   if(checkLogin()){ 
     echo 'Logged as <b>'.$_SESSION['username'].'</b>. <a href="?id=login/changepassword">Change password</a> - <a href="?id=login/logout">Logout</a>';
   }
   else{
     echo '<a href="?id=login/login">Login</a>';
     /* TODO: Reenable when fix the email problem at Durkham's server.
     echo '<a href="?id=login/login">Login</a> - <a href="?id=login/remind">Forgot your Password?</a>';
     */
   }
 }
?>
