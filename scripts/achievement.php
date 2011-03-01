<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2009-2010   Hendrik Brummermann

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
  * An achievement player can reach..
  */
class Achievement {
	public $id;
	public $identifier;
	public $title;
	public $category;
	public $base_score;
	public $description;
	public $count;
 
	function __construct($id, $identifier, $title, $category, $base_score, $description, $count) {
		$this->id = $id;
		$this->identifier = $identifier;
		$this->title = $title;
		$this->category = $category;
		$this->base_score = $base_score;
		$this->description = $description;
		$this->count = $count;
	}



	/**
	  * Returns a list of npcs that meet the given condition.
	  * Note: Parmaters must be sql escaped.
	  */
	public static function getAchievementForCharacter($charname) {
		$query = 'SELECT achievement.id, achievement.identifier, achievement.title, '
			. 'achievement.category, achievement.base_score, achievement.description, '
			. 'count(reached_achievement.charname) As cnt '
			. 'FROM achievement '
			. 'LEFT JOIN reached_achievement ON achievement.id = reached_achievement.achievement_id '
			. 'AND reached_achievement.charname = \''.mysql_real_escape_string($charname).'\''
			. 'GROUP BY achievement.id, achievement.identifier, achievement.title, '
			. 'achievement.category, achievement.base_score, achievement.description '
			. 'ORDER BY achievement.category, achievement.identifier';
		return Achievement::_getAchievements($query);
	}

	/**
	  * Returns a list of npcs that meet the given condition.
	  * Note: Parmaters must be sql escaped.
	  */
	public static function getAchievements($where='', $sortby='name', $cond='') {
		$query = 'SELECT achievement.id, achievement.identifier, achievement.title, '
			. 'achievement.category, achievement.base_score, achievement.description, '
			. 'count(character_stats.admin) As cnt '
			. 'FROM achievement '
			. 'LEFT JOIN reached_achievement ON achievement.id = reached_achievement.achievement_id '
			. 'LEFT JOIN character_stats ON reached_achievement.charname = character_stats.name AND character_stats.admin <= 600 '.$where
			. 'GROUP BY achievement.id, achievement.identifier, achievement.title, '
			. 'achievement.category, achievement.base_score, achievement.description '
			. 'ORDER BY achievement.category, achievement.identifier';
		return Achievement::_getAchievements($query);
	}

	public static function getAchievement($name) {
		$res = Achievement::getAchievements("where title='".mysql_real_escape_string($name)."'");
		if (count($res) > 0) {
			return $res[0];
		}
	}

	public static function getAwardedTo($achievementId) {
		$query = "SELECT character_stats.name, character_stats.outfit "
			. "FROM character_stats, reached_achievement "
			. "WHERE character_stats.name=reached_achievement.charname "
			. "AND reached_achievement.achievement_id = '".mysql_real_escape_string($achievementId)."' "
			. "ORDER BY reached_achievement.timedate DESC LIMIT 10";
		$result = mysql_query($query, getGameDB());
		$list= array();
		while($row = mysql_fetch_assoc($result)) {
			$list[] = array($row['name'], $row['outfit']);
		}
		return $list;
	}

	private static function _getAchievements($query) {
		$result = mysql_query($query, getGameDB());
		$list = array();
		while($row = mysql_fetch_assoc($result)) {
			$list[] = new Achievement($row['id'], $row['identifier'], $row['title'], 
				$row['category'], $row['base_score'], $row['description'], $row['cnt']);
		}
		mysql_free_result($result);
		return $list;
	}
}
?>
