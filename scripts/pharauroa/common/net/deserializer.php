<?php

class PharauroaDeserializer {
	
	private $data;
	private $protocolVersion = PHARAUROA_NETWORK_PROTOCOL_VERSION;

	
	public function __construct($data) {
		$this->data = $data;
	}

	public function setProtocolVersion($protocolVersion) {
		$this->$protocolVersion = min($protocolVersion, PHARAUROA_NETWORK_PROTOCOL_VERSION);
	}

	public function getProtocolVersion() {
		return $this->protocolVersion;
	}
	public function readByte() {
		// TODO opposite of: $this->data = $this->data . chr($byte);
		$output = ord($this->data[0]);
		$this->data = substr($this->data, 1);
		return $output; // TODO
	}

	public function readInt() {
	/// I recommend you use "V" - unsigned long (always 32 bit, little endian byte order) - to keep endian ordering and size accross network
		// TODO opposite of: $this->data = $this->data . pack("I", $int);
		$output = unpack("V", $this->data);
		$this->data = substr($this->data, 4);
		return $output[0]; 
	}

	
	public function readString() {
		// TODO opposite of: $this->writeInt(strlen($string)); $this->data = $this->data . $string;
		$length = $this->readInt();
		$output = substr($this->data, 0, $length);
		$this->data = substr($this->data, $length);
		return $output;
	}

	public function write256LongString($string) {
		// TODO opposite of: $this->writeByte(strlen($string)); $this->data = $this->data . $string;
		$length = $this->readByte();
		$output = substr($this->data, 0, $length);
		$this->data = substr($this->data, $length);
		return $output;
	}
}
