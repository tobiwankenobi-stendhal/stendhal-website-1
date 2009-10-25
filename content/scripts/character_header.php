<?php
// TODO: don't query the database twice: Here and in character.php
$name=$_REQUEST["name"];
$players=getPlayers('where name="'.addslashes($name).'"', 'name');
if(sizeof($players) > 0) {
	$choosen=$players[0];
	$account=$choosen->getAccountInfo();
	if ($account["status"] != 'active') {
		echo '<meta name="robots" content="noindex" />'."\n";
	}
}
?>