<?php 

class DocumentPage extends Page {
	private $filename;
	private $justFilename;
	private $relativeFilename;
	
	public function __construct() {
		$file = '';
		if (isset($_REQUEST['file'])) {
			$file = $_REQUEST['file'];
		}
		if ((strpos($file, '..') !== false) || (strpos($file, ':') !== false)) {
			return;
		}
		$this->relativeFilename = $file;
		$pos = strrpos($file, '/');
		$this->justFilename = substr($file, $pos);
		$this->filename = STENDHAL_DOCUMENT_DIRECTORY.'/'.$file;
	}

	public function writeHttpHeader() {
		if (!isset($this->filename)) {
			header('HTTP/1.0 404 Not found.');
			return true;
		}

		if (!isset($_SESSION) || !isset($_SESSION['account'])) {
			header('HTTP/1.0 403 Forbidden.');
			return true;
		}

		if (!is_dir($this->filename)) {
			$this->streamFile();
			return false;
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Documents'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}

	function writeContent() {
		startBox(t('Documents'));

		if (!isset($_SESSION) || !isset($_SESSION['account'])) {
			echo '<p>'.t('Sorry, internal documents are only available to members.').'</p>';
		} else if (is_dir($this->filename)) {
			$this->renderDirectory();
		} else { // files are handled in writeHttpHeader
			echo '<p>'.t('Sorry, the requested document was not found.').'</p>';
		}
		endBox();
	}

	/**
	 * writes a directory listing
	 */
	function renderDirectory() {
		global $lang;
		$dir = opendir($this->filename);
		while (false !== ($file = readdir($dir))) {
			if (strpos($file, '.') !== 0) {
				$files[] = $file;
			}
		}
		closedir($dir);
		sort($files);

		echo '<p><b>'.t('These are the internal documents of the association.').'</b></p>';
		echo '<ul>';
		foreach ($files as $file) {
			// TODO: Link with correct folder prefix
			echo '<li><a href="'.rewriteURL('/'.$lang.'/documents'.htmlspecialchars($this->relativeFilename.'/'.$file)).'">'.htmlspecialchars($file).'</a></li>';
		}
		echo '</ul>';
	}

	/**
	 * streams a file
	 */
	function streamFile() {

		// content type
		$pos = strrpos($this->filename, '.');
		$ext = strtolower(substr($this->filename, $pos + 1));
		$mimeType = 'application/octet-stream';
		if ($ext == 'jpg' || $ext == 'jpeg') {
			$mimeType = 'image/jpeg';
		} else if ($ext == 'png') {
			$mimeType = 'image/png';
		} else if ($ext == 'txt') {
			$mimeType = 'text/plain';
		} else if ($ext == 'html') {
			$mimeType = 'text/html';
		} else if ($ext == 'pdf') {
			$mimeType = 'application/pdf';
		}

		header('Content-Type: '.$mimeType);
		header('Content-Disposition: atachment; filename='.$this->justFilename);
		header('Content-Length: '.filesize($this->filename));
		$file = fopen($this->filename, 'r');
		echo fread($file, filesize($this->filename));
		fclose($file);
	}
}

$page = new DocumentPage();
?>