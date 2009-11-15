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
  * This represent a Event.
  * Make sure you use one of the available events type:
  * - Raid
  * - Gift
  * - Meeting
  * - Quiz
  * - Other
  */
class Event {
  public $id;
  /* Date of the event in ISO Format  YYYY/MM/DD HH:mm */
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
    echo '<div class="event">';
    echo '<img src="images/events/event'.($this->type).'.png" alt="event logo"/>';
    echo '<div class="description"><a href="?id=content/scripts/event&amp;event_id='.$this->id.'">'.($this->oneLineDescription).'</a></div>';
    
    if (($this->date)!="0000-00-00 00:00:00") {
      $date=date("M,j Y",strtotime($this->date));
    } else {
      $date="<b>TBC</b>";
    }
    if($date==date("M,j Y")) {
      $date="<b>Today</b>";
    }
    
    echo '<div class="date">'.$date.'</div>';
    echo '<div class="location">'.($this->location).'</div>';
    echo '<span style="clear: left;"></span>';
    echo '</div>';
  }
};

/**
  * Returns a list of events. Note: All parameters need to be SQL escaped.
  */
function getEvents($where='', $sortby='id desc', $cond='limit 2') {    
    $result = mysql_query('select * from events '.$where.' order by '.$sortby.' '.$cond, getWebsiteDB());
    $list=array();
    
    while($row=mysql_fetch_assoc($result)) {      
      $resultimages = mysql_query('select * from event_images where event_id="'.$row['id'].'" order by created desc', getWebsiteDB());
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
  return getEvents('where date between '.mysql_real_escape_string($adate).' and '.mysql_real_escape_string($bdate));
  }
  
/**
  * Returns a list of events that happened or are going to happen on this week.
  */
function getEventsOnWeek() {
  return getEvents('where week(date)=week(current_date())');
  }
  
/**
  * Returns a list of events that happened or are going to happen on this month.
  */
function getEventsOnMonth() {
  return getEvents('where month(date)=month(current_date())');
  }

function addEvent($date, $location, $type, $oneline, $body, $images, $approved=false) {
    $date=mysql_real_escape_string($date);
    $location=mysql_real_escape_string($location);
    $type=mysql_real_escape_string($type);
    $oneline=mysql_real_escape_string($oneline);
    $body=mysql_real_escape_string($body);
    
    $query='insert into events values(null,"'.$date.'","'.$type.'","'.$location.'","'.$oneline.'","'.$body.'", null)';
    mysql_query($query, getWebsiteDB());
    if(mysql_affected_rows()!=1) {
        echo '<span class="error">There has been a problem while inserting event: '.mysql_affected_rows().'</span>';
        echo '<span class="error_cause">'.$query.'</span>';
        return;
    }
    
    $result=mysql_query('select LAST_INSERT_ID() as lastid from events;', getWebsiteDB());
    while($rowimages=mysql_fetch_assoc($result)) {      
        $newsid=$rowimages['lastid'];
    }
    mysql_free_result($result);
    
    foreach(explode("\n",$images) as $image) {
      mysql_query('insert into events_images values(null,'.$newsid.',"'.mysql_real_escape_string($image).'",null, null', getWebsiteDB());
    }
    
}

function deleteEvent($id) {
	$query='delete from events where id="'.mysql_real_escape_string($id).'"';
    mysql_query($query, getWebsiteDB());
    if(mysql_affected_rows()!=1) {
        echo '<span class="error">There has been a problem while deleting events.</span>';
        echo '<span class="error_cause">'.$query.'</span>';
        return;
    }
}

function updateEvent($id, $date, $location, $type, $oneline, $body, $images, $approved=false) {
    $id=mysql_real_escape_string($id);
    $date=mysql_real_escape_string($date);
    $location=mysql_real_escape_string($location);
    $type=mysql_real_escape_string($type);
    $oneline=mysql_real_escape_string($oneline);
    $body=mysql_real_escape_string($body);
    
    $query='update events set date="'.$date.'", type="'.$type.'",location="'.$location.'",shortDescription="'.$oneline.'",extendedDescription="'.$body.'" where id="'.$id.'"';
    mysql_query($query, getWebsiteDB());
    if(mysql_affected_rows()!=1) {
        echo '<span class="error">There has been a problem while updating events.</span>';
        echo '<span class="error_cause">'.$query.'</span>';
        return;
    }
}

?>