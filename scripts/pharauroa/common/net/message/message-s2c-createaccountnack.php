<?php 

class PharauroaMessageS2CCreateAccountNACK extends PharauroaMessage{

	/** Desired username */
	private $reason;

	/** Constructor for allowing creation of an empty message */
	public function __construct() {
		parent::__construct(PharauroaMessageType::S2C_CREATEACCOUNT_NACK);
	}

	/**
	 * Returns the reason
	 *
	 * @return loginEvents
	 */
	public function getReason() {
		return $this->reason;
	}


	public function writeObject(&$out) {
		parent::writeObject($out);
		$out->writeByte(($this->reason));
	}

	public function readObject(&$in) {
		parent::readObject($in);
		$this->reason = $in->readByte();

		if ($this->MessageType != PharauroaMessageType::S2C_LOGIN_ACK) {
			// handle error
		}
	}

}
