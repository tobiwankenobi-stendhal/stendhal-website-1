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
/*
 * A class representing a monster.
 */
class Monster {
  public static $classes=array();
  public static $monsters=array();
  
  /* Name of the monster */
  public $name;
  /* Description of the monster */
  public $description;
  /* Class of the monster */
  public $class;
  /* GFX URL of the monster. */
  public $gfx;
  /* Level of the monster */
  public $level;
  /* XP value of the monster */
  public $xp;
  /* Times this monster has been killed */
  public $kills;
  /* Players killed by this monster class */
  public $killed;
  /* Attributes of the monster as an array attribute=>value */
  public $attributes;
  /* Stuff this creature drops as an array (item, quantity, probability) */
  public $drops;
  /* Locations where this monster is found. */
  public $locations;

  function __construct($name, $description, $class, $gfx, $level, $xp, $attributes, $drops) {
    $this->name=$name;
    $this->description=$description;
    $this->class=$class;
    self::$classes[$class]=0;
    $this->gfx=$gfx;
    $this->level=$level;
    $this->xp=$xp;
    $this->attributes=$attributes;
    $this->drops=$drops;
  }
  
  function showImage() {
  	return $this->gfx;
  }
  
  function getClasses() {
    return self::$classes;
  }
  
  function fillKillKilledData() {       
    $numberOfDays=14;
    
    ##
    ## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
    ## 
    
    $this->kills=array();
    $this->killed=array();
    
    for($i=0;$i<$numberOfDays;$i++) {
      $this->kills[$i]=0;
      $this->killed[$i]=0;
    }
    
    ##
    ## HACK: I am here to present fake data until queries are optimizied.
    ##
    if(STENDHAL_PLEASE_MAKE_IT_FAST) {
      return;
    }
    
    /*
     * Amount of times this creature has been killed by a player or another creature.
     */
    $result = mysql_query('
    select 
      dayofyear(timedate) as day, 
      count(*) as amount 
    from gameEvents 
    where 
      event="killed" and 
      param1="'.addslashes($this->name).'" and 
      datediff(now(),timedate)<='.$numberOfDays.' 
    group by dayofyear(timedate)', getGameDB());
    
    /*
     * TODO: Refactoring
     *   Expected table:
     * 
     *   create table Killed(
     *     timedate timedate,
     * 
     *     killed varchar(32),
     *     killedIscreature boolean,
     * 
     *     killer varchar(32),
     *     killerIscreature boolean,
     *   )
     *       
     */
    
    $this->kills=array();
    
    $base=date('z')+1;
    for($i=0;$i<$numberOfDays;$i++) {
      $this->kills[$base-$i]=0;
    }

    while($row=mysql_fetch_assoc($result)) {      
      $this->kills[$row['day']]=$row['amount'];
    }
    
    mysql_free_result($result);

    /*
     * Amount of times this creature has killed a player.
     */
    $result = mysql_query('
    select 
      dayofyear(timedate) as day, 
      count(*) as amount 
    from gameEvents 
    where 
      event="killed" and 
      source="'.addslashes($this->name).'" and 
      datediff(now(),timedate)<='.$numberOfDays.' and 
      param1 not in ('.listOfMonsters(getMonsters()).') 
    group by dayofyear(timedate)', getGameDB());

    /*
     * TODO: Refactoring
     */
        
    $this->killed=array();
    
    $base=date('z')+1;
    for($i=0;$i<$numberOfDays;$i++) {
      $this->killed[$base-$i]=0;
    }

    while($row=mysql_fetch_assoc($result)) {      
      $this->killed[$row['day']]=$row['amount'];
    }
    
    mysql_free_result($result);
  }
}

function existsMonster($name) {
  $monsters=getMonsters();
  foreach($monsters as $m) {
    if($m->name==$name) {
      return true;
    }
  }

  return false;
}

function getMonster($name) {
  $monsters=getMonsters();
  foreach($monsters as $m) {
    if($m->name==$name) {
      return $m;
    }
  }

  return null;
}
function listOfMonsters($monsters) {
  $data='';
  foreach($monsters as $m) {
    $data=$data.'"'.$m->name.'",';
  }
  
  return substr($data, 0, strlen($data)-1);
}

function getMostKilledMonster($monsters) {
    $numOfDays=7;

    ##
    ## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
    ## 
    if(STENDHAL_PLEASE_MAKE_IT_FAST) {
      return array(getMonster("rat"),0);
    }
    ##
    ## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
    ## 
    
    $query='select param1, count(*) as amount from gameEvents where datediff(now(),timedate)<='.$numOfDays.' and event="killed" and param1 in ('.listOfMonsters($monsters).') group by param1 order by amount desc limit 1';
    $result = mysql_query($query, getGameDB());
    
	/*
     * TODO: Refactoring
     */
    
    $monster=null;
    while($row=mysql_fetch_assoc($result)) {      
      foreach($monsters as $m) {
        if($m->name==$row['param1']) {
          $monster=array($m, $row['amount']);        
        }
      }
    }
    
    mysql_free_result($result);
    return $monster;
}

function getBestKillerMonster($monsters) {
    $numOfDays=7;

    ##
    ## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
    ## 
    if(STENDHAL_PLEASE_MAKE_IT_FAST) {
      return array(getMonster("rat"),0);
    }
    ##
    ## HACK AHEAD - MOVE AWAY - HACK AHEAD - MAKE ROOM
    ## 
    
    $query='select source, count(*) as amount from gameEvents where datediff(now(),timedate)<='.$numOfDays.' and event="killed" and source in ('.listOfMonsters($monsters).') and param1 not in ('.listOfMonsters($monsters).') group by source order by amount desc limit 1';
    $result = mysql_query($query, getGameDB());
    
	/*
     * TODO: Refactoring
     */
    
    $monster=null;
    while($row=mysql_fetch_assoc($result)) {   
      $monster=array(getMonster($row['source']), $row['amount']);        
    }
    
    mysql_free_result($result);
    return $monster;
}

/**
  * Returns a list of Monsters
  */
function getMonsters() {
  if(sizeof(Monster::$monsters)!=0) {
    return Monster::$monsters;
  }
  
  $monstersXMLConfigurationFile="data/conf/creatures.xml";
  
  $creatures=XML_unserialize(implode('',file($monstersXMLConfigurationFile)));
  $creatures=$creatures['creatures'][0]['creature'];
  
  $list=array();

  for($i=0;$i<sizeof($creatures)/2;$i++) {
    /*
     * We omit hidden creatures.
     */
    if(isset($creatures[$i]['hidden'])) {
      continue;
    }
    
    $name=$creatures[$i.' attr']['name'];
    
    if(isset($creatures[$i]['description'])) {
      $description=$creatures[$i]['description']['0'];
    } else {
      $description='';
    }
    
    $class=$creatures[$i]['type']['0 attr']['class'];
    $gfx='data/sprites/monsters/'.$class.'/'.$creatures[$i]['type']['0 attr']['subclass'].'.png';
    list($w,$h)=explode(",",$creatures[$i]['attributes'][0]['size']['0 attr']['value']);
    $gfx='monsterimage.php?url='.$gfx.'&w='.$w.'&h='.$h;
    
    $attributes=array();
    $attributes['atk']=$creatures[$i]['attributes'][0]['atk']['0 attr']['value'];
    $attributes['def']=$creatures[$i]['attributes'][0]['def']['0 attr']['value'];
    $attributes['speed']=$creatures[$i]['attributes'][0]['speed']['0 attr']['value'];
    $attributes['hp']=$creatures[$i]['attributes'][0]['hp']['0 attr']['value'];
    
    $level=$creatures[$i]['level']['0 attr']['value'];
    $xp=$creatures[$i]['experience']['0 attr']['value'];
    
    $drops=array();

    foreach($creatures[$i]['drops'][0]['item'] as $drop) {
    	if(is_array($drop)) {
    		$drops[]=array("name"=>$drop['value'],"quantity"=>$drop['quantity'], "probability"=>$drop['probability']);
    	}
    }
    /* DEBUGING
    echo '<h1>Creature: '.$name.'</h1><br>';
    echo 'Description: "'.$description.'"<br>';
    echo 'Class: "'.$class.'"<br>';
    echo 'Level: '.$level.'<br>';
    echo 'GFX: "'.$gfx.'" w='.$w.' h='.$h.' <br>';    
    echo '<img src="monsterimage.php?url='.$gfx.'&w='.$w.'&h='.$h.'"/><br>';
    echo 'Attributes: <br>';
    //print_r($attributes);
    
    //print_r($creatures[$i]);
    */
    $list[]=new Monster($name, $description, $class, $gfx, $level, $xp, $attributes, $drops);
  } 
  
  Monster::$monsters=$list;
  return $list;
}

?>