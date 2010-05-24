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
	private $username;
	private $characterOkay = FALSE;
	private $character;
	private $seed;

	public function __construct__() {
		$this->username = $_SESSION['username'];
		$this->loggedIn = isset($this->username);
		$this->character = $_GET['character'];
		if ($this->loggedIn && isset($this->character) && strlen($this->character) > 0) {
			$characterOkay = verifyCharacterBelongsToUsername($this->username, $this->character);
		}
	}

	public function writeHttpHeader() {
		header('Cache-Control: must-revalidate, private');

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
		} else if (!$this->characterOkay) {
			echo '<p>The specified character '.htmlspecialchars($this->character).' does not belong to your account.</p>';
		} else {
			echo '<p>An unknown error occured.</p>';
		}
		endBox();
	}

	/**
	 * creates and stores a seed.
	 */
	private function createSeed() {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    	$this->seed = '';
		for ($i = 0; $i < 16; $i++) {
        	$seed .= $characters[mt_rand(0, strlen($characters))];
		}
		storeSeed($this->username, $_SERVER['REMOTE_ADDR'], $this->seed, 1);
	}
	
	private function streamWebstart() {
		echo $this->seed;
	}
}
$page = new StarterPage();
?>