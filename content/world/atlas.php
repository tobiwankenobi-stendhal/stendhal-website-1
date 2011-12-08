<?php
class AtlasPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Atlas'.STENDHAL_TITLE.'</title>';
		$this->includeJs();
	}

	function writeContent() {
		echo '<div id="map_canvas" style="width: 570px; height: 380px;"></div><p>&nbsp;</p>';
		startBox('Extended information');
		echo '<p>There are in detail information about the <a href="http://stendhalgame.org/wiki/Semos">various regions</a> and <a href="http://stendhalgame.org/wiki/Semos_Dungeons">dungeons</a> on the Stendhal Wiki.</p>';
		echo '<p>Here is a map with <a href="http://arianne.sourceforge.net/screens/stendhal/world_labelled.png">zone names</a> and a map with <a href="/world/atlas.html?poi=dungeon">dungeon entrances</a>.</p>';
		endBox();

		$zoom = 2;
		$focusX = 500200;
		$focusY = 500100;

		$zones = Zone::getZones();

		// if there is exactly one poi, focus on that
		if (isset($_REQUEST['poi']) && strpos($_REQUEST['poi'], '.') === false) {
			$pois = PointofInterest::getPOIs();
			if (isset($pois[$_REQUEST['poi']])) {
				$poi = $pois[$_REQUEST['poi']];
				$zoom = 5;
				$focusX = $poi->gx;
				$focusY = $poi->gy;
			}
		}

		// focus on position of current player and display a marker
		if (isset($_REQUEST['me'])) {
			$coordinates = explode('.', $_REQUEST['me']);
			$zone = $zones[$coordinates[0]];
			if (isset($zone) && isset($zone->x)) {
				$meZone = $coordinates[0];
				$meX = $zone->x + intval($coordinates[1]);
				$meY = $zone->y + intval($coordinates[2]);
				if ($zone->z === 0) {
					$zoom = 5;
				} else {
					$zoom = 4;
				}
				$focusX = $meX;
				$focusY = $meY;
			}
		}

		// if there is a focus parameter, use it
		if (isset($_REQUEST['focus'])) {
			$zoom = 5;
			$coordinates = explode('.', $_REQUEST['focus']);
			if (count($coordinates) === 1) {
				$pois = PointofInterest::getPOIs();
				$poi = $pois[$coordinates[0]];
				if (isset($poi)) {
					$focusX = $poi->gx;
					$focusY = $poi->gy;
				}
			} else if (count($coordinates) === 2) {
				$focusX = $coordinates[0];
				$focusY = $coordinates[0];
			} else if (count($coordinates) === 3) {
				$zone = $zones[$coordinates[0]];
				if (isset($zone) && isset($zone->x)) {
					$focusX = $zone->x + intval($coordinates[1]);
					$focusY = $zone->y + intval($coordinates[2]);
				}
			}
		}

		echo "\n".'<div class="data">';
		echo '<span id="data-center" data-x="'.htmlspecialchars($focusX).'" data-y="'.htmlspecialchars($focusY).'" data-zoom="'.htmlspecialchars($zoom).'"></span>';
		if (isset($meX)) {
			echo '<span id="data-me" data-x="'.htmlspecialchars($meX).'" data-y="'.htmlspecialchars($meY).'" ';
			echo 'data-zone="'.htmlspecialchars($meZone).'" ';
			echo 'data-local-x="'.htmlspecialchars($coordinates[1]).'" data-local-y="'.htmlspecialchars($coordinates[2]).'"></span>';
		}
		echo '<span id="data-pois" data-pois="'.htmlspecialchars(json_encode(PointofInterest::getPOIs())).'"></span>';
		echo '</div>';

		echo '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
	}
}
$page = new AtlasPage();