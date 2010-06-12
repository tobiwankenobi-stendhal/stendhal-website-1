<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008  Miguel Angel Blanch Lardin

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
class Player {
	/* Name of the player */
	public $name;
	/* Sentence that the player wrote using /sentence */
	public $sentence;
	/* Level of the player */
	public $level;
	/* An outfit representing the player look in game. */
	public $outfit;
	/* The age of the player */
	public $age;
	/* XP of the player. It is a special attribute. */
	public $xp;
	/* adminlevel */
	public $adminlevel;
	/* Attributes the player has as a array key=>value */
	public $attributes;
	/* Money the player has. */
	public $money;
	/* Equipment the player has in slots in a array slot=>item */
	public $equipment;

	function __construct($name, $sentence, $age, $level, $xp, $married, $outfit, $money, $adminlevel, $attributes, $equipment) {
		$this->name=$name;
		$this->sentence=$sentence;
		$this->age=$age;
		$this->level=$level;
		$this->outfit=$outfit;
		$this->xp=$xp;
		$this->married=$married;
		$this->attributes=$attributes;
		$this->adminlevel=$adminlevel;
		$this->money=$money;
		$this->equipment=$equipment;
	}

	function show() {
		echo '<div class="playerBox">';
		echo '  <a href="'.rewriteURL('/character/'.surlencode($this->name).'.html').'">';
		echo '  <img src="'.rewriteURL('/images/outfit/'.surlencode($this->outfit).'.png').'" alt="" width="48" height="64">';
		echo '  <span class="block name">'.htmlspecialchars(utf8_encode($this->name)).'</span>';
		echo ' </a>';
		echo '  <div class="xp">'.$this->xp.' xp</div>';
		if ($this->sentence != '') {
			echo ' <div class="quote">'.htmlspecialchars(utf8_encode($this->sentence)).'</div>';
		} else {
			echo ' <div style="clear:left"></div>';
		}
		echo '</div>';
	}

	function getDeaths() {
		##
		## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
		##
		if(STENDHAL_PLEASE_MAKE_IT_FAST) {
			return array();
		}
		##
		## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
		##

		$result = mysql_query("
    select
      timedate,
      source
    from gameEvents
    where
      event='killed' and
      param1='".mysql_real_escape_string($this->name)."' and
      datediff(now(),timedate)<=7*52 and
      (param2 = 'C P' or param2 = 'E P' or param2 = 'P P')
    order by timedate desc
    limit 4", getGameDB());

		$kills=array();

		/*
		 * TODO: Refactor to use the new table.
		 */

		while($row=mysql_fetch_assoc($result)) {
			$kills[$row['timedate']]=$row['source'];
		}

		mysql_free_result($result);
		return $kills;
	}

	function getAccountInfo() {
		$result=mysql_query('select timedate, status from account, characters where account.id=characters.player_id AND charname="'.mysql_real_escape_string($this->name).'"',getGameDB());
		$account=array();

		$row=mysql_fetch_assoc($result);

		$account["register"]=$row["timedate"];
		$account["status"]=$row["status"];

		mysql_free_result($result);

		return $account;
	}

	function getDMScore() {
		$result=mysql_query('select points from halloffame where charname="'.mysql_real_escape_string($this->name).'" and fametype="D"',getGameDB());

		while($row=mysql_fetch_assoc($result)) {
			$points=$row['points'];
		}

		mysql_free_result($result);
		if(sizeof($points)==0){
			$points=0;
		}
		return $points;

	}
}

/**
 * Returns a list of players online and offline that meet the given condition.
 * Note: Parmaters must be sql escaped.
 */
function getPlayers($where='', $sortby='name', $cond='limit 2') {
	return _getPlayers('select * from character_stats '.$where.' order by '.$sortby.' '.$cond, getGameDB());
}

function getPlayer($name) {
	$player=_getPlayers('select * from character_stats where name="'.mysql_real_escape_string($name).'" limit 1', getGameDB());
	return $player[0];
}

function getBestPlayer($where='') {
	$player=_getPlayers('select  *,xp/(age+1) as xp_age_rel from character_stats '.$where.' order by xp_age_rel desc limit 1', getGameDB());
	return $player[0];
}

function getDMHeroes($cond='limit 2') {
	return _getPlayers('select character_stats.* from character_stats join halloffame on charname=name where fametype="D" order by points desc '.$cond, getGameDB());

}

/**
 * Returns a list of players that are online right now.
 */
function getOnlinePlayers() {
	return _getPlayers('select * from character_stats where online=1 order by name');
}


/**
 * Returns a list of characters owned by this account.
 *
 * @param string $username
 * @return List of Players
 */
function getCharactersForUsername($username) {
	return _getPlayers('SELECT character_stats.* FROM character_stats, characters, account '
	.'WHERE account.username=\''.mysql_real_escape_string($username).'\' AND '
	.'characters.player_id=account.id AND character_stats.name=characters.charname');
}

function _getPlayers($query) {
	$result = mysql_query($query,getGameDB());
	$list=array();

	while($row=mysql_fetch_assoc($result)) {
		$attributes=array();
		$attributes['atk']=$row['atk'];
		$attributes['def']=$row['def'];
		$attributes['hp']=$row['hp'];
		$attributes['karma']=$row['karma'];

		$equipment=array();
		$equipment['head']=$row['head'];
		$equipment['armor']=$row['armor'];
		$equipment['lhand']=$row['lhand'];
		$equipment['rhand']=$row['rhand'];
		$equipment['legs']=$row['legs'];
		$equipment['feet']=$row['feet'];
		$equipment['cloak']=$row['cloak'];

		$list[]=new Player($row['name'],
		$row['sentence'],
		$row['age'],
		$row['level'],
		$row['xp'],
		$row['married'],
		$row['outfit'],
		$row['money'],
		$row['admin'],
		$attributes,
		$equipment);
	}

	mysql_free_result($result);

	return $list;
}

?>
