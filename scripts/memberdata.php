<?php

class Registration {
  public $realname;
  public $street;
  public $city;
  public $country;
  public $email;
  public $visiblename;
  public $visibleemail;

  function __construct($realname, $street, $city, $country, $email, $visiblename, $visibleemail) {
	$this->realname = $realname;
	$this->street = $street;
	$this->email = $email;
	$this->city = $city;
	$this->country = $country;
	$this->visiblename = $visiblename;
	$this->visibleemail = $visibleemail;
  }

}

function saveMemberdata($playerId) {
	if (isset($_POST["realname"])) {

		$sql = "insert into members (realname, street, city, country, email, visiblename, visibleemail, player_id) values "
		 . "(:realname, :street, :city, :country, :email, :visiblename, :visibleemail, :player_id);";

		$stmt = DB::web()->prepare($sql);
		return $stmt->execute(array(
			':realname' => $_POST["realname"], 
			':street' => $_POST["street"],
			':city' => $_POST["city"],
			':country' => $_POST["country"],
			':email' => $_POST["email"],
			':visiblename' => $_POST["visiblename"],
			':visibleemail' => $_POST["visibleemail"],
			':player_id' => $playerId
			));
		 
	}
	return false;
}

function getMemberdata($playerId) {
	$query = "select * from members where player_id=:playerId ORDER BY id DESC LIMIT 1;";
	$stmt = DB::web()->prepare($sql);
	$stmt->execute(array(':id' => $playerId));
	foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$registration = new Registration(
			$row['realname'],
			$row['street'],
			$row['city'],
			$row['country'],
			$row['email'],
			$row['visiblename'],
			$row['visibleemail']
		);
	}
	return $registration;
}