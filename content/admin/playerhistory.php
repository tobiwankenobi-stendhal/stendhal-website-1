<?php
	if(getAdminLevel()<100) {
 		die("Ooops!");
	}
	startBox('Player History');

	$name=$_REQUEST["name"];

	echo '<table class="prettytable"><tr><th>time</th><th>source</th><th>event</th><th>parameters</th></tr>';

	$history = PlayerHistoryEntry::getPlayerHistoryEntriesForPlayer($name);
	foreach ($history as $entry) {
		echo '<tr><td>'.$entry->timedate.'</td><td>'.$entry->source.'</td><td>'.$entry->event.'</td><td>'.$entry->param.'</td></tr>';
	}
	echo '</table>';
?>
<p><b>These logs are for administrators eyes ONLY and should not be copied or pasted to others.</b></p>
<?php
	endBox() 
?>