<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2016  The Arianne Project

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

class Event {
	public $source;
	public $timedate;

	function __construct($source, $timedate) {
		$this->source=$source; 	  	  	
		$this->timedate=$timedate;
	}

	function getURL($outfits, $name, $type) {
		if ($type == 'P') {
			$url = '<a class="small_image_link" href="'.rewriteURL('/character/'.surlencode($name).'.html').'"><img src="'.rewriteURL('/images/outfit/'.surlencode($outfits[$name]).'.png').'" alt="'.htmlspecialchars($name).'" width="36" height="48" title="'.htmlspecialchars($name).'"></a> ';
		} else if ($type == 'C') {
			$monster = getMonster($name);
			if (isset($monster) && $monster != null) {
				$url = '<a class="small_image_link" href="'.rewriteURL('/creature/'.surlencode($name).'.html').'"><img src="'.$monster->showImage().'" alt="'.htmlspecialchars($name).'" width="36" height="48" title="'.htmlspecialchars($name).'"></a> ';
			} else {
				$url = htmlspecialchars($name);
			}
		} else {
			$url = htmlspecialchars($name);
		}
		return $url;
	} 

	function getCharacterHtml($outfits, $character) {
		return $this->getURL($outfits, $character, 'P');
	}

	public function getHtml($outfits) {
		return '';
	}

	public function addPlayersToList(&$players) {
		$players[]=$this->source;
	}
	
	static function getFilterFrom($filter) {
		if($filter=="friends") {
			return ", (SELECT buddy.buddy as charname FROM characters, buddy "
				. "WHERE characters.player_id='".mysql_real_escape_string($_SESSION['account']->id)."' AND account.id=characters.player_id AND characters.charname=buddy.charname AND buddy.relationtype='buddy') As x ";
		} else {
			return '';
		}
	}
	
	static function getFilterWhereSource($filter) {
		if($filter=="friends") {
			return " source=x.charname and ";
		} else {
			return '';
		}
    }
    
 	static function getFilterWhereParam1($filter) {
		if($filter=="friends") {
			return " param1=x.charname and ";
		} else {
			return '';
		}
    }
    
	static function getFilterWhereBoth($filter) {
		if($filter=="friends") {
			return " (source=x.charname OR param1=x.charname) and ";
		} else {
			return '';
		}
    }
}

class KillEvent extends Event  {
  public $victim;
  public $sourcetype;  
  public $victimtype;  
  
  function __construct($source, $victim, $sourcetype, $victimtype, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->victim=$victim;
  	$this->sourcetype=$sourcetype;
  	$this->victimtype=$victimtype;	  	  	  	
  }
  
  function getHtml($outfits) {
  	// known issue with urls of baby dragon, cat and sheep which are down as type 'C'
	// cheat and create pages for them?
  	return '<br>'.$this->getURL($outfits,$this->source,$this->sourcetype).' killed '.$this->getURL($outfits,$this->victim,$this->victimtype).' at '.date('H:i',strtoTime($this->timedate));
  }
  
  function addPlayersToList(&$players) {
	parent::addPlayersToList($players);
  	$players[]=$this->victim;
  }
  
}

function getKillEvents($filter) {
    $query = 'SELECT source, param1 as victim, left(param2,1) as sourcetype, right(trim(param2),1) as victimtype,  timedate ' .
    		'			 FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereBoth($filter) .' event=\'killed\' and source <> \'baby_dragon\' and timedate > subtime(now(), \'00:05:00\') ORDER BY timedate DESC LIMIT 5';
	$rows = DB::game()->query($query);
    $killevents=array();
    foreach($rows as $row) {      
		$killevents[] = new KillEvent($row['source'], $row['victim'], $row['sourcetype'], $row['victimtype'], $row['timedate']);
    }
	
    return $killevents;
}


class OutfitEvent extends Event  {
  
  function __construct($source, $timedate) {
  	parent::__construct($source, $timedate);  	  	  	
  }
  
  function getHtml($outfits) {
  	return '<br>'.$this->getCharacterHtml($outfits,$this->source).' changed outfit at '.date('H:i',strtoTime($this->timedate));
  }
  
}
 
