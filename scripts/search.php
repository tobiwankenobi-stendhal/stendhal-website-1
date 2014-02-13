<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2014   Faiumoni e. V.

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

/**
 * searches for results across multiple entity types
 *
 * @author hendrik
 */
class Searcher {
	private $terms;

	function __construct($searchTerm) {
		echo $searchTerm; 
		$this->terms = preg_split("/[\s,]+/", strtolower($searchTerm), -1, PREG_SPLIT_NO_EMPTY);
	}

	function search() {
		$results = array();
		if (count($this->terms) == 0) {
			return $results;
		}
		array_push($results, $this->searchAchievements());
		array_push($results, $this->searchItem());
		array_push($results, $this->searchNPC());
		array_push($results, $this->searchCreature());
		array_push($results, $this->searchCharacter());
// 		array_push($results, $this->searchZone());
		array_push($results, $this->searchGuide());
		return $results;
	}

	function generateWhere($columns) {
		$query = ' (';
		$firstColumn = true;
		foreach ($colums as $column) {
			$first = true;
			$query .= ' (';
			foreach ($this->terms as $term) {
				if ($first) {
					$first = false;
				} else {
					$query .= ' AND ';
				}
				$query .= $column . " LIKE '%". mysql_real_escape_string($term) . "%'";
			}
			$query .= ')';
		}
		$query .= ') ';
		return $query;
	}
	
	function searchAchievements() {
		$where = ' AND (' . $this->generateWhere('achievement.title')
			. ' OR '. $this->generateWhere('achievement.description') . ')';
		return Achievement::getAchievements($where);
	}

	function searchCharacter() {
		
	}

	function searchCreature() {
		
	}

	function searchGuide() {
	
	}

	function searchItem() {
		
	}

	function searchNPC() {
		
	}

}