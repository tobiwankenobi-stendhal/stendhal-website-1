<?php 
class AchievementPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Achievements'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		startBox("Achievement");
		$list = Achievement::getAchievements();
		echo '<table class="prettytable">';
		foreach ($list as $achievement) {
			echo '<tr>';
			echo '<td><img src="/images/achievements/'.htmlspecialchars(strtolower($achievement->category)).'.png" title="'.htmlspecialchars($achievement->category).'"></td>';
			echo '<td><abbr title="'.htmlspecialchars($achievement->description).'">'.htmlspecialchars($achievement->title).'</abbr></td>';
			echo '<td>'.htmlspecialchars($achievement->count).'</td>';
			echo '</tr>';
		}
		echo '</table>';
		endBox();
	}
}
$page = new AchievementPage();
?>