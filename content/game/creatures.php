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

$monsters=getMonsters();
$classes=Monster::getClasses();
?>

<div style="float: left; width: 50%"><?php
startBox('Most killed');
$result=getMostKilledMonster($monsters);
if($result==null) {
	$result=array($monsters[0],0);
}

list($m, $amount)=$result;
echo '<div><a class="creature" href="?id=content/scripts/monster&name='.$m->name.'">';
echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
echo '  <div class="creature_name">'.$m->name.'</div>';
echo ' </a>';
echo '  <div class="creature_killed">It was killed '.$amount.' times</div>';
echo '</div>';
endBox();
?></div>
<div style="float: left; width: 50%"><?php
startBox('Best Player killer');
$result=getBestKillerMonster($monsters);
if($result==null) {
	$result=array($monsters[0],0);
}

list($m, $amount)=$result;
echo '<div><a class="creature" href="?id=content/scripts/monster&name='.$m->name.'">';
echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
echo '  <div class="creature_name">'.$m->name.'</div>';
echo ' </a>';
echo '  <div class="creature_killed">It has killed '.$amount.' players</div>';
echo '</div>';
endBox();
?></div>

<div style="float: left; width: 100%"><?php

startBox('Creatures');
?>
<form method="get" action="">
  <input type="hidden" name="id" value="content/scripts/monster">
  <input type="text" name="name" maxlength="40">
  <input type="submit" name="sublogin" value="Search">
</form>

<?php 
foreach($monsters as $m) {
	echo '<div class="creature"><a class="creature" href="?id=content/scripts/monster&name='.$m->name.'">';
	echo '  <img class="creature" src="'.$m->gfx.'" alt="'.$m->name.'"/>';
	echo '  <div class="creature_name">'.$m->name.'</div>';
	echo ' </a>';
	echo '  <div class="creature_level">Level '.$m->level.'</div>';
	echo '</div>';
}
?>
<div style="clear: left;"></div>
<?php

endBox();
?></div>
