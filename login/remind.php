<?php

include_once('login_function.php');

/*
   It has two alternatives:
   a) User click new password and we email a md5(rand()) to him IF the account is registered with stendhal.
   b) The user confirms the link and we effectively change the password.
 */

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