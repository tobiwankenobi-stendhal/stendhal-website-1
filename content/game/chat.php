<?php 

class ChatPage extends Page {
	function writeContent() {
startBox("Chat to other users and developers"); ?>
You can get an IRC client and connect to:

<p><b>irc.freenode.net</b> (see <a href="http://freenode.net">http://freenode.net</a> for more information) then
<ul> 
<li><b>#arianne-chat</b> which is for friendly chatter </li>
<li> <b>#arianne</b> which is for technical discussion, getting help and planning.</li>
</ul>
<p>
Alternatively, you can simply use freenode's webchat service, below. Feel free to change the nick to for example, your player name. 
<ul> 
<li>
<a href="http://webchat.freenode.net/?channels=arianne-chat">#arianne-chat</a></li>
<li>
<a href="http://webchat.freenode.net/?channels=arianne">#arianne</a></li>
</ul>
If you are new to IRC it is well worth reading this <a href="http://www.irchelp.org/irchelp/new2irc.html">short guide</a> before you join. In particular the section on talking, and entering commands, and the section 'Some advice' may be helpful.
<?php endBox(); ?>
<?php
 	startBox(MAIN_CHANNEL . ' IRC log');
    $directory = MAIN_LOG_DIRECTORY;

    $date = $_GET['date'];
    if (isset($date) && preg_match("/^\d\d\d\d-\d\d-\d\d$/", $date)) {
?>
    <p>
     <a href="./?id=content/game/chat">Index of logs</a>
    </p>

    <h2><?php echo(MAIN_CHANNEL); ?> IRC Log for <?php echo($date); ?></h2>
    <p>
     Timestamps are in server time.
    </p>
    <p>
    
<?php
$lines = explode("\n", file_get_contents($directory.$date . ".log"));
for ($i = 0; $i < count($lines); $i++) {
	$line = $lines[$i];
	
	## make it pretty, yes this code is ugly.
	$class = "irctext";
	if (substr($line, 5, 5) == ' -!- ') {
		$class = "ircstatus";
	} else if (substr($line, 5, 16) == ' < postman-bot> ') {

		if (substr($line, 21, 22) == 'Administrator SHOUTS: ') {
			$class = "ircshout";
		} else if (strpos($line, 'rented a sign saying') > 10) {
			$class = "ircsign";
		}
	} 
	
	echo '<span class="'.$class.'">'.htmlspecialchars($line).'</span><br>'."\n";
}

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
	 <li><a href="<?php echo($_SERVER['PHP_SELF'] . "?id=content/game/chat&amp;date=" . $file); ?>"><?php echo($file); ?></a></li>

<?php
        }
?>
    </ul>
<?php
    }

?>

<p>
 These logs of  <?php echo(MAIN_CHANNEL); ?> were automatically created on
 <a href="irc://<?php echo(MAIN_SERVER . "/" . substr(MAIN_CHANNEL, 1)); ?>"><?php echo(MAIN_SERVER); ?></a>
</p>
<?php
          endBox();
	}
}
$page = new ChatPage();
?>