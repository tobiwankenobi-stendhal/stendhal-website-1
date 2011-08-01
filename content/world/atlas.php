<!DOCTYPE HTML>
<html>
<head>
<title>Stendhal Map</title>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

function EuclideanProjection() {
	var EUCLIDEAN_RANGE = 256;
	this.pixelOrigin_ = new google.maps.Point(EUCLIDEAN_RANGE / 2, EUCLIDEAN_RANGE / 2);
	this.pixelsPerLonDegree_ = EUCLIDEAN_RANGE / 360;
	this.pixelsPerLonRadian_ = EUCLIDEAN_RANGE / (2 * Math.PI);
	this.scaleLat = 2;      // Height - multiplication scale factor
	this.scaleLng = 1;      // Width - multiplication scale factor
	this.offsetLat = 0;     // Height - direct offset +/-
	this.offsetLng = 0;     // Width - direct offset +/-
};

EuclideanProjection.prototype.fromLatLngToPoint = function(latLng, opt_point) {
	console.info("fromLatLngToPoint");
	var point = opt_point || new google.maps.Point(0, 0);
	var origin = this.pixelOrigin_;
	point.x = (origin.x + (latLng.lng() + this.offsetLng ) * this.scaleLng * this.pixelsPerLonDegree_);
	// NOTE(appleton): Truncating to 0.9999 effectively limits latitude to
	// 89.189.  This is about a third of a tile past the edge of the world tile.
	point.y = (origin.y + (-1 * latLng.lat() + this.offsetLat ) * this.scaleLat * this.pixelsPerLonDegree_);
	return point;
};

EuclideanProjection.prototype.fromPointToLatLng = function(point) {
	console.info("fromPointToLatLng");
	var me = this;
	var origin = me.pixelOrigin_;
	var lng = (((point.x - origin.x) / me.pixelsPerLonDegree_) / this.scaleLng) - this.offsetLng;
	var lat = ((-1 *( point.y - origin.y) / me.pixelsPerLonDegree_) / this.scaleLat) - this.offsetLat;
	return new google.maps.LatLng(lat , lng, true);
};

var mapType = new google.maps.ImageMapType({
	getTileUrl: function(coord, zoom) {
		return getHorizontallyRepeatingTileUrl(coord, zoom, function(coord, zoom) {
			return "http://arianne.sourceforge.net/stendhal/map/" + zoom + "-" + coord.x + "-" + coord.y + ".png";
		});
	},
	tileSize: new google.maps.Size(256, 256),
	isPng: true,
	maxZoom: 5,
	minZoom: 1,
	name: 'Outside',
	credit: 'Stendhal'
});
mapType.projection = new EuclideanProjection(); 


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


function initialize() {

	var mapOptions = {
		zoom: 0,
		center: new google.maps.LatLng(0, 0),
		mapTypeControl: false
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

	// push the credit/copyright custom control

	// add the new map types to map.mapTypes
	map.mapTypes.set("outside", mapType);


	// handle maptypeid_changed event to set the credit line
	google.maps.event.addListener(map, 'maptypeid_changed', function() {
		//
	});

	// start with the moon map type
	map.setMapTypeId('outside');

	var myLatlng = new google.maps.LatLng(50, 50);
	var marker = new google.maps.Marker({
		position: myLatlng, 
		map: map, 
		title:"Hello World!"
	});

	google.maps.event.addListener(map, 'click', function(event) {
		alert("Point.X.Y: " + event.latLng);
	});
}
</script>
</head>
<body onload="initialize()">
	<div id="map_canvas" style="width: 640px; height: 480px;">map div</div>
</body>
</html>
