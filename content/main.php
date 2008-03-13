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
?>

<div id="oneLineDescription">
   <img src="images/playit.gif" alt="play stendhal"/>
   <span>Stendhal is a fully fledged free open source multiplayer online 
   adventures game (MMORPG) developed using the Arianne game system.</span>
</div>
<div id="newsArea">
  <?php
  foreach(getNews() as $i) {
   $i->show();
  }
  ?>
</div>
