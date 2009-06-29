<?php startBox("Recent Development"); ?>
<?php
	$directory = CVS_LOG_DIRECTORY;

	$month = $_GET['month'];
	if (isset($month) && preg_match("/^\d\d\d\d-\d\d$/", $month)) {
?>
	<p>
		<a href="./?id=content/game/cvslog">Index of logs</a>
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
                for ($i = 0; $i < count($lines); $i++) {
                        $line = $lines[$i];

                        ## make it pretty, yes this code is ugly.
                        if ((strpos($line, '< CIA-') > 0) && (strpos($line, '> arianne_rpg: ') > 0)) {
                                $line = htmlspecialchars($line);
                                $time = '<span class="cvstime">' . $month . '-' . $daystr . ' ' . substr($line, 0, 5) . '</span>';

                                $pos = strpos($line, 'arianne_rpg: ');
                                $line = substr($line, $pos + 13);
                                $pos = strpos($line, ' ');
                                $user = '<span class="cvsuser">' . substr($line, 0, $pos) . '</span>';

                                $line = substr($line, $pos + 1);
                                $pos = strpos($line, ' ');
                                if (substr($line, 0, $pos) != '*') {
                                        $branch = '<span class="cvsbranch">' . substr($line, 0, $pos) . '</span>';
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

                                $commit = '<span class="cvscommit">' . substr($line, $pos + 1) . '</span>';

                                echo '<li>'
                                	. $time .' '. $user .' '. $branch .' '. $module .' '. $files .':<br>'. $commit
                                	."</li>\n";
                        }
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
				echo '<li><a href="' . $_SERVER['PHP_SELF'] . '?id=content/game/cvslog&amp;month=' . $month . '">' . $month . '</a></li>';
				$last = $month;
			}
		}
?>
	</ul>
<?php
}

?>


<?php endBox() ?>
