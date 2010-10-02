<?php 

class DownloadPage extends Page {
	public function writeHtmlHeader() {
		echo '<title>Downloads'.STENDHAL_TITLE.'</title>';
		echo '<meta name="robots" content="noindex">'."\n";
	}
	
	function writeContent() {
		startBox("Download");
		?>
		<p><b>These is the most recent development snapshots.</b> 
		They are good for testing. You can download the release at
		<a href="http://sourceforge.net/projects/arianne/files/">http://sourceforge.net/projects/arianne/files/</a>.</p>
		<div style="margin-left: 3em">
		<?php
		$dir = opendir('download');
		while (false !== ($file = readdir($dir))) {
			if (strpos($file, '.') !== 0) {
				echo '<li><a href="/download/'.htmlspecialchars($file).'">'.htmlspecialchars($file).'</a></li>';
			}
		}
		closedir($dir);
		?>
		</div>
		<?php
		endBox();
	}
}

$page = new DownloadPage();
?>