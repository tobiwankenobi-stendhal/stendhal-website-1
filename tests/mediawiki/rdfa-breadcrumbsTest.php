<?php
require_once('mediawiki/rdfa-breadcrumbs.php');

class RDFaBreadcrumbsTest extends PHPUnit_Framework_TestCase {
	
	public function testGetURLFromLink() {
		$this->assertEquals($out_byte4, $in_byte4, 'Test case readByte() - 4');
	}

	public function testGetTextFromLink() {
		$this->assertEquals(getTextFromLink('Simple Link'), 'Simple Link', 'Simple Link with [[]]');
		$this->assertEquals(getTextFromLink('[[Simple Link]]'), 'Simple Link', 'Simple Link');
		$this->assertEquals(getTextFromLink('[[url|Link Text]]'), 'Link Text', 'Link with different text');
		$this->assertEquals(getTextFromLink('[http://example.com Link Text]'), 'Link Text', 'External Link');
	}
}
