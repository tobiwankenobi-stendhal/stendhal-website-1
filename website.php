<?php 
/*
 * This file is the PHP code that generate each of the website sections. 
 */
include('configuration.inc'); 
include('mysql.php');


function startBox($title) {
  echo '<div class="box">';
  echo '<div class="boxTitle">'.$title.'</div>';
  echo '<div class="boxContent">';
  }

function endBox() {
  echo '</div></div>';
  }
  
/**
  * Return a list of of the screenshots.
  * Each screenshot is a URL to the image.
  */
function getScreenshots($cond='') {
    $result = mysql_query('select * from screenshots order by created desc '.$cond, getWebsiteDB());
    $list=array();
    
    while($row=mysql_fetch_assoc($result)) {
      $list[]=$row['url'];
    }
    
    mysql_free_result($result);
    
    return $list;
  }

function getLatestScreenshot() {
  $list=getScreenshots('limit 1');
  return $list[0];
}

/**
  * This represent a Event.
  * Make sure you use one of the available events type:
  * - Raid
  * - Gift
  * - Meeting
  * - Quiz
  */
class Event {
  public $id;
  /* Date of the event in ISO Format  YYYY/MM/DD HH:mm*/
  public $date;
  /* Type of the event from the above */
  public $type;
  /* Location of the event. */
  public $location;
  /* Images of the event */
  public $images;
  /* One line description of the event. */
  public $oneLineDescription;
  /* The full description of the event with perhaps the result of it. */
  public $extendedDescription;
  
  function __construct($id, $date, $type, $location, $image, $shortDesc, $longDesc) {
    $this->id=$id;
    $this->date=$date;
    $this->type=$type;
    $this->location=$location;
    $this->images=$image;
    $this->oneLineDescription=$shortDesc;
    $this->extendedDescription=$longDesc;	
  }

  function show() {
    /* NOTE: Fill this note with the HTML code needed to draw an Event box. */
    echo '<div class="event">';
    echo '<img src="images/event'.($this->type).'.png" alt="event logo"/>';
    echo '<div class="eventDescription"><a href="?id=content/scripts/event&event_id='.$this->id.'">'.($this->oneLineDescription).'</a></div>';
    echo '<div class="eventDate">'.($this->date).'</div>';
    echo '<div class="eventLocation">'.($this->location).'</div>';
    echo '<span style="clear: left;"></span>';
    echo '</div>';
    /* END NOTE */
  }
};

/**
  * Returns a list of events.
  */
function getEvents($where='', $sortby='date desc', $cond='limit 2') {    
    $result = mysql_query('select * from events '.$where.' order by '.$sortby.' '.$cond, getWebsiteDB());
    $list=array();
    
    while($row=mysql_fetch_assoc($result)) {      
      $resultimages = mysql_query('select * from event_images where event_id='.$row['id'].' order by created desc', getWebsiteDB());
      $images=array();
      
      while($rowimages=mysql_fetch_assoc($resultimages)) {      
        $images[]=$rowimages['url'];
      }
      mysql_free_result($resultimages);
      
      $list[]=new Event(
                     $row['id'],
                     $row['date'],
                     ucfirst($row['type']),
                     $row['location'],
                     $images,
                     $row['shortDescription'],
                     $row['extendedDescription']);
    }
    
    mysql_free_result($result);
	
    return $list;
    }

/**
  * Returns a list of events that happens between adate and bdate both inclusive.
  */
function getEventsBetween($adate, $bdate) {
  return getEvents('where date between '.$adate.' and '.$bdate);
  }
  
/**
  * Returns a list of events that happened or are going to happed on this week.
  */
function getEventsOnWeek() {
  return getEvents('where week(date)=week(current_date())');
  }
  
/**
  * Returns a list of events that happened or are going to happed on this month.
  */
function getEventsOnMonth() {
  return getEvents('where month(date)=month(current_date())');
  }
  

/**
  * A class representing a news item without comments.
  */
class News {
  /* Title of the news item */
  public $title;
  /* Date in ISO format YYYY/MM/DD HH:mm */
  public $date;
  /* One line description of the news item. */
  public $oneLineDescription;
  /* Extended description of the news item that follow the one line one. */
  public $extendedDescription;
  /* Images of the news item */
  public $images;
  
  function __construct($title, $date, $shortDesc, $longDesc, $images) {
    $this->title=$title;
    $this->date=$date;
    $this->oneLineDescription=$shortDesc;
    $this->extendedDescription=$longDesc;
    $this->images=$images;
  }

  function show() {
    /* NOTE: Fill this note with the HTML code needed to draw an News item. */
    echo '<div class="newsItem">';
    echo '<div class="newsDate">'.$this->date.'</div>';
    echo '<div class="newsTitle">'.$this->title.'</div>';
    echo '<div class="newsOneLineContent">'.$this->oneLineDescription.'</div>';
    echo '<div class="newsContent">'.$this->extendedDescription.'</div>';
    echo '</div>';
    /* END NOTE */
   }
};

