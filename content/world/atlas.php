<!DOCTYPE HTML>
<html>
<head>
<title>Stendhal Map</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
var mapTypes = {};

// set up the map types
mapTypes['outside'] = {
	getTileUrl: function(coord, zoom) {
		return getHorizontallyRepeatingTileUrl(coord, zoom, function(coord, zoom) {
			var bound = Math.pow(2, zoom);
			return "http://arianne.sourceforge.net/stendhal/map/" + zoom + "-" + coord.x + "-" + coord.y + ".png";
		});
	},
	tileSize: new google.maps.Size(256, 256),
	isPng: true,
	maxZoom: 5,
	minZoom: 1,
	name: 'Outside',
	credit: 'Stendhal',
	projection: {
		fromLatLngToPoint: function(latLng) {
			console.log.info(latLng.lng(), latLng.lat());
			return new Point(latLng.lng() - 500000, latLng.lat() - 500000);
		},
		fromPointToLatLng: function(point) {
			console.log.info(point.y, point.x);
			return new LatLng(point.y, point.x);
		}
	}
};


// Normalizes the tile URL so that tiles repeat across the x axis (horizontally) like the
// standard Google map tiles.
function getHorizontallyRepeatingTileUrl(coord, zoom, urlfunc) {
	var y = coord.y;
	var x = coord.x;

	// tile range in one direction range is dependent on zoom level
	// 0 = 1 tile, 1 = 2 tiles, 2 = 4 tiles, 3 = 8 tiles, etc
	var tileRange = 1 << zoom;

	// don't repeat across y-axis (vertically)
	if (y < 0 || y >= tileRange) {
		return null;
	}

	// don't repeat across x-axis
	if (x < 0 || x >= tileRange) {
		return null;
	}
	return urlfunc({x:x,y:y}, zoom)
}

var map;
var mapTypeIds = [];

// Setup a copyright/credit line, emulating the standard Google style
var creditNode = document.createElement('div');
creditNode.id = 'credit-control';
creditNode.style.fontSize = '11px';
creditNode.style.fontFamily = 'Arial, sans-serif';
creditNode.style.margin = '0 2px 2px 0';
creditNode.style.whitespace = 'nowrap';
creditNode.index = 0;

function setCredit(credit) {
	creditNode.innerHTML = credit + ' -';
}

function initialize() {

	// push all mapType keys in to a mapTypeId array to set in the mapTypeControlOptions
	for (var key in mapTypes) {
		mapTypeIds.push(key);
	}

	var mapOptions = {
		zoom: 0,
		center: new google.maps.LatLng(0, 0),
		mapTypeControlOptions: {
			mapTypeIds: mapTypeIds,
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		}
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

	// push the credit/copyright custom control
	map.controls[google.maps.ControlPosition.BOTTOM_RIGHT].push(creditNode);

	// add the new map types to map.mapTypes
	for (key in mapTypes) {
		map.mapTypes.set(key, new google.maps.ImageMapType(mapTypes[key]));
	}


	// handle maptypeid_changed event to set the credit line
	google.maps.event.addListener(map, 'maptypeid_changed', function() {
		setCredit(mapTypes[map.getMapTypeId()].credit);
	});

	// start with the moon map type
	map.setMapTypeId('outside');

	var myLatlng = new google.maps.LatLng(50, 50);
	var marker = new google.maps.Marker({
		position: myLatlng, 
		map: map, 
		title:"Hello World!"
	});
}
</script>
</head>
<body onload="initialize()">
	<div id="map_canvas" style="width: 640px; height: 480px;">map div</div>
</body>
</html>
