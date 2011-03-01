<?php 
class AchievementPage extends Page {
	private $achievements;

	public function __construct() {
		if ($_REQUEST['name']) {
			$this->achievements = Achievement::getAchievement($_REQUEST['name']);
		} else {
			$this->achievements = Achievement::getAchievements();
		}
	}

	public function writeHttpHeader() {
		if ($_REQUEST['name'] && count($this->achievements)==0) {
			header('HTTP/1.0 404 Not Found');
			return true;
		}
		return true;
	}

	public function writeHtmlHeader() {
		echo '<title>Achievements'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		if ($_REQUEST['name']) {
			if (count($this->achievements)==0) {
				startBox('Achievement');
				echo 'Achievement not found.';
				endBox();
			} else {
				$this->achievementDetail();
			}
		} else {
			$this->achievementList();
		}
	}

	function achievementDetail() {
		startBox("Achievement");
		echo '<table class="prettytable">';
			echo '<tr>';
			echo '<td><img src="/images/achievements/'.htmlspecialchars(strtolower($this->achievements->category)).'.png" title="'.htmlspecialchars($this->achievements->category).'"></td>';
			echo '<td><abbr title="'.htmlspecialchars($this->achievements->description).'">'.htmlspecialchars($this->achievements->title).'</abbr></td>';
			echo '<td>'.htmlspecialchars($this->achievements->count).'</td>';
			echo '</tr>';
		echo '</table>';
		endBox();
	}

	function achievementList() {
		startBox("Achievements");
		echo '<table class="prettytable">';
		foreach ($this->achievements as $achievement) {
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