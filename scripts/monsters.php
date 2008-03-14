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
  /* Times this monster has been killed */
  public $kills;
  /* Players killed by this monster class */
  public $killed;
  /* Attributes of the monster as an array attribute=>value */
  public $attributes;
  /* Stuff this creature wears as an array slot=>item */
  public $equipment;
  /* Locations where this monster is found. */
  public $locations;

  function __construct($name, $description, $class, $gfx, $level,$attributes) {
    $this->name=$name;
    $this->description=$description;
    $this->class=$class;
    self::$classes[$class]=0;
    $this->gfx=$gfx;
    $this->level=$level;
    $this->attributes=$attributes;
  }
  
  function show() {
     /* NOTE: Fill this note with the HTML code needed to draw an News item. */
     /* END NOTE */
  }
  
  function getClasses() {
    return self::$classes;
  }
  
  function fillKillKilledData() {       
    $numberOfDays=14;
    
    $result = mysql_query('select dayofyear(timedate) as day, count(*) as amount from gameEvents where datediff(now(),timedate)<='.$numberOfDays.' and event="killed" and param1="'.addslashes($this->name).'" group by dayofyear(timedate)', getGameDB());

    $this->kills=array();
    
    $base=date('z')+1;
    for($i=0;$i<$numberOfDays;$i++) {
      $this->kills[$base-$i]=0;
    }

    while($row=mysql_fetch_assoc($result)) {      
      $this->kills[$row['day']]=$row['amount'];
    }
    
    mysql_free_result($result);

    $result = mysql_query('select dayofyear(timedate) as day, count(*) as amount from gameEvents where datediff(now(),timedate)<='.$numberOfDays.' and event="killed" and source="'.addslashes($this->name).'" group by dayofyear(timedate)', getGameDB());

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

function listOfMonsters($monsters) {
  $data='';
  foreach($monsters as $m) {
    $data=$data.'"'.$m->name.'",';
  }
  
  return substr($data, 0, strlen($data)-1);
}

function getMostKilledMonster($monsters) {
    $numOfDays=7;
   
    $query='select param1, count(*) as amount from gameEvents where datediff(now(),timedate)<='.$numOfDays.' and event="killed" and param1 in ('.listOfMonsters($monsters).') group by param1 order by amount desc limit 1';
    $result = mysql_query($query, getGameDB());
   
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

    $query='select source, count(*) as amount from gameEvents where datediff(now(),timedate)<='.$numOfDays.' and event="killed" and source in ('.listOfMonsters($monsters).') and param1 not in ('.listOfMonsters($monsters).') group by source order by amount desc limit 1';
    $result = mysql_query($query, getGameDB());
   
    $monster=null;
    while($row=mysql_fetch_assoc($result)) {      
      foreach($monsters as $m) {
        if($m->name==$row['source']) {
          $monster=array($m, $row['amount']);        
        }
      }
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
    $list[]=new Monster($name, $description, $class, $gfx,$level, $attributes);
  } 
  
  Monster::$monsters=$list;
  return $list;
}

?>