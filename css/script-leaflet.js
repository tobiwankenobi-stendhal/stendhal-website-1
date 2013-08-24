(function(){
	//----------------------------------------------------------------------------
	//                                 Atlas
	//----------------------------------------------------------------------------

	function worldToLatLng(map, point) {
		var x = point[0];
		var y = point[1];
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
		return map.unproject([lx, ly], 0);
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

	/**
	 * adds active points of interest to the map
	 */
	function addActivePOIs(map) {
		var pois = $.parseJSON($("#data-pois").attr("data-pois"));
		var wanted = decodeURI(gup("poi")).toLowerCase().split(",");
		var key;
		for (key in pois) {
			if (pois.hasOwnProperty(key)) {
				var poi = pois[key];
				if (($.inArray(poi.type.toLowerCase(), wanted) > -1)
					|| ($.inArray(poi.name.toLowerCase(), wanted) > -1)) {

					L.marker(
						worldToLatLng(map, [poi.gx - 1, poi.gy - 1]), {
							icon: L.icon({iconUrl: "/images/mapmarker/" + poi.type + ".png"}),
							title: poi.name
						})
						.addTo(map)
						.bindPopup("<div style=\"max-width:400px\"><b><a target=\"_blank\" href=\""
							 + poi.url + "\">" 
							 + poi.title.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;")
							 + "</a></b><p>" + poi.description.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;") + "</p></div>");
				}
			}
		}
	}

	function addMe(map) {
		if ($("#data-me").length > 0) {
			L.marker(
				worldToLatLng(map, [parseInt($("#data-me").attr("data-x"), 10) - 1, parseInt($("#data-me").attr("data-y"), 10) - 1]), {
					icon: L.icon({iconUrl: "/images/mapmarker/me.png"}),
					title: "Me"
				})
				.addTo(map)
				.bindPopup("I am here at " + $("#data-me").attr("data-zone")
						+ " (" + $("#data-me").attr("data-local-x") + ", " + $("#data-me").attr("data-local-y") + ")");
		}

	}

	function initializeLeafletAtlas() {


		var map = L.map('map_leaflet', {
			attributionControl: false
		});
		map.crs = L.CRS.Simple;

		var focusX = parseInt($("#data-center").attr("data-x"), 10);
		var focusY = parseInt($("#data-center").attr("data-y"), 10);
		var zoom = parseInt($("#data-center").attr("data-zoom"), 10);
		map.setView(worldToLatLng(map, [focusX, focusY]), zoom);

		addActivePOIs(map);
		addMe(map);

		L.tileLayer('https://stendhalgame.org/map/2/{z}-{x}-{y}.png', {
			attribution: '',
			minZoom: 2,
			maxZoom: 6,
			noWrap: true
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