function getOutfitEvents($filter) {
 	// consider adding a distinct or group by so we don't get lots from same player
    $query = 'SELECT source,  timedate ' .
			  'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereSource($filter) .' event=\'outfit\' and timedate > subtime(now(), \'01:00:00\') ORDER BY timedate DESC LIMIT 2';
    $rows = DB::game()->query($query);
    $outfitevents = array();
	foreach($rows as $row) {      
		$outfitevents[] = new OutfitEvent($row['source'], $row['timedate']);
	}
    return $outfitevents;
}
  
  
class QuestEvent extends Event  {
  public $quest;
  
  function __construct($source, $quest, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->quest=$quest;	  	  	  	
  }
  
  function getHtml($outfits) {
  	return '<br>'.$this->getCharacterHtml($outfits,$this->source).' completed the '.htmlspecialchars(ucfirst(str_replace('_',' ',$this->quest))).' quest at '.date('H:i',strtoTime($this->timedate));
  }
}

function getQuestEvents($filter) {
    $query = 'SELECT source, param1 as quest, timedate ' .
			  'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereSource($filter) .' event=\'quest\' and param1 IN (\'daily\',\'deathmatch\') and timedate > subtime(now(), \'01:00:00\') and left(param2,4)=\'done\'  ORDER BY timedate DESC LIMIT 10';
    $rows = DB::game()->query($query);
    $questevents = array();
    foreach($rows as $row) {      
		$questevents[] = new QuestEvent($row['source'], $row['quest'], $row['timedate']);
    }
    return $questevents;
}

class LevelEvent extends Event  {
  public $level;

  function __construct($source, $level, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->level=$level;  	  	  	
  }
  
  function getHtml($outfits) {
	return '<br>'.$this->getCharacterHtml($outfits, $this->source).' reached level '.htmlspecialchars($this->level).' at '.date('H:i',strtoTime($this->timedate));
  }
  
}
 function getLevelEvents($filter) {
    $query = 'SELECT source, param1 as level,  timedate ' .
		'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereSource($filter) .' event=\'level\'  and timedate > subtime(now(), \'01:00:00\')  ORDER BY timedate DESC LIMIT 10';
    $rows = DB::game()->query($query);
    $levelevents = array();
    foreach($rows as $row) {      
        $levelevents[] = new LevelEvent($row['source'], $row['level'], $row['timedate']);
    }
    return $levelevents;
}


class SignEvent extends Event  {
  public $text;

  function __construct($source, $text, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->text=$text;  	  	  	
  }
  
  function getHtml($outfits){
  	return '<br>'.$this->getCharacterHtml($outfits,$this->source).' rented a sign saying: "'.htmlspecialchars($this->text).'" at '.date('H:i',strtoTime($this->timedate));
  }
  
}
 function getSignEvents($filter) {
 
    $query = 'SELECT source, trim(param2) as text,  timedate ' .
    		'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereSource($filter) .' event=\'sign\'  and timedate > subtime(now(), \'01:00:00\')  ORDER BY timedate DESC LIMIT 10';
    $rows = DB::game()->query($query);
    $signevents = array();
    foreach($rows as $row) {      
        $signevents[] = new SignEvent($row['source'], $row['text'], $row['timedate']);
    }
    return $signevents;
}


class PoisonEvent extends Event  {
  public $victim; 
  
  function __construct($source, $victim, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->victim=$victim; 	  	  	
  }
  
  function getHtml($outfits) {
  	return '<br>' .$this->getURL($outfits,$this->source,'C').'poisoned '.$this->getCharacterHtml($outfits,$this->victim).' at '.date('H:i',strtoTime($this->timedate));
  }
  
  function addPlayersToList(&$players) {
	parent::addPlayersToList($players);
  	$players[]=$this->victim;
  }
  
}

function getPoisonEvents($filter) {
    $query = 'SELECT source, param1 as victim,  timedate ' .
		      'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereParam1($filter) .' event=\'poison\' and timedate > subtime(now(), \'00:05:00\') ORDER BY timedate DESC LIMIT 3';
    $rows = DB::game()->query($query);
    $events = array();
    foreach($rows as $row) {      
        $events[] = new PoisonEvent($row['source'], $row['victim'], $row['timedate']);
    }
    return $events;
}

class ChangeZoneEvent extends Event  {
  public $zone; 
  
  function __construct($source, $zone, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->zone=$zone; 	  	  	
  }
  
