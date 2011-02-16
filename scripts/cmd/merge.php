<?php

if (isset($_SERVER['SERVER_NAME'])) {
	echo 'Command line tool';
	exit;
}

if (count($_SERVER['argv']) != 3) {
	echo "Call php merge.php <oldaccount> <newaccount>.\r\n";
	echo "    <oldaccount> is the one which will be deactivated.\r\n";
	echo "    <newaccount> is the one which will get all characters.\r\n";
	exit;
}

set_include_path('../..');

require_once('scripts/website.php');

echo "\r\n\r\n\r\nIgnore the above errors\r\n\r\n\r\n\r\n\r\n\r\n";

connect();

$oldAccountId = getUserID($_SERVER['argv'][1]);
$newAccountId = getUserID($_SERVER['argv'][2]);

echo 'Old id: '.$oldAccountId. '      New id: '.$newAccountId."\r\n";

if (!isset($oldAccountId) || !isset($newAccountId) || $oldAccountId <= 0 || $newAccountId <= 0) {
	echo 'Invalid user name.'."\r\n";
	exit;
}

if ($oldAccountId = $newAccountId) {
	echo 'You need to provide different account names.'."\r\n";
	exit;
}

mergeAccount($_SERVER['argv'][1], $_SERVER['argv'][2]);
