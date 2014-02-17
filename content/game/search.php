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


function searchScoreCallback($a, $b) {
	if (abs($a['score']) == abs($b['score'])) {
		return 0;
	}
	return (abs($a['score']) < abs($b['score'])) ? 1 : -1;
}

class SearchsPage extends Page {

	public function writeHtmlHeader() {
		$q = '';
		if (isset($_REQUEST['q'])) {
			$q = $_REQUEST['q'];
		}
		echo '<title>Search '.htmlspecialchars($q).STENDHAL_TITLE.'</title>';

		?>
		<style type="text/css">
		.searchform {margin: 1em}
		.searchresults {margin: 0 0 2em 1em}
		.searchentry {margin: 0 0 1.5em 0;}
		.searchheader {font-weight: bold; padding: 0 0 0.2em 0}
		.searchtype {color: #777}
		.searchimagecontainer {float: left; width: 48px; height: 3em}
		.searchicon {max-height: 48px}
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

		// the database resutls are pre sorted within each query, but we still need to "merge-sort" the results from multiple queries
		usort($rows, searchScoreCallback);

		$known = array();
		$filteredResults = false;
		
		echo '<div class="searchresults">';
		
		foreach ($rows As $row) {
		
			// filter duplicated entries
			$key = $row['entitytype'].$row['entityname'];
			if (isset($known[$key])) {
				continue;
			}
			$known[$key] = 1;

			// filter irrelevant
			if ($row['score'] < 0) {
				// we intentionally check for POST here
				if (!isset($_POST['disablefilter'])) {
					$filteredResults = true;
					continue;
				}
			}

			// display result
			$this->render($row);
		}
		echo '</div>';

		if ($filteredResults) {
			echo '<form action="'.rewriteURL('/search/?q='.urlencode($_REQUEST['q'])).'" method="POST">';
			echo 'Some inappropriate results have been filtered. <input type="submit" name="disablefilter" id="disablefilter" value="Disable Filter"></form>';
		}
	}


	function render($row) {
		switch ($row['entitytype']) {
			case 'A': {
				$this->renderAchievement($row['entityname']);
				break;
			}
			case 'C': {
				$this->renderCreature($row['entityname']);
				break;
			}
			case 'G': {
				$this->renderWiki($row['entitytype'], $row['entityname']);
				break;
			}
			case 'I': {
				$this->renderItem($row['entityname']);
				break;
			}
			case 'N': {
				$this->renderNpc($row['entityname']);
				break;
			}
			case 'P': {
				$this->renderPlayer($row['entityname']);
				break;
			}
			case 'W': {
				$this->renderWiki($row['entitytype'], $row['entityname']);
				break;
			}
		}
	}

	function renderEntry($name, $type, $url, $icon, $description) {
		echo '<div class="searchentry">';
		echo '<div class="searchheader"><a href="'.rewriteURL($url.surlencode($name).'.html')
		.'">'.htmlspecialchars(ucfirst($name)).'</a></div>';
		echo '<div class="searchimagecontainer"><img class="searchicon" src="'.htmlspecialchars($icon).'" alt=""></div>';
		echo '<div class="searchtype">'.htmlspecialchars($type).'</div>';
		echo '<div class="searchdescr">';
		if (isset($description) && $description != '') {
			echo htmlspecialchars($description);
		} else {
			echo '&nbsp;';
		}
		echo '</div></div>';
	}

	function renderAchievement($name) {
		$entry = Achievement::getAchievement($name);
		$this->renderEntry($name, 
			'Achievement – Earned: '.$achievements->count, 
			'/achievement/', 
			'/images/achievements/'.htmlspecialchars(strtolower($entry->category)).'.png', 
			$entry->description);
	}

	function renderCreature($name) {
		$entry = getMonster($name);
		$this->renderEntry($name,
			'Creature – Level: '.$entry->level,
			'/creature/',
			$entry->gfx,
			$entry->description);
	}

	function renderItem($name) {
		$entry = getItem($name);
		$this->renderEntry($name,
				'Item – '.ucfirst($entry->class),
				'/item/'.surlencode($entry->class).'/',
				$entry->gfx,
				$entry->description);
	}

	function renderNpc($name) {
		$entry = NPC::getNpc($name);
		if ($entry->pos != '') {
			$pos = $entry->zone.' '.$entry->pos;
		} else {
			$pos = $npc->zone;
		}
		$this->renderEntry($name,
			'NPC – '.$pos,
			'/npc/',
			$entry->imagefile,
			$entry->description);
	}

	function renderPlayer($name) {
		$entry = getPlayer($name);
		$this->renderEntry($name,
			'Character – Level: '.$entry->level,
			'/character/',
			'/images/outfit/'.surlencode($entry->outfit).'.png',
			$entry->sentence);
	}

	function renderWiki($entitytype, $name) {
		$type = 'Wiki page';
		if ($entitytype == 'G') {
			$type = "Player's guide";
		} else if ($entitytype == 'W') {
			$type = "World guide";
		}
		$this->renderEntry($name,
			$type,
			'/wiki/',
			'/images/item/documents/paper.png',
			'');
	}
}
$page = new SearchsPage();
