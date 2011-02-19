<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2010  The Arianne Project
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


require_once('configuration.php');
require_once('configuration.default.php');

$websitedb = -1;
$gamedb = -1;

function getWebsiteDB() {
	global $websitedb;
	
	return $websitedb;
}

function getGameDB() {
	global $gamedb;
	
	return $gamedb;
}


function getWikiDB() {
	global $wikidb;
	if (!isset($wikidb)) {
		$wikidb = mysql_connect(STENDHAL_WIKI_HOSTNAME, STENDHAL_WIKI_USERNAME, STENDHAL_WIKI_PASSWORD, true);
		@mysql_select_db(STENDHAL_WIKI_DB, $wikidb) or die( "Unable to select Wiki database");
		mysql_query('set character set utf8;', $wikidb);
	}
	return $wikidb;
}

function getTestDB() {
	global $testdb;
	if (!isset($testdb)) {
		$wikidb = mysql_connect(STENDHAL_GAME_HOSTNAME, STENDHAL_GAME_USERNAME, STENDHAL_GAME_PASSWORD, true);
		@mysql_select_db(STENDHAL_TEST_DB, $testdb) or die( "Unable to select test database");
		mysql_query('set character set utf8;', $testdb);
	}
	return $testdb;
}

function connect() {
	global $websitedb, $gamedb;
	$websitedb = mysql_connect(STENDHAL_WEB_HOSTNAME, STENDHAL_WEB_USERNAME, STENDHAL_WEB_PASSWORD, true);
	@mysql_select_db(STENDHAL_WEB_DB, $websitedb) or die( "Unable to select Website database");
	mysql_query('set character set utf8;', $websitedb);

	$gamedb = mysql_connect(STENDHAL_GAME_HOSTNAME, STENDHAL_GAME_USERNAME, STENDHAL_GAME_PASSWORD, true);
	@mysql_select_db(STENDHAL_GAME_DB, $gamedb) or die( "Unable to select Game database");
	mysql_query('set character set utf8;', $gamedb);
}

function disconnect() {
	global $websitedb, $gamedb, $wikidb;
	mysql_close($websitedb);
	mysql_close($gamedb);
	if (isset($wikidb)) {
		mysql_close($wikidb);
	}
}
