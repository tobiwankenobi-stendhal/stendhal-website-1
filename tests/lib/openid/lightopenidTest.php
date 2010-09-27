<?php
require_once('lib/openid/lightopenid.php');

class LightOpenidTest extends PHPUnit_Framework_TestCase {

	public function testDoesServerExist() {
		$openid = new LightOpenID();
		$this->assertTrue($openid->doesServerExist("localhost"));
		$this->assertTrue($openid->doesServerExist("www.google.com"));
		$this->assertTrue($openid->doesServerExist("ip6-loopback"));
		$this->assertTrue($openid->doesServerExist("127.0.0.1"));
		$this->assertFalse($openid->doesServerExist("not.exists"));

		$this->assertTrue($openid->doesServerExist("http://me.yahoo.com"));
		$this->assertTrue($openid->doesServerExist("http://me.yahoo.com/"));
		$this->assertTrue($openid->doesServerExist("http://me.yahoo.com:80"));
		$this->assertTrue($openid->doesServerExist("http://me.yahoo.com:80/"));

		$this->assertFalse($openid->doesServerExist("http://not.exists"));
		$this->assertFalse($openid->doesServerExist("http://not.exists/"));
		$this->assertFalse($openid->doesServerExist("http://not.exists:80/"));
	}
}