<?php
class AtlasPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Atlas'.STENDHAL_TITLE.'</title>';
	}

	function getBodyTagAttributes() {
		return ' onload="initialize()"';
	}
	
	function writeContent() {
		echo '<div id="map_canvas" style="width: 570px; height: 380px;"></div><p>&nbsp;</p>';
		startBox('Extended information');
		echo '</p>There are in detail information about the <a href="http://stendhalgame.org/wiki/Semos">various regions</a> and <a href="http://stendhalgame.org/wiki/Semos_Dungeons">dungeons</a> on the Stendhal Wiki.</p>';
		echo '</p>Here is a map with <a href="http://arianne.sourceforge.net/screens/stendhal/world_labelled.png">zone names</a> and a map with <a href="http://stendhalgame.org/wiki/images/9/9e/WorldWithMarkedDungeons091122.png">dungeon entrances</a>.</p>';
		endBox();

		$zoom = 2;
		$focusX = 500200;
		$focusY = 500100;

		// focus on position of current player and display a marker
		if (isset($_REQUEST['me'])) {
			$coordinates = explode('.', $_REQUEST['me']);
			$zones = Zone::getZones();
			$zone = $zones[$coordinates[0]];
			if (isset($zone) && isset($zone->x)) {
				$meX = $zone->x + intval($coordinates[1]);
				$meY = $zone->y + intval($coordinates[2]);
				$zoom = 5;
				$focusX = $meX;
				$focusY = $meY;
			}
		}
		?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

function EuclideanProjection() {
	var EUCLIDEAN_RANGE = 4*256; // move markers outside the map area far out of the way
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


function worldToLatLng(x, y) {
	var xw0 = 499616;
	var yw0 = 499744;
	var xwz = 501280;
	var ywz = 500896;

	var xl0 = 4.7;
	var yl0 = 4.7;
	var xlz = 242.28125;
	var ylz = 169.09375;

	var lx = (x - xw0) / (xwz - xw0) * (xlz - xl0) + xl0;
	var ly = (y - yw0) / (ywz - yw0) * (ylz - yl0) + yl0;
	return mapType.projection.fromPointToLatLng({x:lx, y:ly});
}

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


function initialize() {

	var mapOptions = {
		backgroundColor: "#5f9860",
		center: worldToLatLng(<?php echo $focusX.', '.$focusY?>),
		noClear: true,
		zoom: <?php echo $zoom;?>,
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
	map.setMapTypeId('outside');
	<?php
	if (isset($meX)) {
		echo 'new google.maps.Marker({
			position: worldToLatLng('.$meX.', '.$meY.'), 
			map: map, title:"Me",
			icon: "/images/buttons/postman_button.png"
			});';
	}
	echo "\r\nvar pois = ".json_encode(PointofInterest::getPOIs()).";\r\n";
	if (isset($_REQUEST['pois'])) {
	?>
	for (var key in pois) {
		var poi = pois[key];
		var t = new google.maps.Marker({position: worldToLatLng(poi.gx, poi.gy),
			map: map, title: poi.name/*, icon: "/images/mapmarker/" + poi.type + ".png"*/});
	}
	<?php }?>
/*
	google.maps.event.addListener(map, 'click', function(event) {
		alert("Point.latlng: " + event.latLng + "\r\n Point.xy: " + mapType.projection.fromLatLngToPoint(event.latLng, false));
	});
	*/
}
</script>
<?php 
	}
}
$page = new AtlasPage();