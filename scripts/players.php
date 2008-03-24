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
  /* The age of the player
  public $age
  /* XP of the player. It is a special attribute. */
  public $xp;
  /* Attributes the player has as a array key=>value */
  public $attributes;
  /* Money the player has. */
  public $money;
  /* Equipment the player has in slots in a array slot=>item */
  public $equipment;
  
  function __construct($name, $sentence, $age, $level, $xp, $outfit, $money, $attributes, $equipment) {
    $this->name=$name;
    $this->sentence=$sentence;
    $this->age=$age;
    $this->level=$level;
    $this->outfit=$outfit;
    $this->xp=$xp;
    $this->attributes=$attributes;
    $this->money=$money;
    $this->equipment=$equipment;
  }

  function show() {
    echo '<div class="playerBox">';
    echo '  <img src="createoutfit.php?outfit='.$this->outfit.'" alt="Player outfit"/>';
    echo '  <a href="?id=content/scripts/character&name='.$this->name.'">';
    echo '  <div class="name">'.$this->name.'</div>';
    echo ' </a>';
    echo '  <div class="xp">'.$this->xp.' xp</div>';
    echo '  <div class="quote">"'.$this->sentence.'"</div>';
    echo '</div>';
  }
  
  function getDeaths() {       
    $result = mysql_query('select timedate, source from gameEvents where datediff(now(),timedate)<=7*52 and event="killed" and param1="'.addslashes($this->name).'" limit 4', getGameDB());
    $kills=array();

    while($row=mysql_fetch_assoc($result)) {      
      $kills[$row['timedate']]=$row['source'];
    }
    
    mysql_free_result($result);
    return $kills;
    }
}
  
/**
  * Returns a list of players online and offline that meet the given condition.
  */
function getPlayers($where='', $sortby='name', $cond='limit 2') {
    return _getPlayers('select * from character_stats '.$where.' order by '.$sortby.' '.$cond, getGameDB());
}

function getBestPlayer() {
    $player=_getPlayers('select  *,xp/(age+1) as xp_age_rel from character_stats order by xp_age_rel desc limit 1', getGameDB());
    return $player[0];
}

/**
  * Returns a list of players that are online right now.
  */
function getOnlinePlayers() {
    return _getPlayers('select * from character_stats where online=1 order by name');
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
                     $row['outfit'],
                     $row['money'],
                     $attributes,
                     $equipment);
    }
    
    mysql_free_result($result);
	
    return $list;
}


/**
  * Returns the player of the week.
  */
function getPlayerOfTheWeek() {
  $player=_getPlayers('select  *,xp/(age+1) as xp_age_rel from character_stats order by xp_age_rel desc limit 1', getGameDB());
  return $player[0];
  }


?>