/**
  * Returns a list of news.
  */
function getNews($where='', $sortby='created desc', $cond='limit 2') {
    $result = mysql_query('select * from news '.$where.' order by '.$sortby.' '.$cond, getWebsiteDB());
    $list=array();
    
    while($row=mysql_fetch_assoc($result)) {      
      $resultimages = mysql_query('select * from news_images where news_id='.$row['id'].' order by created desc', getWebsiteDB());
      $images=array();
      
      while($rowimages=mysql_fetch_assoc($resultimages)) {      
        $images[]=$rowimages['url'];
      }
      mysql_free_result($resultimages);
      
      $list[]=new News($row['title'],
                     $row['created'],
                     $row['shortDescription'],
                     $row['extendedDescription'],
                     $images);
    }
    
    mysql_free_result($result);
	
    return $list;
  }

/**
  * Returns a list of news between adate and bdate both inclusive
  */
function getNewsBetween($adate, $bdate) {
  return getNews('where date between '.$adate.' and '.$bdate);
  }

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
    $this->level=$level;
    $this->outfit=$outfit;
    $this->xp=$xp;
    $this->attributes=$attributes;
    $this->money=$money;
    $this->equipment=$equipment;
  }

  function showExtended() {
    echo '<h1>'.$this->name.'</h1>';
    echo '<img src="createoutfit.php?outfit='.$this->outfit.'" alt="Player outfit"/>';
    
    /* TODO */
  }
  
  function show() {
    echo '<div class="playerBox">';
    echo '  <img src="createoutfit.php?outfit='.$this->outfit.'" alt="Player outfit"/>';
    echo '  <a href="?id=content/scripts/character&name='.$this->name.'">';
    echo '  <div class="playerBoxName">'.$this->name.'</div>';
    echo ' </a>';
    echo '  <div class="playerBoxXP">'.$this->xp.'</div>';
    echo '  <div class="playerBoxQuote">"'.$this->sentence.'"</div>';
    echo '</div>';
  }
  
  function showBrief() {
     /* NOTE: Fill this note with the HTML code needed to draw an News item. */
     /* END NOTE */
  }
}
  
/**
  * Returns a list of players online and offline that meet the given condition.
  */
function getPlayers($where='', $sortby='name', $cond='limit 2') {
    $result = mysql_query('select * from character_stats '.$where.' order by '.$sortby.' '.$cond, getGameDB());
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
  $player=getPlayers('', 'xp', 'limit 1');	   
  return $player[0];
  }


/**
  * Returns a list of players that are online right now.
  */
function getOnlinePlayers() {
  return getPlayers();
  }

/*
 * A Poll
 */
class Poll {
  /* Poll identifier */
  public $id;
  /* Poll question */
  public $question;
  /* where the poll should be redirected. */
  public $action;
  /* Possible answers of the poll */
  public $answers;
  
  function __construct($id, $question, $action, $answers) {
    $this->id=$id;
    $this->question=$question;
    $this->action=$action;
    $this->answers=$answers;
  }
  
  function show() {
    /* NOTE: Fill this note with the HTML code needed to draw an News item. */
    echo '<div class="pollQuestion">'.$this->question.'</div>';
    echo '<form>';
    foreach($this->answers as $i) {
      echo '  <input type="radio" name="answer" value="'.$i.'">'.$i.'<br>';
    }
    echo '<p><input type="button" name="submit" value="Send">';
    echo '</form>';
     /* END NOTE */
  }
}

/**
  * Returns a Poll give its id.
  */
function getPoll($id) {
  return new Poll($id, "Question?", "pollprocess.php", array("Yes", "No"));
}

function getLatestPoll() {
  return new Poll("dummy", "Question?", "pollprocess.php", array("Yes", "No"));
}

/**
  * Returns an array with the key=>value of stats from server.
  */
function getServerStats() {
}

/*
 * A class representing a monster.
 */
class Monster {
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

  function __construct($name, $description, $class, $gfx, $level,$kills, $killed, $attributes, $equipment, $location) {
    $this->id=$name;
    $this->description=$description;
    $this->class=$class;
    $this->gfx=$gfx;
    $this->level=$level;
    $this->kills=$kills;
    $this->killed=$killed;
    $this->attributes=$attributes;
    $this->equipment=$equipment;
    $this->locations=$location;
  }
  
  function show() {
     /* NOTE: Fill this note with the HTML code needed to draw an News item. */
     /* END NOTE */
  }
}

/**
  * Returns a list of Monsters
  */
function getMonsters() {
  return array(
    Monster('rat','A little rat','rat','rat.png','0',10,1210,array('atk'=>10, 'def'=>3, 'hp'=>'10'),array(), 'At Semos')
	);
}

?>