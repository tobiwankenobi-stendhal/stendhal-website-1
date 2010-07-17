<?php

class PharauroaDeserializer {
	// Fields
	private $data;
	private $protocolVersion = PHARAUROA_NETWORK_PROTOCOL_VERSION;

	// Constructors
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * sets the protocol version
	 *
	 * @param $protocolVersion version of protocol
	 */
	public function setProtocolVersion($protocolVersion) {
		$this->$protocolVersion = min($protocolVersion, PHARAUROA_NETWORK_PROTOCOL_VERSION);
	}

	/**
	 * gets the protocol version
	 *
	 * @return $protocolVersion version of protocol
	 */
	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	/**
	 * This method reads a byte
	 *
	 * @return the byte 
	 */
	public function readByte() {
		$output = ord($this->data[0]);
		$this->data = substr($this->data, 1);
		return $output;
	}

	/**
	 * This method reads an integer
	 *
	 * @return the integer
	 */
	public function readInt() {
		$output = unpack("I", $this->data);
		$this->data = substr($this->data, 4);
		return $output[1]; 
	}

	/**
	 * This method reads a long string
	 *
	 * @return the string
	 */
		public function readString() {
		$length = $this->readInt();
		$output = substr($this->data, 0, $length);
		$this->data = substr($this->data, $length);
		return $output;
	}

	/**
	 * This method reads a short string
	 *
	 * @return the string
	 */
	public function read255LongString() {
		$length = $this->readByte();
		$output = substr($this->data, 0, $length);
		$this->data = substr($this->data, $length);
		return $output;
	}
}
?>