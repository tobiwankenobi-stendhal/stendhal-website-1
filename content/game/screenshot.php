<?php 

class ScreenshotPage extends Page {
	private $filename;
	private $title;

	function __construct() {
// SELECT page_title As filename, cl_sortkey_prefix As subtitle FROM categorylinks, page WHERE cl_to='Stendhal_Slideshow' AND cl_type='file' AND page_id=cl_from ORDER BY page_touched DESC, page_id DESC;
		$this->filename = 'Athor_pool.jpeg';
		$this->title = 'A A A';
	}

	public function writeHttpHeader() {
		echo '<!DOCTYPE html><html><head>';
		echo '<title>Screenshot '.$this->filename.STENDHAL_TITLE.'</title>';
		echo '</head><body>';
		echo '<img src="'.$this->getImageUrl($this->filename).'" alt="'.htmlspecialchars($this->title).'">';
		echo '<p>'.htmlspecialchars($this->title).'</p>';
		echo '</body></html>';
		return false;
	}

	function getImageUrl($filename) {
		$hash = md5($filename);
		return '/wiki/images/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . '/' . urlencode($filename);
	}
}
$page = new ScreenshotPage();
?>