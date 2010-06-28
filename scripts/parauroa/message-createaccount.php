<?php
class ParauroaMessageC2SCreateAccount extends ParauroaMessage{

	/** Desired username */
	private $username;

	/** Desired password */
	private $password;

	/** email address for whatever thing it may be needed. */
	private $email;

	/** Constructor for allowing creation of an empty message */
	public function __construct() {
		parent::__construct(ParauroaMessageType::C2S_CREATEACCOUNT);
	}

	/**
	 * Constructor with username, password
	 * and email associated to the account to be created.
	 *
	 * @param username desired username
	 * @param password desired password
	 * @param email email of the player
	 */
	public function __construct($username, $password, $email) {
		parent::__construct(ParauroaMessageType::C2S_CREATEACCOUNT);
		$this->username = username;
		$this->password = password;
		$this->email = email;
	}

	/**
	 * Returns desired account's username
	 * @return desired account's username
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Returns desired account's password
	 * @return desired account's password
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Returns the account associated email.
	 * @return the account associated email.
	 */
	public function getEmail() {
		return $this->email;
	}

	public function writeObject($out) {
		parent::writeObject(out);
		$out->writeString($this->username);
		$out->write($this->password);
		$out->write($this->email);
	}

	public function readObject($in) {
		parent::readObject(in);
		$this->username = $in->readString();
		$this->password = $in->readString();
		$this->email = $in->readString();

		if ($this->MessageType != ParauroaMessageType::C2S_CREATEACCOUNT) {
			// TODO: handle error
		}
	}
}