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

class ServerStatistics {
  public $diff;
  public $date;
  public $bytes_send;
  public $bytes_recv;
  public $players_online;
  
  function __construct($diff, $date, $send, $recv, $online) {
    $this->diff=$diff;
    $this->date=$date;
    $this->bytes_send=$send;
    $this->bytes_recv=$recv;
    $this->players_online=$online;
  }
  
  function isOnline() {
    if(isset($this->diff) && $this->diff<300) {
      return true;
    } else {
      return false;
    }
  }
}

function getServerStats() {
    $result = mysql_query('SELECT *, now()-timedate As diff FROM statistics ORDER BY id DESC LIMIT 1', getGameDB());
    
    while($row=mysql_fetch_assoc($result)) {      
      $server=new ServerStatistics($row['diff'],$row['timedate'],$row['bytes_send'],$row['bytes_recv'],$row['players_online']);
    }
    
    mysql_free_result($result);
	
    return $server;
}

function getAmountOfPlayersOnline() {
    $result = mysql_query('select count(*) as amount from character_stats where online=1', getGameDB());
    
    while($row=mysql_fetch_assoc($result)) {      
      $amount=$row['amount'];
    }
    
    mysql_free_result($result);
	
    return $amount;
}

?>