<?php

include_once('login_function.php');
require_once('logEvents.php');

/**
 * Checks to see if the user has submitted his
 * username and password through the login form,
 * if so, checks authenticity in database and
 * creates session.
 */
if(isset($_POST['sublogin'])){
   /* Check that all fields were typed in */
   if(!$_POST['user'] || !$_POST['pass']){
     die('You didn\'t fill in a required field.');
     }
     
   /* Spruce up username, check length */
   $_POST['user'] = trim($_POST['user']);
   if(strlen($_POST['user']) > 30){
     startBox("Login failed");
     echo "Sorry, the username is longer than 30 characters, please shorten it.";
     endBox();
     return;
     }

   /* We first check that the username is not banned. 
    */
   $result = confirmValidStatus($_POST['user']);
   
   /* Check error codes */
   if($result == 2){
   	 /*
   	  * If result==1 then username doesn't exist, so we let the password check handle it.
   	  */
     startBox("Login failed");
     echo "Sorry. Your account is blocked by multiple passwords failures or it has been banned.";
     endBox();
     return;
     }

   /* Checks that username is in database and password is correct */
   $md5pass = strtoupper(md5($_POST['pass']));
   $result = confirmUser($_POST['user'], $md5pass);
   
   if ($result === 2) {
     /* We need to check the pre-Marauroa 2.0 passwords */
	 $md5pass = strtoupper(md5(md5($_POST['pass'],true)));
	 $result = confirmUser($_POST['user'], $md5pass);
     }

   /* Here we log the login attempt, with username, IP and whether failed or successful */
   logUserLogin($_POST['user'], $_SERVER['REMOTE_ADDR'], $result == 0);
	
   /* Check error codes */
   if($result != 0){
     startBox("Login failed");
     echo "Sorry. You mispelled either username or password.<br>Make sure you have an account at Stendhal.";
     endBox();
     return;
     }

   /* Username and password correct, register session variables */
   $_POST['user'] = stripslashes($_POST['user']);
   $_SESSION['username'] = $_POST['user'];
   $_SESSION['password'] = $md5pass;

   /**
    * This is the cool part: the user has requested that we remember that
    * he's logged in, so we set two cookies. One to hold his username,
    * and one to hold his md5 encrypted password. We set them both to
    * expire in 100 days. Now, next time he comes to our site, we will
    * log him in automatically.
    */
   if(isset($_POST['remember'])){
      setcookie("cookname", $_SESSION['username'], time()+60*60*24*100, "/");
      setcookie("cookpass", $_SESSION['password'], time()+60*60*24*100, "/");
   }
      
   echo "<meta http-equiv=\"Refresh\" content=\"5;url=?\">";
   startBox("Login");
     echo '<h1>Login correct.</h1> Moving to main page.';
   endBox();
} else {
startBox("Login");
?>

<div class="bubble">
  Remember not to disclose your username or password to anyone, not even friends or administrators.<br>
  Check that this webpage URL matchs your game server name.
</div>
<form action="" method="post">
<table>
  <tr><td>Username:</td><td><input type="text" name="user" maxlength="30"></td></tr>
  <tr><td>Password:</td><td><input type="password" name="pass" maxlength="30"></td></tr>
  <tr><td colspan="2" align="left"><input type="checkbox" name="remember">
  <font size="2">Remember me next time</font></td></tr>
  <tr><td colspan="2" align="right"><input type="submit" name="sublogin" value="Login"></td></tr>
</table>
</form>

<?php
endBox();

}
?>
