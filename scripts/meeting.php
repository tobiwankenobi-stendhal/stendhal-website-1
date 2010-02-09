<?php

class Registration {
  public $realname;
  public $email;
  public $mobile;
  public $country;
  public $nickname;
  public $attent;
  public $comment;

  function __construct($realname, $email, $mobile, $country, $nickname, $attent, $comment) {
	$this->realname=$realname;
	$this->email=$email;
	$this->mobile=$mobile;
	$this->country=$country;
	$this->nickname=$nickname;
	$this->attent=$attent;
	$this->comment=$comment;
  }

}

function saveMeetingRegistration($playerId) {
	if (isset($_POST["realname"]) || isset($_POST["email"]) || isset($_POST["nickname"])) {
		$query = "insert into meeting (realname, email, mobile, country, nickname, attent, comment, player_id) values "
		 . "('" . mysql_real_escape_string($_POST["realname"]) . "', '" . mysql_real_escape_string($_POST["email"]) . "', '" . mysql_real_escape_string($_POST["mobile"])
		 . "', '" . mysql_real_escape_string($_POST["country"]) . "', '" . mysql_real_escape_string($_POST["nickname"]) . "', '" . mysql_real_escape_string($_POST["attent"])
		 . "', '" . mysql_real_escape_string($_POST["comment"]) . "', '" . $playerId . "');";
		 mysql_query($query, getGameDB());
	}
}

function getMeetingRegistration($playerId) {
	$query = "select * from meeting where player_id='".mysql_real_escape_string($playerId)."' ORDER BY id DESC LIMIT 1;";
	$result = mysql_query($query, getGameDB());

	while($row = mysql_fetch_assoc($result)) {

		$registration = new Registration(
			$row['realname'],
			$row['email'],
			$row['mobile'],
			$row['country'],
			$row['nickname'],
			$row['attent'],
			$row['comment']
		);
	}

	mysql_free_result($result);
	
	return $registration;
}