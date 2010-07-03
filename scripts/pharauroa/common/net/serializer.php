<?php

class PharauroaSerializer {
	
	private $data;
	
	public function writeByte($byte) {
		$this->data = $this->data . chr($byte);
	}

	public function writeInt($int) {
		$this->data = $this->data . pack("I", $int);
	}

	public function writeString($string) {
		$this->writeInt(strlen($string));
		$this->data = $this->data . $string;
	}

	public function write256LongString($string) {
		if (strlen($string) > 256) {
			// TODO
		}
		$this->writeByte(strlen($string));
		$this->data = $this->data . $string;
	}

	public function getData() {
		return pack("I", strlen($this->data) + 4).$this->data;
	}
}