<?php
require_once 'PHPUnit/Framework.php';

require_once 'tests/scripts/pharauroa/common/net/deserializerTest.php';
require_once 'tests/mediawiki/rdfa-breadcrumbsTest.php';


class AllTests {
	public static function suite() {
		$suite = new PHPUnit_Framework_TestSuite('PHPUnit');

		$suite->addTestSuite('PharauroaDeserializerTest');
		$suite->addTestSuite('RDFaBreadcrumbsTest');
		
		return $suite;
	}
}
?>