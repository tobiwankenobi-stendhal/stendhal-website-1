<?php
require_once('scripts/website.php');

/**
* Tests for website
*
* @author hendrik
*/
class WikiTest extends PHPUnit_Framework_TestCase {

	public function testRewriteImageLinks() {
		$content = 'x<a href="/wiki/File:Semos.png" class="image"><img alt="Semos.png" src="/wiki/images/thumb/f/f0/Semos.png/300px-Semos.png" height="266" width="300"></a>y<a href="xxx">b</<a>';
		$expected = 'x<a href="/wiki/images/f/f0/Semos.png" class="image fancybox"><img alt="Semos.png" src="/wiki/images/thumb/f/f0/Semos.png/300px-Semos.png" height="266" width="300"></a>y<a href="xxx">b</<a>';
		$res = Wiki::rewriteImageLinks($content);
		$this->assertEquals($expected, $res);

		$content = 'y<a href="/wiki/File:Semos.png" class="image"><img alt="Semos.png" src="/wiki/images/f/f0/Semos.png/300px-Semos.png" height="266" width="300"></a>y<a href="xxx">b</<a>';
		$expected = 'y<a href="/wiki/images/f/f0/Semos.png" class="image fancybox"><img alt="Semos.png" src="/wiki/images/f/f0/Semos.png/300px-Semos.png" height="266" width="300"></a>y<a href="xxx">b</<a>';
		$res = Wiki::rewriteImageLinks($content);
		$this->assertEquals($expected, $res);
	}
}

