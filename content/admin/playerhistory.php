<?php
	if(getAdminLevel()<100) {
 		die("Ooops!");
	}
	$name=$_REQUEST["name"];
	startBox('Search Player');
?>
	<form method="get" action="">
		<input type="hidden" name="id" value="content/admin/playerhistory">
		<input type="text" name="name" maxlength="60" value="<?php if (isset($name)) {echo htmlspecialchars($name);}?>">
		<input type="submit" name="sublogin" value="Search">
	</form>
<?php
	endBox(); 

	if (isset($name)) {
		startBox('History for Player '. htmlspecialchars($name));

		echo '<table class="prettytable"><tr><th>time</th><th>source</th><th>event</th><th>parameters</th></tr>';

		$history = PlayerHistoryEntry::getPlayerHistoryEntriesForPlayer($name);
		foreach ($history as $entry) {
			echo '<tr><td>'.htmlspecialchars($entry->timedate).'</td><td>'.htmlspecialchars($entry->source)
				.'</td><td>'.htmlspecialchars($entry->event).'</td><td>'.htmlspecialchars($entry->param).'</td></tr>';
		}
		echo '</table>';
		echo '<p><b>These logs are for administrators eyes ONLY and should not be copied or pasted to others.</b></p>';

		endBox(); 
	}
?>