<?php
require_once('scripts/account.php');

class AccountTest extends PHPUnit_Framework_TestCase {
	
	public function testConvertToValidUsername() {
		$this->assertEquals(null, Account::convertToValidUsername(null));
		$this->assertEquals(null, Account::convertToValidUsername(''));
		$this->assertEquals(null, Account::convertToValidUsername('#'));
		$this->assertEquals('hello', Account::convertToValidUsername(' h-e#l+l_o '));
		$this->assertEquals('hhhhhh', Account::convertToValidUsername('94320480h32'));
		$this->assertEquals('qwertz', Account::convertToValidUsername('qwertz'));
	}
}