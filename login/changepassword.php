<?php

include_once('login_function.php');

if(!checkLogin()){
  die("You need to login first");
}

/**
 * Checks to see if the user has submitted his
 * username and password through the login form,
 * if so, checks authenticity in database and
 * creates session.
 */
if(isset($_POST['sublogin'])){
   /* Check that all fields were typed in */
   if(!$_SESSION['username'] || !$_POST['pass']){
      die('You didn\'t fill in a required field.');
   }
   /* Spruce up username, check length */
   $username = trim($_SESSION['username']);
   if(strlen($_POST['user']) > 30){
      die("Sorry, the username is longer than 30 characters, please shorten it.");
   }

   /* Checks that username is in database and password is correct */
   $md5pass = strtoupper(md5($_POST['pass']));
   $result = confirmUser($username, $md5pass);

   /* Check error codes */
   if($result == 1){
      die('That username doesn\'t exist in our database.');
   }
   else if($result == 2){
      die('Incorrect password, please try again.');
   }
   
   if($_POST['newpass']!=$_POST['newpass_retype']) {
      die('Password incorrectly typed.');
   }

   $conn=getGameDB();   
   
   /* Add slashes if necessary (for query) */
   if(!get_magic_quotes_gpc()) {
	$username = addslashes($_SESSION['username']);
   }

   /* Verify that user is in database */
   $md5newpass = strtoupper(md5($_POST['newpass']));
   $q = "update account set password='$md5newpass' where username = '$username'";
   $result = mysql_query($q,$conn);

   /* Username and password correct, register session variables */
   $_POST['user'] = stripslashes($_POST['user']);
   $_SESSION['username'] = $_POST['user'];
   $_SESSION['password'] = $md5newpass;
  
   echo "<meta http-equiv=\"Refresh\" content=\"5;url=?\">";
   startBox("Login");
     echo '<h1>Change password correct.</h1> Moving to main page.';
   endBox();
} else {
startBox("Change password");
?>

<form action="" method="post">
<table>
  <tr><td>Old Password:</td><td><input type="password" name="pass" maxlength="30"></td></tr>
  <tr><td>New Password:</td><td><input type="password" name="newpass" maxlength="30"></td></tr>
  <tr><td>Retype new Password:</td><td><input type="password" name="newpass_retype" maxlength="30"></td></tr>
  <tr><td colspan="2" align="right"><input type="submit" name="sublogin" value="Change Password"></td></tr>
</table>
</form>

<?php
endBox();

}
?>