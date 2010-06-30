<?php

class ParauroaMessageFactory {
	private $sock;

	function __construct($sock) {
		$this->sock = $sock;
	}

	public function readMessage() {
		$data = socket_read($this->sock, 4);
		$temp = unpack("I", $data);
		$size = $temp[1];
		$data = socket_read($this->sock, $size);

		// TODO: make sure that $data contains the complete message
		$deserializer = new ParauroaDeserializer($data);
		echo ord($data[1]);
	}

}

?>