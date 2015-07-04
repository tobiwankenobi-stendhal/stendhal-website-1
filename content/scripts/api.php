<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2010  Stendhal

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

class APIPage extends Page {

	public function writeHttpHeader() {
		header("Content-Type: text/javascript", true);
		if ($_REQUEST['method'] == 'isNameAvailable') {
			$ignoreAccount = false;
			if (isset($_REQUEST['ignoreAccount'])) {
				$ignoreAccount = $_REQUEST['ignoreAccount'];
			}
			$this->isNameAvailable($_REQUEST['param'], $ignoreAccount);
		} else if ($_REQUEST['method'] == 'traceroute') {
			$ip = false;
			if (isset($_REQUEST['ip'])) {
				$ip = $_REQUEST['ip'];
			}
			$this->traceroute($_REQUEST['fast'], $ip);
		} else if ($_REQUEST['method'] == 'rankhistory') {
			$this->rankhistory($_REQUEST['param']);
		} else if ($_REQUEST['method'] == 'login') {
			$this->login($_POST['username'], $_POST['password']);
		} else if ($_REQUEST['method'] == 'data') {
			$categories = '';
			if (isset($_REQUEST['categories'])) {
				$categories = $_REQUEST['categories'];
			}
			$this->getData($categories);
		} else if ($_REQUEST['method'] == 'screenshots') {
			$this->getScreenshots();
		} else if ($_REQUEST['method'] == 'pushnotification') {
			$this->pushNotification($_REQUEST['param']);
		} else {
			$this->unknown($_REQUEST['param']);
		}
		return false;
	}

	/**
	 * checks if a name is available for account or character creation
	 *
	 * @param $name account/character name to check
	 * @param $ignoreAccount ignore this account on the character check (to allow someone to create a character with his own account name)
	 */
	public function isNameAvailable($name, $ignoreAccount) {
		$res = array();
		$res['name'] = $name;
		$res['result'] = Account::isNameAvailable($name, $ignoreAccount);
		echo json_encode($res);
	}

	public function traceroute($fast, $ip) {
		// allow only admins to specify an ip-address
		if (!$ip || getAdminLevel() < 100) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		

		// validate ip
		if (!preg_match('/^[0-9a-fA-F.:]+$/', $ip)) {
			echo 'throw new Exception("Invalid IP")';
			return;
		}
		$netstats = new Netstats();
		echo $netstats->traceroute($ip, $fast, 3);
	}

	public function rankhistory($name) {
		$res = getHallOfFameHistory($name);
		echo json_encode($res);
	}

	public function login($username, $password) {
		if (!isset($username)) {
			echo 'FAILED';
			return;
		}
		$result = Account::tryLogin("password", $username, $password);
		if (! ($result instanceof Account)) {
			echo htmlspecialchars($result);
		}

		session_start();
		$_SESSION['account'] = $result;
		$_SESSION['marauroa_authenticated_username'] = $result->username;
		$_SESSION['csrf'] = createRandomString();

		fixSessionPermission();
		echo 'OK';
	}

	/**
	 * returns an error response because the method is not known
	 *
	 * @param $param ignored
	 */
	public function unknown($param) {
		header('HTTP/1.1', true, 400);
		echo 'throw new Exception("Unknown method")';
	}

	/**
	 * returns a json object with data about the game world
	 *
	 * @param string $categories
	 */
	public function getData($categories) {
		$c = explode(',', $categories);
		$res = array();
		if (in_array('creatures', $c)) {
			$res['creatures'] = getMonsters();
		}
		if (in_array('items', $c)) {
			$res['items'] = getItems();
		}
			if (in_array('npcs', $c)) {
			$res['npcs'] = NPC::getNPCS();
		}
			if (in_array('pois', $c)) {
			$res['pois'] = PointofInterest::getPOIs();
		}
		if (in_array('zones', $c)) {
			$res['zones'] = Zone::getZones();
		}
		echo json_encode($res);
	}

	public function getScreenshots() {
		$sql = "SELECT page_title As href, cl_sortkey_prefix As title FROM categorylinks, page WHERE cl_to='Stendhal_Slideshow' AND cl_type='file' AND page_id=cl_from ORDER BY page_touched DESC, page_id DESC";
		$data = fetchToArray($sql, getWikiDB());
		for ($i = 0; $i < count($data); $i++) {
			$hash = md5($data[$i]['href']);
			$data[$i]['href'] = '/wiki/images/' . $hash{0} . '/' . substr( $hash, 0, 2 ) . '/' . urlencode($data[$i]['href']);
		}
		echo json_encode($data);
	}
	
	
	public function pushNotification($param) {
		if (!checkLogin()) {
			http_response_code(403);
			echo 'Not logged in.';
			return;
		}

		if (($_REQUEST['csrf'] !== $_SESSION['csrf'])) {
			http_response_code(403);
			echo 'Invalid csrf';
			echo $_POST['csrf'] . '--!--' . $_SESSION['csrf'];
			return;
		}

		if ($param === 'subscribe') {
			addAccountLink($_SESSION['account']->username, 'push', $_REQUEST['subscriptionId'], '', $_REQUEST['endpoint']);
		} else if ($param === 'unsubscribe') {
			delAccountLink($_SESSION['account']->username, 'push', $_REQUEST['subscriptionId']);
		}
	}
}
$page = new APIPage();
