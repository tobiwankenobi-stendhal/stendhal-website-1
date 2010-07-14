<?php

class PharauroaDeserializer {
	
	private $data;
	private $protocolVersion = PHARAUROA_NETWORK_PROTOCOL_VERSION;

	public function __constructor($data) {
		$this->data = $data;
	}

	public function setProtocolVersion($protocolVersion) {
		$this->$protocolVersion = min($protocolVersion, PHARAUROA_NETWORK_PROTOCOL_VERSION);
	}

	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	public function __construct($data) {
		$this->data = $data;
	}
	
	public function readByte() {
		// TODO opposite of: $this->data = $this->data . chr($byte);
		return 0; // TODO
	}

	public function readerInt() {
		// TODO opposite of: $this->data = $this->data . pack("I", $int);
		return 0; 
	}

	
	public function readString() {
		// TODO opposite of: $this->writeInt(strlen($string)); $this->data = $this->data . $string;
		return "";
	}

	public function write256LongString($string) {
		// TODO opposite of: $this->writeByte(strlen($string)); $this->data = $this->data . $string;
		return "";
	}
}