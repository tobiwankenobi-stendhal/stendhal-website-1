<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2009   Hendrik Brummermann

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
  * A class that represent a player, what it is and what it equips.
  */
class NPC {
	public $name;
	public $title;
	public $class;
	public $outfit;
	public $imagefile;
	public $level;
	public $hp;
	public $base_hp;
	public $zone;
	public $pos;
	public $description;
	public $job;

 
	function __construct($name, $title, $class, $outfit, $level, $hp, $base_hp, $zone, $pos, $description, $job) {
		$this->name=$name;
		$this->title=$title;
		$this->class=$class;
		$this->outfit=$outfit;
		$imagefile = '/images/npc/'.surlencode($class).'.png';
		if (isset($outfit) && $outfit != '') {
			$imagefile = '/images/outfit/'.surlencode($outfit).'.png';
		}
		$this->imagefile=rewriteURL($imagefile);
		$this->level=$level;
		$this->hp=$hp;
		$this->base_hp=$base_hp;
		$this->zone=$zone;
		$this->pos=$pos;
		$this->description=$description;
		$this->job=$job;
	}


	/**
	 * gets the names NPC from the database.
	 */
	function getNPC($name) {
		$npcs = NPC::_getNPCs('select * from npcs where name="'.mysql_real_escape_string($name).'" limit 1', getGameDB());
		return $npcs[0];	
	}


	/**
	  * Returns a list of npcs that meet the given condition.
	  * Note: Parmaters must be sql escaped.
	  */
	function getNPCs($where='', $sortby='name', $cond='') {
	    return NPC::_getNPCs('select * from npcs '.$where.' order by '.$sortby.' '.$cond, getGameDB());
	}


	private function _getNPCs($query) {
		$NO_ZONE = array(
			'Azazel', 'Cherubiel', 'Gabriel', 'Ophaniel', 'Raphael', 'Uriel', 'Zophiel',
			'Ben', 'Goran', 'Mary', 'Zak',
			'Easter Bunny', 'Rose Leigh', 'Santa');

		$result = mysql_query($query, getGameDB());
		$list = array();

		while($row = mysql_fetch_assoc($result)) {
			$zone = $row['zone'];
			$pos = 'at ' . $row['x'] . ', ' . $row['y'];
			if (in_array($row['name'], $NO_ZONE)) {
				$zone = 'unknown';
				$pos = '';
			}
			$list[]=new NPC($row['name'],
				$row['title'],
				$row['class'],
				$row['outfit'],
				$row['level'],
				$row['hp'],
				$row['base_hp'],
				$zone,
				$pos,
				$row['description'],
				$row['job']);
		}
		mysql_free_result($result);
		return $list;
	}
}
?>
