<?php
class AtlasPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Atlas'.STENDHAL_TITLE.'</title>';
		$this->includeJs();
	}

	function writeContent() {
		echo '<div id="map_canvas" style="width: 570px; height: 380px;"></div><p>&nbsp;</p>';
		startBox('Extended information');
		echo '</p>There are in detail information about the <a href="http://stendhalgame.org/wiki/Semos">various regions</a> and <a href="http://stendhalgame.org/wiki/Semos_Dungeons">dungeons</a> on the Stendhal Wiki.</p>';
		echo '</p>Here is a map with <a href="http://arianne.sourceforge.net/screens/stendhal/world_labelled.png">zone names</a> and a map with <a href="/world/atlas.html?poi=dungeon">dungeon entrances</a>.</p>';
		endBox();
	}
	
	function writeAfterJS() {
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
		?>

<script
	type="text/javascript"
	src="http://maps.google.com/maps/api/js?sensor=false"></script>
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

	var xl0 = 0;
	var yl0 = 0;
	var xlz = 208.15;
	var ylz = 144.2;

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
//		return "http://localhost/map/" + zoom + "-" + coord.x + "-" + coord.y + ".png";
		return "http://arianne.sourceforge.net/stendhal/map/2/" + zoom + "-" + coord.x + "-" + coord.y + ".png";
	},
	tileSize: new google.maps.Size(256, 256),
	isPng: true,
	maxZoom: 6,
	minZoom: 1,
	name: 'Outside',
	credit: 'Stendhal'
});
mapType.projection = new EuclideanProjection(); 

var map;

// http://www.netlobo.com/url_query_string_javascript.html
function gup(name) {
	name = name.replace(/[\[]/, "\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec( window.location.href );
	if (results == null) {
		return "";
	} else {
		return results[1];
	}
}

$().ready(function() {
	var mapOptions = {
		backgroundColor: "#5f9860",
		center: worldToLatLng(<?php echo $focusX.', '.$focusY?>),
		noClear: true,
		zoom: <?php echo $zoom;?>,
		mapTypeControl: false,
		streetViewControl: false
	};
	map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

	// push the credit/copyright custom control

	// add the new map types to map.mapTypes
	map.mapTypes.set("outside", mapType);

	infowindow = new google.maps.InfoWindow({});

	map.setMapTypeId('outside');
	<?php
	if (isset($meX)) {
		echo 'var me = new google.maps.Marker({
			position: worldToLatLng('.$meX.', '.$meY.'), 
			map: map, title:"Me",
			icon: "/images/mapmarker/me.png"
			});';
		echo 'addClickEventToMarker(me, {
				name: "Me",
				description: "I am here at '.htmlspecialchars($meZone)
				.' ('.htmlspecialchars($coordinates[1]).', '.htmlspecialchars($coordinates[2]).').",
				url: "/account/mycharacters.html"
			});';
	}
	echo "\r\nvar pois = ".json_encode(PointofInterest::getPOIs()).";\r\n";
	?>
	wanted = decodeURI(gup("poi")).toLowerCase().split(",");
	for (var key in pois) {
		var poi = pois[key];
		if (($.inArray(poi.type.toLowerCase(), wanted) > -1)
			|| ($.inArray(poi.name.toLowerCase(), wanted) > -1)) {

			var marker = new google.maps.Marker({position: worldToLatLng(poi.gx, poi.gy),
				map: map, title: poi.name, icon: "/images/mapmarker/" + poi.type + ".png"});

			addClickEventToMarker(marker, poi);
		}
	}
});

function addClickEventToMarker(marker, poi) {
	google.maps.event.addListener(marker, 'click', function(x, y, z) {
		infowindow.setContent("<b><a target=\"_blank\" href=\""
				 + poi.url + "\">" 
				 + poi.title.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")
				 + "</a></b><p>" + poi.description.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;") + "</p>");
		infowindow.open(map, marker);
	});
}
</script>
<?php
	}
}
$page = new AtlasPage();