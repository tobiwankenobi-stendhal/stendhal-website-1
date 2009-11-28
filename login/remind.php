<?php

include_once('login_function.php');

class RemindPage extends Page {
	function writeContent() {

/*
   It is composed of two steps:
   a) User click new password and we email a md5(rand()) to him IF the account is registered with stendhal.
   b) The user confirms the link and we effectively change the password.
 */

if(isset($_POST["forgotpassword"])) {
  if(!isset($_POST["email"]) or !isset($_POST["account"])) {
    die('You didn\'t fill in a required field.');
  }
  
  $email=mysql_real_escape_string($_POST["email"]);
  
  if(existsUser($email)) {
    $signature=strtoupper(md5(rand()));
    
    /* Good, store it... */
    $username=getUser($email);
    
    $query='insert into remind_password values("'.$username.'","'.$signature.'",null)';
    if(!mysql_query($query, getWebsiteDB())) {
      echo '<span class="error">There has been a problem while sending your password.</span>';
      die();
    }
        
    /* Remove the entry or anything 48 hours old.*/
    $q = "delete from remind_password where datediff(now(),requested)>2";
    $result = mysql_query($q,getWebsiteDB());
    
    /* ...and email */
    $server=$_SERVER["SERVER_NAME"];
    $location=str_replace("/index.php","",$_SERVER["PHP_SELF"]);
    
    $clientip=$_SERVER['REMOTE_ADDR'];
    
    $body=file_get_contents("login/remindpassword.email");
    
    /* Fill variables */
    $body=str_replace("[SERVER]",$server.$location,$body);
    $body=str_replace("[SIGNATURE]",$signature,$body);
    $body=str_replace("[CLIENTIP]",$clientip,$body);
    
    if($body==false) {
      echo '<span class="error">There has been a problem while getting password email template.</span>';
      die();
    }
  
    $headers = 'From: noreply@stendhal.game-host.org';  
    if(!mail($email,"Password reset request",$body,$headers)) {
      echo '<span class="error">There has been a problem while sending your password email.</span>';
      die();
    }
    
    startBox("Password reset link emailed");
    ?>
      We have just sent you a link to reset your password.<br>
      Check you inbox and follow the email instructions.
      <p>
      Back to <a href="?">Main</a>
    <?php
    endBox();
  } else {
    startBox("Unregistered account");
    ?>
    The email that you have written is not registered in this server.<p>
    If you don't remind your email there is no way of reseting your password.
    <p>
    Back to <a href="?">Main</a>

    <?php
    endBox();
  	}
} else {
  startBox("Forgot your password?");
  ?>
  In case you have forgotten your new password or your account information we can send you it to your email account that you used to create your stendhal account.<p>
  <form action="" method="post">
  <table>
    <tr><td>Email address:</td><td><input type="text" name="email" maxlength="90"></td></tr>
    <tr><td>Account:</td><td><input type="text" name="account" maxlength="90"></td></tr>
    <tr><td><input type="checkbox" name="knowaccount" checked>I do remember my account name.</td></tr>
    <tr><td colspan="2" align="right"><input type="submit" name="forgotpassword" value="Get new password"></td></tr>
  </table>
  </form>

  <?php
  endBox();
  }
	}
}
$page = new RemindPage();
?>
