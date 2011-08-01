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
	var point = opt_point || new google.maps.Point(0, 0);
	var origin = this.pixelOrigin_;
	point.x = (origin.x + (latLng.lng() + this.offsetLng ) * this.scaleLng * this.pixelsPerLonDegree_);
	point.y = (origin.y + (-1 * latLng.lat() + this.offsetLat ) * this.scaleLat * this.pixelsPerLonDegree_);
	return point;
};

EuclideanProjection.prototype.fromPointToLatLng = function(point) {
	var me = this;
	var origin = me.pixelOrigin_;
	var lng = (((point.x - origin.x) / me.pixelsPerLonDegree_) / this.scaleLng) - this.offsetLng;
	var lat = ((-1 *( point.y - origin.y) / me.pixelsPerLonDegree_) / this.scaleLat) - this.offsetLat;
	return new google.maps.LatLng(lat , lng, true);
};

var mapType = new google.maps.ImageMapType({
	getTileUrl: function(coord, zoom) {
		var y = coord.y;
		var x = coord.x;
		var tileRange = 1 << zoom;
		if (y < 0 || y >= tileRange) {
			return null;
		}
		if (x < 0 || x >= tileRange) {
			return null;
		}
		return "http://arianne.sourceforge.net/stendhal/map/" + zoom + "-" + coord.x + "-" + coord.y + ".png";
	},
	tileSize: new google.maps.Size(256, 256),
	isPng: true,
	maxZoom: 5,
	minZoom: 1,
	name: 'Outside',
	credit: 'Stendhal'
});
mapType.projection = new EuclideanProjection(); 

var map;

function worldToLatLng(x, y) {
	var xw0 = 499616;
	var yw0 = 499744;
	var xwz = 501280;
	var ywz = 500896;

	var xl0 = 4.5625;
	var yl0 = 4.59375;
	var xlz = 242.28125;
	var ylz = 169.09375;

	var lx = (x - xw0) / (xwz - xw0) * (xlz - xl0) + xl0;
	var ly = (y - yw0) / (ywz - yw0) * (ylz - yl0) + yl0;
	return mapType.projection.fromPointToLatLng({x:lx, y:ly});
}

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
	var myLatlng = worldToLatLng(500016, 500034);
	var marker = new google.maps.Marker({
		position: myLatlng, 
		map: map, 
		title:"Semos Village Guard House"
	});
	var myLatlng = worldToLatLng(500949, 500153);
	var marker = new google.maps.Marker({
		position: myLatlng, 
		map: map, 
		title:"Ados Church"
	});

	google.maps.event.addListener(map, 'click', function(event) {
		alert("Point.latlng: " + event.latLng + "\r\n Point.xy: " + mapType.projection.fromLatLngToPoint(event.latLng, false));
	});
}
</script>
</head>
<body onload="initialize()">
	<div id="map_canvas" style="width: 640px; height: 480px;">map div</div>
</body>
</html>
