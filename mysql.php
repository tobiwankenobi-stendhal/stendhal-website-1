<?php
/***************************************************************************
 *                      (C) Copyright 2008 - Stendhal                      *
 ***************************************************************************
 ***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
 
include_once('configuration.php');

$websitedb=-1;
$gamedb=-1;

function getWebsiteDB() {
  global $websitedb;
  return $websitedb;
}

function getGameDB() {
  global $gamedb;
  return $gamedb;
}

function connect() {
    global $websitedb,$gamedb;
    $websitedb=mysql_connect(STENDHAL_WEB_HOSTNAME,STENDHAL_WEB_USERNAME,STENDHAL_WEB_PASSWORD, true);
    @mysql_select_db(STENDHAL_WEB_DB, $websitedb) or die( "Unable to select Website database");

    $gamedb=mysql_connect(STENDHAL_GAME_HOSTNAME,STENDHAL_GAME_USERNAME,STENDHAL_GAME_PASSWORD, true);
    @mysql_select_db(STENDHAL_GAME_DB, $gamedb) or die( "Unable to select Game database");
}

function disconnect() {
    global $websitedb,$gamedb;
    mysql_close($websitedb);
    mysql_close($gamedb);
}
?>