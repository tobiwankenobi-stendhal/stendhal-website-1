<div id="description">
  <div id="oneLineDescription">
    Stendhal is a full fledged open source multiplayer online adventures game (MMORPG) developed using the Arianne game development system.
  </div>
    Stendhal features a new, rich and expanding world in which you can explore towns, buildings, plains, caves and dungeons.<br>
    You will meet NPCs and acquire tasks and quests for valuable experience and cold hard cash.<br>
    Your character will develop and grow and with each new level up become stronger and better. With the money you acquire you can buy new items and improve your armour and weapons.<br>
    And for the blood thirsty ones of you satisfy your killing desires by roaming the world in search of evil monsters!<br>
</div>
<div id="newsArea">
  <?php
  foreach(getNews() as $i) {
   $i->show();
  }
  ?>
</div>