  function getHtml($outfits) {
  	return '<br>'.$this->getCharacterHtml($outfits,$this->source).' visited '.htmlspecialchars(ucfirst(str_replace('_',' ',$this->zone))).' at '.date('H:i',strtoTime($this->timedate));
  }
  
}

function getChangeZoneEvents($filter) {
    $query = 'SELECT source, substring(param1,locate(\'_\',param1)+1) as zone,  timedate ' .
		  'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereSource($filter) .' event=\'change zone\' and timedate > subtime(now(), \'00:05:00\') ORDER BY timedate DESC LIMIT 3';
    $rows = DB::game()->query($query);
    $events = array();
    foreach($rows as $row) {      
        $events[] = new ChangeZoneEvent($row['source'], $row['zone'], $row['timedate']);
    }
    return $events;
}

class EquipEvent extends Event  {
  public $zone; 
  
  function __construct($source, $item, $amount, $timedate) {
  	parent::__construct($source, $timedate); 
  	$this->item=$item; 	 
  	$this->amount=$amount; 
  }
  
  function getHtml($outfits) {
  	return '<br>'.$this->getCharacterHtml($outfits,$this->source).' picked up ' .
  			'<a class="menu" href="'.rewriteURL('/item/'.surlencode(getItem($this->item)->class).'/'.surlencode($this->item).'.html').'"><img src="'.htmlspecialchars(getItem($this->item)->showImage()).'" alt="'.htmlspecialchars($this->item).'" title="'.htmlspecialchars($this->amount).' '.htmlspecialchars($this->item).'"></a> at '.date('H:i',strtoTime($this->timedate));
  }
}

function getEquipEvents($filter) {
    $query = 'SELECT source, param1 as item, substring_index(trim(param2),\' \',-1) as amount, timedate       ' .
		   'FROM gameEvents'. Event::getFilterFrom($filter) .' USE INDEX (i_gameEvents_timedate) WHERE '. Event::getFilterWhereSource($filter) .' event=\'equip\'  and timedate > subtime(now(), \'00:05:00\') and (left(param2,7)=\'content\' or left(param2,4)=\'null\') ORDER BY timedate DESC LIMIT 5';
    $rows = DB::game()->query($query);
    $events = array();
    foreach($rows as $row) {      
        $events[] = new EquipEvent($row['source'], $row['item'], $row['amount'], $row['timedate']);
    }
    return $events;
}

class AchievementEvent extends Event  {
	public $zone; 

	function __construct($source, $title, $description, $timedate) {
		parent::__construct($source, $timedate);
		$this->title=$title;
		$this->description=$description; 
	}

	function getHtml($outfits) {
		return '<br>'.$this->getCharacterHtml($outfits,$this->source).' reached achievement '
			. '<a href="'.rewriteURL('/achievement/'.surlencode($this->title).'.html').'" alt="'.htmlspecialchars($this->title).'" title="'.htmlspecialchars($this->description).'">'.
			htmlspecialchars($this->title).'</a> at '.date('H:i',strtoTime($this->timedate));
	}

}
function getAchievementEvents($filter) {
    $query = 'SELECT charname as source, title, description, timedate       ' .
    		 'FROM reached_achievement JOIN achievement ON achievement.id = reached_achievement.achievement_id '. Event::getFilterFrom($filter) .' WHERE active = 1 HAVING '. Event::getFilterWhereSource($filter) .' timedate > subtime(now(), \'06:00:00\') ORDER BY timedate DESC LIMIT 10';
    $rows = DB::game()->query($query);
    $events = array();
    foreach($rows as $row) {      
        $events[] = new AchievementEvent($row['source'], $row['title'], $row['description'], $row['timedate']);
    }
    return $events;
}

function getOutfitsForPlayers($players) {
	$query = 'SELECT distinct name, outfit, outfit_colors FROM character_stats where name IN ("'.implode('","',$players).'")';
    $rows = DB::game()->query($query);
	$outfits = array();
	foreach($rows as $row) {
		$outfit = $row['outfit'];
		if (isset($row['outfit_colors']) && strlen($row['outfit_colors']) > 0) {
			$outfit = $outfit.'_'.$row['outfit_colors'];
		}
		$outfits[$row['name']] = $outfit;
	}
	return $outfits;
}
