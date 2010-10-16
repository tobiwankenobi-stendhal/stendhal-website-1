<?php
class MapPage extends Page {

	public function writeHtmlHeader() {
		echo '<title>Map'.STENDHAL_TITLE.'</title>';
		?>
		<style type="text/css">
			#canvas {border: 1px solid black}
			#container {width:99%}
		</style>
		<script type="text/javascript" src="<?php echo STENDHAL_FOLDER; ?>/css/jsxgraph-util.js"></script>
		<?php
	}

	function getBodyTagAttributes() {
		return 'id="body" onload="load()"';
	}

	function writeContent() {
		startBox("Map");
?>
<form name="mapform" onsubmit="return refreshButton()">
<label for="mapname">File name: </label><input name="mapname" id="mapname">
<input type="submit" value="Refresh">
</form>
		<?php endBox(); ?>

<canvas id="canvas" width="1000" height="300"></canvas>

<script type="text/javascript">
	var lastMap = ""
	var tileSize = 32;
	var zoomSize = 16;

	var layers;
	var firstgids;
	var numberOfXTiles;
	var numberOfYTiles;


	// Start http://www.webreference.com/programming/javascript/gr/column3/ 
	function ImagePreloader(images, callback) {
		// store the call-back
		this.callback = callback;

		// initialize internal state.
		this.nLoaded = 0;
		this.nProcessed = 0;
		this.aImages = new Array;

		// record the number of images.
		this.nImages = images.length;

		// for each image, call preload()
		for ( var i = 0; i < images.length; i++)
			this.preload(images[i]);
	}

	ImagePreloader.prototype.preload = function(image) {
		// create new Image object and add to array
		var oImage = new Image;
		this.aImages.push(oImage);

		// set up event handlers for the Image object
		oImage.onload = ImagePreloader.prototype.onload;
		oImage.onerror = ImagePreloader.prototype.onerror;
		oImage.onabort = ImagePreloader.prototype.onabort;

		// assign pointer back to this.
		oImage.oImagePreloader = this;
		oImage.bLoaded = false;

		// assign the .src property of the Image object
		oImage.src = image;
	}

	ImagePreloader.prototype.onComplete = function() {
		this.nProcessed++;
		if (this.nProcessed == this.nImages) {
			this.callback(this.aImages, this.nLoaded);
		}
	}

	ImagePreloader.prototype.onload = function() {
		this.bLoaded = true;
		this.oImagePreloader.nLoaded++;
		this.oImagePreloader.onComplete();
	}

	ImagePreloader.prototype.onerror = function() {
		this.bError = true;
		this.oImagePreloader.onComplete();
	}

	ImagePreloader.prototype.onabort = function() {
		this.bAbort = true;
		this.oImagePreloader.onComplete();
	}

	// End http://www.webreference.com/programming/javascript/gr/column3/

	var httpRequest;
	function makeRequest(url, callback) {
		httpRequest = new XMLHttpRequest();
		if (httpRequest.overrideMimeType) {
			httpRequest.overrideMimeType('text/xml');
		}
		httpRequest.onreadystatechange = callback;
		httpRequest.open('GET', url, true);
		httpRequest.send(null);
	}

	function draw(aImages, nLoaded) {
		var canvas = document.getElementById("canvas");
		var ctx = canvas.getContext("2d");

		for (var z=0; z < layers.length-2; z++) {
			var layer = layers[z];
			for (var y=0; y < numberOfYTiles; y++) {
				for (var x=0; x < numberOfXTiles; x++) {
					try {
						var gid = layer[y * numberOfXTiles + x];
						if (gid > 0) {
							var tileset = getTilesetForGid(gid);
	
							var base = firstgids[tileset];
							var idx = gid - base;
							var tilesetWidth = aImages[tileset].width;
	
							ctx.drawImage(aImages[tileset], 
									(idx * tileSize) % tilesetWidth, Math.floor((idx * tileSize) / tilesetWidth) * tileSize, tileSize, tileSize, 
									x * zoomSize, y * zoomSize, zoomSize, zoomSize);
						}
					} catch (e) {
						// ignore
						alert(e + " gid: " + gid + " tileset: " + tileset + " base: " + base);
						alert("tilesetWidth: " + tilesetWidth 
								+ " x : " + ((idx * tileSize) % tilesetWidth) 
								+ " y: " + (Math.floor((idx * tileSize) / tilesetWidth) * tileSize));
					}
				}
			}
		}
		var body = document.getElementById("body");
		body.style.cursor = "default";
	}

	/**
	 * Returns the index of the tileset a tile belongs to.
	 */
	function getTilesetForGid(value) {
		var pos;
		for (pos = 0; pos < firstgids.length; pos++) {
			if (value < firstgids[pos]) {
				break;
			}
		}
		return pos - 1;
	}

	/**
	 * parses the map file, loads the tileset and resizes the canvas.
	 */
	function parseMap() {
		if (httpRequest.readyState == 4) {
			if (httpRequest.status != 200) {
				var body = document.getElementById("body")
				body.style.cursor = "auto";
				alert("Could not find map");
				return;
			}
			var xmldoc = httpRequest.responseXML;
			var root = xmldoc.getElementsByTagName('map').item(0);
			var images = new Array;
			firstgids = new Array;
			layers = new Array;
			for (var iNode = 0; iNode < root.childNodes.length; iNode++) {
				var node = root.childNodes.item(iNode);
				if (node.nodeName == "tileset") {
					filename = getTilesetFilename(node)
					images.push(filename);
					firstgids.push(node.getAttribute("firstgid"));
				} else if (node.nodeName == "layer") {
					var data = node.getElementsByTagName("data")[0];
					var mapData = data.firstChild.nodeValue.trim();
					var decoder = new JXG.Util.Unzip(JXG.Util.Base64.decodeAsArray(mapData));
					var data = decoder.unzip()[0][0];
					readLayer(data);
				}
			}
			new ImagePreloader(images, draw);

			// read map size and adjust size of canvas
			var canvas = document.getElementById("canvas")
			numberOfXTiles = root.getAttribute("width")
			numberOfYTiles = root.getAttribute("height")
			canvas.width = numberOfXTiles * zoomSize;
			canvas.height = numberOfYTiles * zoomSize;
		}
	}

	function getTilesetFilename(node) {
		var image = node.getElementsByTagName("image");
		return image[0].getAttribute("source").replace(/\.\.\/\.\.\//g, "");
	}

	/**
	 * reads the tile information for a layer
	 */
	function readLayer(dataString) {
		var layer = new Array;
		data = dataString;
		for (var i = 0; i < data.length - 3; i=i+4) {
			var tileId = (data.charCodeAt(i) >>> 0)
				+ (data.charCodeAt(i + 1) << 8)
				+ (data.charCodeAt(i + 2) << 16)
				+ (data.charCodeAt(i + 3) << 24);
			layer.push(tileId)
		}
		layers.push(layer);
	}

	function load() {
		var location = window.location.hash.substring(2);
		if (location == "") {
			document.getElementById("mapname").value = "Level 0/semos/city.tmx";
			refreshButton();
		}
		checkMapChange();
	}

	function checkMapChange() {
		setTimeout("checkMapChange()", 200);
		var location = window.location.hash.substring(2);
		if (lastMap != location) {
			lastMap = location;
			loadMap();
		}
	}

	function loadMap() {
		var body = document.getElementById("body")
		body.style.cursor = "wait";
		var location = window.location.hash.substring(2).replace(/%20/, " ");
		if (location.indexOf(":") > -1) {
			var body = document.getElementById("body")
			body.style.cursor = "auto";
			alert("Invalid map name");
			return;
		}
		document.getElementById("mapname").value = location;
		makeRequest("tiled/" + escape(location), parseMap);
	}

	function refreshButton() {
		window.location = "#!" + escape(document.getElementById("mapname").value.trim());
		return false;
	}
</script>
<?php
	}
}
$page = new MapPage();