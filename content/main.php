<div id="oneLineDescription">
   <img src="images/playit.gif" alt="play stendhal"/>
   <span>Stendhal is a fully fledged free open source multiplayer online adventures game (MMORPG) developed using the Arianne game system.</span>
</div>
<div id="newsArea">
  <?php
  foreach(getNews() as $i) {
   $i->show();
  }
  ?>
</div>
