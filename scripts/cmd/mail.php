<?php

if (!isset($_SERVER['SERVER_NAME'])) {
	
	if (count($_SERVER['argv']) != 5) {
		echo "Call php mail.php player_id username token email.\r\n";
		exit;
	}
	
	set_include_path('../..');
	
	require_once('scripts/website.php');
	
	connect();
	
	$playerId = $_SERVER['argv'][1];
	$username = $_SERVER['argv'][2];
	$token = $_SERVER['argv'][3];
	$email = $_SERVER['argv'][4];

	sendRegistrationMail($playerId, $username, $token, $email);
}

function sendRegistrationMail($playerId, $username, $token, $email) {
	$subject = "Stendhal account ".$username." email verification";
	$body = "Hello ".$username."!\r\n";
	$body .= "\r\n";
	$body .= "Please confirm your email-address by clicking on this link:\r\n";
	$body .= STENDHAL_LOGIN_TARGET."/account/confirm/".urlencode($token)."\r\n";
	$body .= "\r\n";
	$body .= "\r\n";
	$body .= "If you did not create a Stendhal account, please ignore this email.\r\n";
	$body .= "Your email address will be used for account recovery, if you forget \r\n";
	$body .= "your password or if there are indications for an account hack.\r\n";
	$body .= "\r\n";
	$body .= "Best regards from the Stendhal team\r\n";
	$body .= "\r\n";
	mail($email, $subject, $body, "From: ".STENDHAL_NOREPLY_EMAIL, '-f '.STENDHAL_NOREPLY_ADDRESS);
}
