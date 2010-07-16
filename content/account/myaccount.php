<?php

class MyAccountPage extends Page {

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>My Account'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {

startBox("My Account"); ?>
	<p>You are logged in as <b><?php echo htmlspecialchars($_SESSION['username']);?></b>.</p>
<ul id="dmenu" >
	<?php 
		echo '<li><a href="'.rewriteURL('/account/change-password.html').'"><img src="/images/buttons/password_button.png" alt=" "> Change password</a></li>';
		echo '<li><a href="'.rewriteURL('/account/history.html').'"><img src="/images/buttons/history_button.png" alt=" "> Login history</a></li>';
		echo '<li><a href="'.rewriteURL('/account/merge.html').'"><img src="/images/buttons/players_button.png" alt=" "> Merge Accounts</a> - (<a href="https://stendhalgame.org/wiki/Stendhal_Account_Merging">Help</a>)</li>';
		echo '<li><a href="'.rewriteURL('/account/logout.html').'"><img src="/images/buttons/logout_button.png" alt=" "> Logout</a></li>';
	?>
</ul>
<?php endBox();
	}
}
$page = new MyAccountPage();
?>

