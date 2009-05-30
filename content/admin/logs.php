<?php
	if(getAdminLevel()<100) {
 		die("Ooops!");
	}
    $directory = SUPPORT_LOG_DIRECTORY;

    include("header.inc.php");


    $date = $_GET['date'];
    if (isset($date) && preg_match("/^\d\d\d\d-\d\d-\d\d$/", $date)) {
?>

    <p>
     <a href="./?id=content/admin/logs">Index of logs</a>
    </p>

    <h2>IRC Log for <?php echo($date); ?></h2>
    <p>
     Timestamps are in server time.
    </p>
    <p>
    
<?php
echo str_replace(array("\r\n", "\n", "\r"),'<br>', htmlspecialchars(file_get_contents($directory.$date . ".log")));

?>
    </p>
<?php
    }
    else {
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
        
        
        foreach ($filearray as $file) {
            $file = substr($file, 0, 10);
?>
	 <li><a href="<?php echo($_SERVER['PHP_SELF'] . "?id=content/admin/logs&amp;date=" . $file); ?>"><?php echo($file); ?></a></li>

<?php
        }
?>
    </ul>
<?php
    }

    include("footer.inc.php");

?>
