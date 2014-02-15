<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2014 Faiumoni e. V.

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class SearchsPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Search'.STENDHAL_TITLE.'</title>';

		?>
		<style type="text/css">
		.searchform {margin: 1em}
		.searchresults {margin: 0 0 2em 1em}
		.searchentry {margin: 0 0 1em 0}
		.searchheader {font-weight: bold; padding: 0 0 0.2em 0}
		.searchtype {color: #777}
		.searchicon {float: left; padding: 0.1em .5em 0 0}
		</style>
		<?php
	}

	function writeContent() {
		startBox('Search');
		$this->writeSearchForm();
		$this->writeSearchResult();
		endBox();
	}

	function writeSearchForm() {
		echo '<form class="searchform" action="'.rewriteURL('/search').'" method="GET">';
		if (!STENDHAL_MODE_REWRITE) {
			echo '<input type="hidden" name="id" value="content/game/search">';
		}
		echo '<label for="q">Search: </label><input name="q" id="q"';
		if (isset($_REQUEST['q'])) {
			echo ' value="'.htmlspecialchars($_REQUEST['q']).'"';
		}
		echo '><input type="submit" value="Search"></form>';
	}

	function writeSearchResult() {
		$searcher = new Searcher($_REQUEST['q']);
		$rows = $searcher->search();

		$known = array();
		
		echo '<div class="searchresults">';
		
		foreach ($rows As $row) {
		
			// filter duplicated entries
			$key = $row['entitytype'].$row['entityname'];
			if (isset($known[$key])) {
				continue;
			}
			$known[$key] = 1;
		
			// display result
			$this->render($row);
		}
		echo '</div>';
		
		$known = array();

		echo '<table class="prettytable"><tr><th>T</th><th>Name</th><th>score</th></tr>';
		foreach ($rows As $row) {

			// filter duplicated entries
			$key = $row['entitytype'].$row['entityname'];
			if (isset($known[$key])) {
				continue;
			}
			$known[$key] = 1;

			// display result
			echo '<tr><td>'.htmlspecialchars($row['entitytype']);
			echo '</td><td>'.htmlspecialchars($row['entityname']);
			echo '</td><td>'.htmlspecialchars($row['score']);
			echo '</td></tr>';
		}
		echo '</table>';
	}

	function render($row) {
		switch ($row['entitytype']) {
			case 'A': {
				$this->renderAchievement($row['entityname']);
				break;
			}
		}
	}

	function renderAchievement($name) {
		$achievement = Achievement::getAchievement($name);
		echo '<div class="searchentry">';
		echo '<div class="searchheader"><a href="'.rewriteURL('/achievement/'.surlencode($achievement->title).'.html')
			.'">'.htmlspecialchars($achievement->title).'</a></div>';
		echo '<img class="searchicon" src="/images/achievements/'.htmlspecialchars(strtolower($achievement->category)).'.png" alt="">';
		echo '<div class="searchtype">Achievement</div>';
		echo '<div class="searchdescr">'.htmlspecialchars($achievement->description).'</div>';
		echo '</div>';
	}
}
$page = new SearchsPage();
