<?php

function acfUserCreateForm($template) {
	$_SESSION['acf_wiki_timestamp'] = time();
	return true;
}

function acfAbortNewAccount($user, $message) {
	if ($_SESSION['acf_wiki_timestamp'] + 3 > time()) {
		$message = 'Automatic SPAM filter triggered, please try again later.';
		return false;
	}
	return true;
}


$wgHooks['UserCreateForm'][] = 'acfUserCreateForm';
$wgHooks['AbortNewAccount'][] = 'acfAbortNewAccount';
