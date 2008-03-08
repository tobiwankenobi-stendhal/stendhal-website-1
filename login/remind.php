<?php

include_once('login_function.php');

/*
   It has two alternatives:
   a) User click new password and we email a md5(rand()) to him IF the account is registered with stendhal.
   b) The user confirms the link and we effectively change the password.
 */

if(isset($_POST["forgotpassword"])) {
  if(!isset($_POST["email"])) {
    die('You didn\'t fill in a required field.');
  }
  
  $email=$_POST["email"];
  $email=mysql_real_escape_string($email);
  
  if(existsUser($email)) {
    $signature=md5(rand());
    
    /* Good, store it... */
    $username=getUser($email);
    
    $query='insert into remind_password values("'.$username.'","'.$signature.'",null)';
    if(!mysql_query($query, getWebsiteDB())) {
        echo '<span class="error">There has been a problem while sending your password.</span>';
        echo '<span class="error_cause">'.$query.'</span>';
        return;
    }    
    
    /* ...and email */
    $server=$_SERVER["SERVER_NAME"];
    $location="login/approve.php";
    $clientip=$_SERVER['REMOTE_ADDR'];
    
    $body="
Hi

Someone has requested that the password for your account be reset.  
 
If you did not make this request, please simply disregard this
e-mail; it is sent only to the address on file for your account,
and will become invalid after 48 hours, so you do not have to
worry about your account being taken over.
 
To choose a new password, please go to the following URL:
 
http://$server/$location?id=$signature
 
This request originated from $clientip
 
Sincerely,
The Stendhal Team";

    print nl2br($body);
  
    $headers = 'From: noreply@stendhal.game-host.org';  
    if(mail($email,"Password reset request",$body,$headers)) {
      echo '<span class="error">There has been a problem while sending your password email.</span>';
      return;
    }
  }
}

startBox("Forgot your password?");
?>
In case you have forgotten your new password or your account information we can send you it to your email account that you used to create your stendhal account.<p>
<form action="" method="post">
<table>
  <tr><td>Email address:</td><td><input type="text" name="email" maxlength="90"></td></tr>
  <tr><td colspan="2" align="right"><input type="submit" name="forgotpassword" value="Get new password"></td></tr>
</table>
</form>

<?php
endBox();

?>