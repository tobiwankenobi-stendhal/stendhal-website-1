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
 * This file is the PHP code that generate each of the website sections. 
 */
include('scripts/mysql.php');
include('scripts/xml.php');

function startBox($title) {
  echo '<div class="box">';
  echo '<div class="boxTitle">'.$title.'</div>';
  echo '<div class="boxContent">';
  }

function endBox() {
  echo '</div></div>';
  }
  
function showKnownBugNotice() {
if(STENDHAL_PLEASE_MAKE_IT_FAST){
  ?>
    <div class="notice">
      The following data is not correct and that's a known bug.<br/>
      Please don't report it.<br/>
      As soon as we fix this problem you will see real data from game server.
    </div>
  <?php
 }
}
  
include('scripts/screenshots.php');
include('scripts/events.php');
include('scripts/news.php');
include('scripts/players.php');
include('scripts/monsters.php');
include('scripts/items.php');
include('scripts/statistics.php');
include('scripts/cache.php');

?>
