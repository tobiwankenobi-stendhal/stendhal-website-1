<?php

include_once('login_function.php');

startBox("Forgot your password?");
?>
In case you have forgotten your new password or your account information we can send you it to your email account that you used to create your stendhal account.<p>
<form action="" method="post">
<table>
  <tr><td>Email address:</td><td><input type="text" name="email" maxlength="90"></td></tr>
  <tr><td colspan="2" align="right"><input type="submit" name="lostpassword" value="Get new password"></td></tr>
</table>
</form>

<?php
endBox();

?>