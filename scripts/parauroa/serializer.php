<?php

class ParauroaSerializer {
	
	private $data;
	
	public function writeByte($byte) {
		$data = $data . chr($byte);
	}

	public function writeInt($int) {
		$data = $data . pack("N", $int);
	}

	public function writeString($string) {
		$this->writeInt(strlen($string));
		$data = $data . $string;
	}

	public function getData() {
		return $data;
	}
}