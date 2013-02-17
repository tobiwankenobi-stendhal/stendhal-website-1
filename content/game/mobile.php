<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2013  Hendrik Brummermann

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

require_once('scripts/tilesetmapping.php');
class MobilePage extends Page {
	private static $items;
	private static $classes;
	private static $itemindexes;

	public function writeHttpHeader() {
		$this->write();
		return false;
	}


	private function write() {
		MobilePage::$items = getItems();
		MobilePage::$classes = Item::getClasses();
		MobilePage::$itemindexes = getItemTilesetIndexMapping();
		$this->writeHeader();
		$this->writeMobileContent();
		$this->writeFooter();
	}

	private function writeHeader() {
		echo '<!DOCTYPE html>
		<html><head><title>Stendhal</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="/css/jquery.mobile-1.2.0.min.css" />
		<script src="/css/jquery-1.8.2.min.js"></script>
		<script src="/css/jquery.mobile-1.2.0.min.js"></script>
		</head>
		<body>';
	}

	private function writeMobileContent() {
		$this->writeItemClassesPage();
		foreach (MobilePage::$classes as $class => $temp) {
			$this->writeItemClassPage($class);
		}
	}

	private function writeItemClassesPage() {
		?>
		<div data-role="page" id="page-itemclasses">
		<div data-role="header"><h1>Stendhal Items</h1></div>

		<div data-role="content">
		<ul data-role="listview" data-inset="true" data-filter="false">
		<?php 
		foreach (MobilePage::$classes as $class => $temp) {
			echo '<li><a href="#page-itemclass-'.htmlspecialchars($class).'">'.htmlspecialchars(ucfirst($class)).'</a></li>';
		}
		?>
		</ul></div>

		<div data-role="footer"><h4>Proof of concept</h4></div>
		</div>
		<?php
	}

	private function writeItemClassPage($class) {
		echo '<div data-role="page" id="page-itemclass-'.htmlspecialchars($class).'">';
		echo '<div data-role="header">
			<h1>'.htmlspecialchars(ucfirst($class)).' - Stendhal Items</h1>
			<a href="#page-itemclasses">Back</a>
			</div>';
		
		echo '<div data-role="content">
			<h3>'.htmlspecialchars(ucfirst($class)).'</h3>
			<div data-role="collapsible-set">';
		

		foreach(MobilePage::$items as $item) {
			if($item->class==$class) {
				echo '<div data-role="collapsible" data-collapsed="true">
					<h3>'.htmlspecialchars(ucfirst($item->name)).'</h3>';
 				$this->writeItemDetails($item);
 				echo '</div>';
			}
		}
		
		echo '</div></div>
			<div data-role="footer"><h4>Proof of concept</h4></div></div>';
	}

	private function writeFooter() {
		echo '</body></html>';
	}

	private function writeItemDetails($item) {
		$sprite = substr($item->gfx, 13);
		if (isset(MobilePage::$itemindexes[$sprite])) {
			$offset = MobilePage::$itemindexes[$sprite] * 32;
			echo '<div style="width:32px; height:32px; background-image:url(\'/images/buttons/item-tileset.png\'); background-position: -'.$offset.'px 0px"></div>';
		}

		echo '<div>';
		if ($item->description == '') {
			echo 'No description. Please write one.';
		} else {
			echo htmlspecialchars($item->description);
		}
		echo '</div>';


		if (count($item->attributes) > 0) {
			echo '<h4>Attributes</h4>';
			echo '<table>';
			foreach ($item->attributes as $label=>$data) {
				echo '<tr><td scope="row">'.htmlspecialchars(ucfirst($label)).':</td>';
				echo '<td>'.htmlspecialchars($data).'</td></tr>';
			}
			echo '</table>';
		}

		if (count($item->susceptibilities) > 0) {
			echo '<h4>Resistances</h4>';
			echo '<table>';
			foreach ($item->susceptibilities as $label=>$data) {
				echo '<tr><td scope="row">'.htmlspecialchars(ucfirst($label)).':</td>';
				echo '<td>'.htmlspecialchars($data).'%</td></tr>';
			}
			echo '</table>';
		}



		$found = false;
		$monsters = getMonsters();
		foreach ($monsters as $monster) {
			foreach ($monster->drops as $drop) {
				if ($drop['name']==$item->name) {
					$found = true;
					break;
				}
			}
		}

		if ($found) {
			echo '<h4>Dropped by</h4>';
			echo '<table>';
			foreach ($monsters as $monster) {
					foreach ($monster->drops as $drop) {
					if ($drop['name']==$item->name) {
						echo '<tr><td>'.htmlspecialchars(ucfirst($monster->name)).'</td>';
						echo '<td>'.htmlspecialchars($drop['quantity']).'</td>';
						echo '<td>'.formatNumber($drop['probability']).'%</td></tr>';
					}
				}
			}
			echo '</table>';
		}
	}
	
	function writeContent() {
		// do nothing
	}
	
}
$page = new MobilePage();
?>