<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2010 The Arianne Project

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

class StarterPage extends Page {
	private $loggedIn;
	private $characterOkay = FALSE;
	private $character;

	public function __construct__() {
		$username = $_SESSION['username'];
		$this->loggedIn = isset($username);
		$this->character = $_REQUEST['character'];
		if ($this->loggedIn && isset($this->character) && strlen($this->character) > 0) {
			$characterOkay = verifyCharacterBelongsToUsername($username, $this->character);
		}
	}

	public function writeHttpHeader() {
		header('Cache-Control: must-revalidate, private');
		header('Pragma: no-cache');

		// if everything is okay, we proceed with the login process
		if ($this->loggedIn && $this->characterOkay) {
			$this->createSeed();
			$this->streamWebstart();
			// don't render the normal web page
			return false;
		}

		// something went wrong, render the normal web page 
		// and add an error message later
		return true;
	}

	public function writeHtmlHeader() {
		echo '<meta name="robots" content="noindex">'."\n";
		echo '<title>Starter'.STENDHAL_TITLE.'</title>';
	}

	function writeContent() {
		startBox("Starter");
		if(!isset($_SESSION['username'])) {
			echo '<p>Please login to start the Stendhal Client.</p>';
		} else {
			echo '<p></p>';
		}
		endBox();
	}

}
$page = new LoginHistoryPage();
?>