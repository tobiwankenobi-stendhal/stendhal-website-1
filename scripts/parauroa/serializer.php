<?php

class ParauroaSerializer {
	
	private $data;
	
	public function writeByte($byte) {
		$data = $data . chr($byte);
	}

	public function writeInt($int) {
		$data = $data . pack($int, "N");
	}

	public function writeString($string) {
		writeInt(strlen($string));
		$data = $data . $string;
	}

	public function getData() {
		return $data;
	}
}