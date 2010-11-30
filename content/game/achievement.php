<?php 
class AchievementPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Achievements'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		startBox("Achievement");
		$list = Achievement::getAchievements();
		var_dump($list);
		endBox();
	}
}
$page = new AchievementPage();
?>