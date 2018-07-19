<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2018  The Arianne Project
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

class DB {
	private static $game;
	private static $web;
	private static $wiki;

	public static function game() {
		if (!isset(DB::$game)) {
			try {
				$connection = STENDHAL_GAME_CONNECTION;
				if (isset($_REQUEST) && isset($_REQUEST['test']) && $_REQUEST['test'] == 'testdb') {
					$connection = STENDHAL_TEST_CONNECTION;
				}
				DB::$game = new PDO($connection, STENDHAL_GAME_USERNAME, STENDHAL_GAME_PASSWORD);
				DB::$game->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				DB::$game->exec('set character set utf8');
			} catch(PDOException $e) {
				error_log('ERROR connecting to game database: ' . $e->getMessage());
				die(databaseConnectionErrorMessage('game database'));
			}
		}
		return DB::$game;
	}

	public static function web() {
		if (!isset(DB::$web)) {
			try {
				DB::$web = new PDO(STENDHAL_WEB_CONNECTION, STENDHAL_WEB_USERNAME, STENDHAL_WEB_PASSWORD);
				DB::$web->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				DB::$web->exec('set character set utf8');
			} catch(PDOException $e) {
				error_log('ERROR connecting to web database: ' . $e->getMessage());
				die(databaseConnectionErrorMessage('game database'));
			}
		}
		return DB::$web;
	}

	public static function wiki() {
		if (!isset(DB::$wiki)) {
			try {
				DB::$wiki = new PDO(STENDHAL_WIKI_CONNECTION, STENDHAL_WIKI_USERNAME, STENDHAL_WIKI_PASSWORD);
				DB::$wiki->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				DB::$wiki->exec('set character set utf8');
			} catch(PDOException $e) {
				error_log('ERROR connecting to wiki database: ' . $e->getMessage());
				die(databaseConnectionErrorMessage('game database'));
			}
		}
		return DB::$wiki;
	}	
}


if (!function_exists('mysql_real_escape_string')) {
	function mysql_real_escape_string($param) {
		$quoted = DB::game()->quote($param);
		return substr($quoted, 1, -1);
	}
} else {
	// connect to database old-style so that mysql_real_escape_string can be used
	$gamedb = mysql_connect(STENDHAL_GAME_HOSTNAME, STENDHAL_GAME_USERNAME, STENDHAL_GAME_PASSWORD, true);
	mysql_select_db(STENDHAL_GAME_DB, $gamedb) or die( databaseConnectionErrorMessage('game database'));
	mysql_query('set character set utf8;', $gamedb);
}


function databaseConnectionErrorMessage($message) {
	@header('HTTP/1.0 500 Maintenance', true, 500);
		?>
	<html>
		<head>
			<title>Stendhal</title><meta name="robots" content="noindex">
		</head>
		<body>
			<div style='border:5px solid red; font-size:200%; padding:1em; margin:2em'>
				<p><b>We are currently doing <?php echo htmlspecialchars($message)?> maintenance.</b></p>
				<p>We apologize for the inconvenience.</p>
			</div>
		</body>
	</html>
	<?php
}

function disconnect() {
}

function queryFirstCell($query, $connection) {
	$stmt = $connection->query($query);
	$res = $stmt->fetch(PDO::FETCH_NUM);
	$stmt->closeCursor();
	return $res[0];
}

function fetchToArray($query, $connection) {
	$rows = $connection->query($query);
	$res = array();
	
	foreach($rows as $row) {
		$res[] = $row;
	}

	return $res;
}
