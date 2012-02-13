<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2012 The Arianne Project

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

require_once 'mycharacters.php';

class FBCanvasPage extends Page {

	public function writeHttpHeader() {
		$this->writePageStart();
		$this->writeContent();
		$this->writePageEnd();
		return false;
	}

	function writePageStart() {
		echo '<!DOCTYPE html>';
		echo '<html><head>';
		echo '<title>Stendhal</title>';
		?>
		<style type="text/css">
.fbcanvas {

	width: 570px; 
	border: 1px solid black
	margin: 0;
	padding: 0;
	font-family: Arial, Helvetica, "Liberation Sans", "Lucida Sans", Sans-serif;
	font-size: 12pt;
}

.box {
	margin: 4px;
	padding: 0;
	border: solid 2px black;
	background-color: white;
}

.boxTitle {
	margin: 0;
	padding: 3px;
	color: white;
	font-weight: bolder;
	background-image: url("/data/gui/panelwood119.jpg");
	background-repeat: repeat;
	border: outset 2px brown;
}

.boxContent {
	color: Black;
	padding: 4px;
}

.onlinePlayer {
	width: 80px;
	text-align: center;
	float: left;
	font-size: 12px;
	font-weight: bold;
}

.characterHeight {
	height: 140px;
}

.onlineLink {
	text-decoration: none;
	color: black;
}
span.block {
	display: block;
	overflow-x: hidden;
}
		</style>
		<?php
		echo '</head><body>';
	}

	function writeContent() {
		echo '<div class="fbcanvas">';
		$chars = new MyCharactersPage();
		$chars->writeCharacterList(6);
		echo '</div>';
	}

	function writePageEnd() {
		echo '</div>';
		echo '</body></html>';
	}
}
$page = new FBCanvasPage();