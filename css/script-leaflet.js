(function(){
	//----------------------------------------------------------------------------
	//                                 Atlas
	//----------------------------------------------------------------------------
/*
	var mapType, infowindow;
	function EuclideanProjection() {
		var EUCLIDEAN_RANGE = 4*256; // move markers outside the map area far out of the way
		this.pixelOrigin = new google.maps.Point(EUCLIDEAN_RANGE / 2, EUCLIDEAN_RANGE / 2);
		this.pixelsPerLonDegree = EUCLIDEAN_RANGE / 360;
		this.pixelsPerLonRadian = EUCLIDEAN_RANGE / (2 * Math.PI);
		this.scaleLat = 2;      // Height - multiplication scale factor
		this.scaleLng = 1;      // Width - multiplication scale factor
		this.offsetLat = 0;     // Height - direct offset +/-
		this.offsetLng = 0;     // Width - direct offset +/-
	}

	EuclideanProjection.prototype.fromLatLngToPoint = function (latLng, opt_point) {
		var point = opt_point || new google.maps.Point(0, 0);
		var origin = this.pixelOrigin;
		point.x = (origin.x + (latLng.lng() + this.offsetLng ) * this.scaleLng * this.pixelsPerLonDegree);
		point.y = (origin.y + (-1 * latLng.lat() + this.offsetLat ) * this.scaleLat * this.pixelsPerLonDegree);
		return point;
	};

	EuclideanProjection.prototype.fromPointToLatLng = function (point) {
		var me = this;
		var origin = me.pixelOrigin;
		var lng = (((point.x - origin.x) / me.pixelsPerLonDegree) / this.scaleLng) - this.offsetLng;
		var lat = ((-1 *( point.y - origin.y) / me.pixelsPerLonDegree) / this.scaleLat) - this.offsetLat;
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


	// http://www.netlobo.com/url_query_string_javascript.html
	function gup(name) {
		name = name.replace(/[\[]/, "\\\[").replace(/[\]]/,"\\\]");
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec( window.location.href );
		if (results == null) {
			return "";
		}
		return results[1];
	}

	function openInfoForPOI(map, marker, poi) {
		infowindow.setContent("<div style=\"max-width:400px\"><b><a target=\"_blank\" href=\""
				 + poi.url + "\">" 
				 + poi.title.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")
				 + "</a></b><p>" + poi.description.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;") + "</p></div>");
		infowindow.open(map, marker);
	}

	function addClickEventToMarker(map, marker, poi) {
		google.maps.event.addListener(marker, 'click', function (x, y, z) {
			openInfoForPOI(map, marker, poi);
		});
	}

	function initializeAtlas() {
		var tileUrlBase = $("#map_canvas").attr("data-tile-url-base");

		mapType = new google.maps.ImageMapType({
			getTileUrl: function (coord, zoom) {
				var y = coord.y;
				var x = coord.x;
				var tileRange = 1 << zoom;
				if (y < 0 || y >= tileRange) {
					return null;
				}
				if (x < 0 || x >= tileRange) {
					return null;
				}
				return tileUrlBase + "/2/" + zoom + "-" + coord.x + "-" + coord.y + ".png";
			},
			tileSize: new google.maps.Size(256, 256),
			isPng: true,
			maxZoom: 6,
			minZoom: 1,
			name: 'Outside',
			credit: 'Stendhal'
		});
		mapType.projection = new EuclideanProjection(); 


		var mapOptions = {
			backgroundColor: "#5f9860",
			center: worldToLatLng(parseInt($("#data-center").attr("data-x"), 10), parseInt($("#data-center").attr("data-y"), 10)),
			noClear: true,
			zoom: parseInt($("#data-center").attr("data-zoom"), 10),
			mapTypeControl: false,
			streetViewControl: false
		};
		var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		map.mapTypes.set("outside", mapType);

		infowindow = new google.maps.InfoWindow({});

		map.setMapTypeId('outside');
		if ($("#data-me").length > 0) {
			var me = new google.maps.Marker({
				position: worldToLatLng(parseInt($("#data-me").attr("data-x"), 10), parseInt($("#data-me").attr("data-y"), 10)),
				map: map, title:"Me",
				icon: "/images/mapmarker/me.png"
				});
			addClickEventToMarker(map, me, {
					name: "Me",
					title: "Me",
					description: "I am here at " + $("#data-me").attr("data-zone")
						+ " (" + $("#data-me").attr("data-local-x") + ", " + $("#data-me").attr("data-local-y") + ")",
					url: "/account/mycharacters.html"
				});
		}
		var pois = $.parseJSON($("#data-pois").attr("data-pois"));
		var wanted = decodeURI(gup("poi")).toLowerCase().split(",");
		var key;
		for (key in pois) {
			if (pois.hasOwnProperty(key)) {
				var poi = pois[key];
				if (($.inArray(poi.type.toLowerCase(), wanted) > -1)
					|| ($.inArray(poi.name.toLowerCase(), wanted) > -1)) {

					var marker = new google.maps.Marker({position: worldToLatLng(poi.gx, poi.gy),
						map: map, title: poi.name, icon: "/images/mapmarker/" + poi.type + ".png"});
	
					addClickEventToMarker(map, marker, poi);

					if ($("#data-center").attr("data-open")) {
						openInfoForPOI(map, marker, poi);
					}
				}
			}
		}
	}
*/

	function initializeLeafletAtlas() {
		var map = L.map('map_leaflet', {
			center: [1, 1],
			zoom: 3,
			// set maxBounds to prevent draging into a copy of the world
		});
		
		L.tileLayer('https://stendhalgame.org/map/2/{z}-{x}-{y}.png', {
			attribution: '',
			minZoom: 2,
			maxZoom: 6
		}).addTo(map);
	}



	//----------------------------------------------------------------------------
	//                                       init
	//----------------------------------------------------------------------------


	$().ready(function () {
		if (document.getElementById("map_leaflet") != null) {
			initializeLeafletAtlas();
		}
	});
}());
