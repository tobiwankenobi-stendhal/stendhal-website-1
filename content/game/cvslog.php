<?php 

class CVSLogPage extends Page {

	public function writeHtmlHeader() {
		$month = $_GET['month'];
		if (!isset($month)) {
			echo '<title>Source Code Changes in CVS'.STENDHAL_TITLE.'</title>';
		} else {
			if (preg_match("/^\d\d\d\d-\d\d$/", $month)) {
				echo '<title>Source Code Changes in CVS in '.$month.STENDHAL_TITLE.'</title>';
			} else {
				echo '<title>Source Code Changes in CVS'.STENDHAL_TITLE.'</title>';
				echo '<meta name="robots" content="noindex">'."\n";
			}
		}
	}

	function writeContent() {
startBox("CVS"); ?>
<p>The Arianne project is hosted on <a href="http://sourceforge.net/projects/arianne">Sourceforge</a> and uses CVS (Concurrent Versions System) to manage changes to our source code. </p> 

<p>You can use a CVS client to <a href="http://sourceforge.net/scm/?type=cvs&group_id=1111">download our source code</a> or you can browse the <a href="http://arianne.cvs.sourceforge.net/arianne">web-based CVS repository</a>. Recent changes to CVS are recorded below. </p>
<?php endBox() ?>
<?php startBox("Recent Development"); ?>
<?php
	$directory = CVS_LOG_DIRECTORY;

	$month = $_GET['month'];
	if (isset($month) && preg_match("/^\d\d\d\d-\d\d$/", $month)) {
?>
	<p>
		<a href="<?php echo rewriteURL('/development/cvslog.html')?>">Index of logs</a>
	</p>

	<p>Timestamps are in server time.</p>

	<ul class="cvs">

<?php
for ($day = 1; $day <= 31; $day++) {

	$daystr = $day;
	if ($day < 10) {
		$daystr = '0'.$day;
	}

	$filename = $directory.$month . '-' . $daystr . ".log";
	if (is_file($filename)) {
		$lines = explode("\n", file_get_contents($filename));
		$res = '';
		for ($i = 0; $i < count($lines); $i++) {
			$line = $lines[$i];

			## make it pretty, yes this code is ugly.
			if ((strpos($line, '< CIA-') > 0) && (strpos($line, '> arianne_rpg: ') > 0)) {
				
				if (preg_match('/^[0-9][0-9]:[0-9][0-9] < CIA-.*> arianne_rpg: [^ ]*( [^ ]*)? \*/', $line)) {
					if ($res != '') {
						echo '<li>' . $res . "</span></li>\n";
					}
				
	
					$line = htmlspecialchars($line);
					$time = '<span class="cvstime">' . $month . '-' . $daystr . ' ' . substr($line, 0, 5) . '</span>';
	
					$pos = strpos($line, 'arianne_rpg: ');
					$line = substr($line, $pos + 13);
					$pos = strpos($line, ' ');
					$user = '<span class="cvsuser">' . substr($line, 0, $pos) . '</span>';
	
					$line = substr($line, $pos + 1);
					$pos = strpos($line, ' ');
					if (substr($line, 0, $pos) != '*') {
						$branch = '<span class="cvsbranch">&nbsp;' . substr($line, 0, $pos) . '&nbsp;</span>';
						$pos = $pos + 2;
					} else {
						$branch = '';
					}
	
					$line = substr($line, $pos + 1);
					$pos = strpos($line, '/');
					$module = '<span class="cvsmodule">' . substr($line, 0, $pos) . '</span>';
	
					$line = substr($line, $pos + 1);
					$pos = strpos($line, ':');
					$files = '<span class="cvsfiles">' . substr($line, 0, $pos) . '</span>';
	
					$commit = '<span class="cvscommit">' . substr($line, $pos + 1);
	
					$res = $time .' '. $user .' '. $branch .' '. $module .' '. $files .':<br>'. $commit;
				} else {
					$pos = strpos($line, 'arianne_rpg: ');
					$res = $res . substr($line, $pos + 12);  
				}
			}
		} // for

		if ($res != '') {
			echo '<li>' . $res . "</li>\n";
		}

	}
}

?>
	</ul>

<?php
	} else {
		$dir = opendir($directory);
		while (false !== ($file = readdir($dir))) {
			if (strpos($file, ".log") == 10) {
				$filearray[] = $file;
			}
		}
		closedir($dir);
		rsort($filearray);
?>
	<ul>
<?php


		$last = '';
		foreach ($filearray as $file) {
			$month = substr($file, 0, 7);
			if ($month != $last) {
				echo '<li><a href="'.rewriteURL('/development/cvslog/'.htmlspecialchars($month).'.html').'">' . $month . '</a></li>';
				$last = $month;
			}
		}
?>
	</ul>
<?php
}

endBox();

	}
}
$page = new CVSLogPage();
?>
