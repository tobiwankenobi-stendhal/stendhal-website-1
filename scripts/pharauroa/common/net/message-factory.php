<?php

class PharauroaMessageFactory {
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
		$deserializer = new PharauroaDeserializer($data);
		$type = ord($data[1]);
		if ($type == PharauroaMessageType::S2C_CREATEACCOUNT_ACK) {
			echo "ACK";
			// TODO
		} else if ($type == PharauroaMessageType::S2C_CREATEACCOUNT_NACK) {
			echo "NACK";
			$message = new PharauroaMessageS2CCreateAccountNACK();
		} else {
			// TODO
		}
		$message->readObject($deserializer);
		var_dump($message);
	}

}

?>