<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2011 Hendrik Brummermann

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
/*
 * A class representing a zone.
 */
class Zone {
	private static $zones;

	public $name;
	public $x;
	public $y;
	public $z;
	public $int;
	public $file;

	function __construct($name, $x, $y, $z, $int, $file) {
		$this->name = name;
		$this->x = intval($x);
		$this->y = intval($y);
		$this->z = intval($z);
		$this->int = $int;
		$this->file = $file;
	}

	public static function getZones() {
		global $cache;
		if(sizeof(Zone::$zones) == 0) {
			Zone::$zones = $cache->fetchAsArray('stendhal_zones');
		}
		if((Zone::$zones !== false) && (sizeof(Zone::$zones) != 0)) {
			return Zone::$zones;
		}

		Zone::loadZoneXmlData();
		Zone::$zones = $cache->fetchAsArray('stendhal_zones');
		return Zone::$zones;
	}

	static function loadZoneXmlData() {
		global $cache;

		// read list of xml files from disk
		$configurationFile="data/conf/zones.xml";
		$configurationBase='data/conf/';
		
		$content = file($configurationFile);
		$temp = implode('',$content);
		$files = XML_unserialize($temp);
		$files = $files['groups'][0]['group'];

		$zoneXmlMap = array();
		$zoneAttrMap = array();
		
		// create a map of xml fragements
		foreach ($files as $file) {
			if (isset($file['uri'])) {
				$content = file($configurationBase.$file['uri']);
				$temp = implode('', $content);
				$zones =  XML_unserialize($temp);
				$zones = $zones['zones'][0]['zone'];
				for ($i=0; $i < sizeof($zones) / 2; $i++) {
					$name = $zones[$i.' attr']['name'];
					$zoneXmlMap[$name] = $zones[$i];
					$zoneAttrMap[$name] = $zones[$i.' attr'];
				}
			}
		}

		// create zone objects
		$zoneList = array();
		$poiList = array();
		foreach ($zoneXmlMap as $name => $xml) {

			// create zone object
			$zoneX = $zoneAttrMap[$name]['x'];
			$zoneY = $zoneAttrMap[$name]['y'];
			$zoneZ = $zoneAttrMap[$name]['level'];
			$file = $zoneAttrMap[$name]['file'];
			$int = !isset($z);

			// try to resolve internal zones to their place in the world
			if ($int) {
				$destination = Zone::getFirstPortalDestination($xml);
				if (!isset($destination)) {
					continue;
				}
				$destZone = $zoneXmlMap[$destination['zone']];
				if (!isset($destZone)) {
					continue;
				}
				$tempX = $zoneAttrMap[$destination['zone']]['x'];
				$tempY = $zoneAttrMap[$destination['zone']]['y'];
				if (isset($tempX)) {
					$portal = Zone::getNamedPortalInZone($destZone, $destination['ref']);
					if (isset($portal)) {
						$zoneX = $tempX + $portal['x'];
						$zoneY = $tempY + $portal['y'];
						$zoneZ = $zoneAttrMap[$destination['zone']]['level'];
					}
				}
			}
			$zoneList[$name] = new Zone($name, $zoneX, $zoneY, $zoneZ, $int, $file);
			$pois = Zone::createPOIsFromZone($zoneList[$name], $xml);
			if (isset($pois)) {
				$poiList = array_merge($poiList, $pois);
			}
		}
		$cache->store('stendhal_pois', new ArrayObject($poiList));
		$cache->store('stendhal_zones', new ArrayObject($zoneList));
	}

	private static function getFirstPortalDestination($xml) {
		$portal = $xml['portal'];
		if (isset($portal)) {
			if (is_array($portal[0]) && is_array($portal[0]['destination']) && is_array($portal[0]['destination']['0 attr'])) {
				return $portal[0]['destination']['0 attr'];
			}
		}
		return null;
	}

	private static function getNamedPortalInZone($zone, $name) {
		$portals = $zone['portal'];
		if (isset($portals)) {
			for ($i=0; $i < sizeof($portals) / 2; $i++) {
				if ($portals[$i.' attr']['ref'] == $name) {
					return $portals[$i.' attr'];
				}
			}
		}
		return null;
	}

	private static function createPOIsFromZone($zone, $xml) {
		$pois = $xml['point-of-interest'];
		if (!isset($pois)) {
			return null;
		}
		$res = array();
		for ($i=0; $i < sizeof($pois) / 2; $i++) {
			$attr = $pois[$i.' attr'];
			$children = $pois[$i];
			
			$res[$children['name'][0]] = new PointofInterest($zone->name, 
				$attr['x'], $attr['y'], 
				$zone->x + intval($attr['x']), $zone->y + intval($attr['y']),
				$children['name'][0], $children['type'][0],
				$children['description'][0], $children['url'][0]);
		}
		return $res;
	}
}

/**
 * a point of interest on the map
 */
class PointofInterest {
	private static $pois;

	public $zoneName;
	public $x;
	public $y;
	public $gx;
	public $gy;
	public $name;
	public $type;
	public $description;
	public $url;

	function __construct($zoneName, $x, $y, $gx, $gy, $name, $type, $description, $url) {
		$this->zoneName = $zoneName;
		$this->x = intval($x);
		$this->y = intval($y);
		$this->gx = intval($gx);
		$this->gy = intval($gy);
		$this->name = $name;
		$this->type = $type;
		$this->description = $description;
		$this->url = $url;
	}

	public static function getPOIs() {
		global $cache;
		if(sizeof(PointofInterest::$pois) == 0) {
			PointofInterest::$pois = $cache->fetchAsArray('stendhal_pois');
		}
		if((PointofInterest::$pois !== false) && (sizeof(PointofInterest::$pois) != 0)) {
			return PointofInterest::$pois;
		}
		
		Zone::loadZoneXmlData();
		PointofInterest::$pois = $cache->fetchAsArray('stendhal_pois');
		return PointofInterest::$pois;
	}
}