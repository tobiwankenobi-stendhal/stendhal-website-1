<?php

class PharauroaDeserializer {
	
	private $data;
	private $protocolVersion = PHARAUROA_NETWORK_PROTOCOL_VERSION;

	public function setProtocolVersion($protocolVersion) {
		$this->$protocolVersion = min($protocolVersion, PHARAUROA_NETWORK_PROTOCOL_VERSION);
	}

	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	public function __construct($data) {
		$this->data = $data;
	}
	
	public function readByte($byte) {
		return 0; // TODO
	}

	public function readerInt($int) {
		return 0; // TODO
	}
}