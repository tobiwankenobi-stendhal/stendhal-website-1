<?php

include_once('login/login_function.php');

class LogoutPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Logout'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {

/**
 * Delete cookies - the time must be in the past,
 * so just negate what you added when creating the
 * cookie.
 */
if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){
   setcookie("cookname", "", time()-60*60*24*100, "/");
   setcookie("cookpass", "", time()-60*60*24*100, "/");
}

?>

<html>
<title>Logging Out</title>
<body>

<?php
startBox("Logout");
if(!checkLogin()){
   echo "You are not currently logged in, logout failed. Back to <a href=\"?\">main</a>";
}
else{
   /* Kill session variables */
   unset($_SESSION['username']);
   unset($_SESSION['password']);
   $_SESSION = array(); // reset session array
   session_destroy();   // destroy session.

   echo "<meta http-equiv=\"Refresh\" content=\"0;url=?\">";
   echo "<h1>Logged Out</h1>\n";
   echo "You have successfully <b>logged out</b>.<p>Back to <a href=\"?\">main</a>";
}

endBox();
	}
}
$page = new LogoutPage();
?>
