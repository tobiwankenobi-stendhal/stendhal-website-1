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
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		$this->int = $int;
		$this->file = $file;
	}

	public static function getZones() {
		global $cache;
		$zoneXmlMap = array();
		$zoneAttrMap = array();
		if(sizeof(Zone::$zones) == 0) {
			Zone::$zones = $cache->fetchAsArray('stendhal_zones');
		}
		if((Zone::$zones !== false) && (sizeof(Zone::$zones) != 0)) {
			return Zone::$zones;
		}

		// read xml files from disk
		$configurationFile="data/conf/zones.xml";
		$configurationBase='data/conf/';
		
		$content = file($configurationFile);
		$temp = implode('',$content);
		$files = XML_unserialize($temp);
		$files = $files['groups'][0]['group'];

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
		$list = array();
		foreach ($zoneXmlMap as $name => $xml) {
			$x = $zoneAttrMap[$name]['x'];
			$y = $zoneAttrMap[$name]['y'];
			$z = $zoneAttrMap[$name]['level'];
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
						$x = $tempX + $portal['x'];
						$y = $tempY + $portal['y'];
						$z = $zoneAttrMap[$destination['zone']]['level'];
					}
				}
			}
			$list[$name] = new Zone($name, $x, $y, $z, $int, $file);
		}

		Zone::$zones = $list;
		$cache->store('stendhal_zones', new ArrayObject($list));
		return $list;
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
}