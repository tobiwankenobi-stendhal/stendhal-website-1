<?php 
class AchievementPage extends Page {
	private $achievements;

	public function __construct() {
		if ($_REQUEST['name']) {
			$this->achievements = Achievement::getAchievement(preg_replace('/_/', ' ', $_REQUEST['name']));
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
		if (count($this->achievements) == 1) {
			echo '<title>Achievement '.$this->achievements->title.STENDHAL_TITLE.'</title>';
		} else {
			echo '<title>Achievements'.STENDHAL_TITLE.'</title>';
		}
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
		echo '<div class="achievement">';
		echo '<div class="name">'.htmlspecialchars($this->achievements->title).'</div>';
		echo '<img class="achievement" src="/images/achievements/'.htmlspecialchars(strtolower($this->achievements->category)).'.png" alt="">';
		echo '<div class="description">'.htmlspecialchars($this->achievements->description).'</div>';
		echo '</div>';
		echo 'Earned by '.htmlspecialchars($this->achievements->count). ' characters.';
		endBox();

		startBox("Recently awarded to");
		$list = Achievement::getAwardedTo($this->achievements->id);
		if (count($list) == 0) {
			echo 'No character has earned this achievement, yet. Be the first!';
		} else {
			foreach ($list as $entry) {
				echo '<img src="'.rewriteURL('/images/outfit/'.urlencode($entry[1]).'.png').'"> '.htmlspecialchars($entry[0]).'<br>';
			}
		}
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