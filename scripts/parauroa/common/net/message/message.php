<?php
abstract class ParauroaMessage {

	/** Type of the message */
	protected $messageType;

	/** Clientid of the player that generated the message */
	protected $clientid = PARAUROA_CLIENTID_INVALID;

	/** Timestamp about when the message was created */
	protected $timestampMessage;

	/** version of this message */
	protected $protocolVersion = PARAUROA_NETWORK_PROTOCOL_VERSION;


	public function __construct($messageType) {
		$this->messageType = $messageType;
	}

	/**
	 * Serialize the object into an ObjectOutput
	 *
	 * @param out ParauroaSerializer the output serializer.
	 */
	public function writeObject(&$out) {
		$out->writeByte($this->protocolVersion);
		$out->writeByte($this->messageType);
		$out->writeInt($this->clientid);
		$out->writeInt($this->timestampMessage);
	}

	/**
	 * Serialize the object from an ObjectInput
	 *
	 * @param in ParauroaDeserializer the input deserializer
	 */
	public function readObject(&$in) {
		$this->protocolVersion = $in->readByte();
		// TODO: check if version is supported
		$this->messageType = $in->readByte();
		$this->clientid = $in->readInt();
		$this->timestampMessage = $in->readInt();
	}
}