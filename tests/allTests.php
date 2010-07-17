<?php
require_once 'PHPUnit/Framework.php';

require_once 'tests/scripts/pharauroa/common/net/deserializerTest.php';


class AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('PHPUnit');

		$suite->addTestSuite('PharauroaDeserializerTest');

		return $suite;
	}
}
?>