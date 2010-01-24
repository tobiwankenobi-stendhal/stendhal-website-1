<?php


function setup($data) {
	global $width, $height, $padding, $x_0, $y_0, $x_end, $y_end, $x_max, $y_max;
	global $image, $colorBlack, $colorBlue, $colorGrey, $colorWhite;

	$width = intval($_GET['width']);
	$height = intval($_GET['height']);

	$padding = 20;

	$x_0 = $padding;
	$y_0 = $padding;
	$x_end = $width - 2 * $padding;
	$y_end = $height - 2 * $padding;

	$x_max = count($data) - 1;
	$y_max = 100;

	$image = imagecreate($width, $height);
	$colorBlack = imagecolorallocate($image, 0, 0, 0);
	$colorBlue = imagecolorallocate($image, 0, 0, 255);
	$colorGrey = imagecolorallocate($image, 127, 127, 127);
	$colorWhite = imagecolorallocate($image, 255, 255, 255);
}

function drawCoordinateSystem() {
	global $width, $height, $padding, $x_0, $y_0, $x_end, $y_end, $x_max, $y_max;
	global $image, $colorBlack, $colorBlue, $colorGrey, $colorWhite;

	imagefilledrectangle($image, 0, 0, $width, $height, $colorWhite);
	imageline($image, getX(0), getY(0), getX($x_max), getY(0), $colorBlack);
	imageline($image, getX(0), getY(0), getX(0), getY($y_max), $colorBlack);
	
	for ($i = 10; $i <= 100; $i += 10) {
		imageline($image, getX(0), getY($i), getX($x_max), getY($i), $colorGrey);
	}
}

function draw($data) {
	global $width, $height, $padding, $x_0, $y_0, $x_end, $y_end, $x_max, $y_max;
	global $image, $colorBlack, $colorBlue, $colorGrey, $colorWhite;
	
	$x_last = getX(0);
	$y_last = getY(0);

	for($i_row = 0; $i_row < count($data); $i_row++) {
		$value = $data[$i_row];

		$x = getX($i_row);
		$y = getY($value);

//		echo $i_row . ', '.$value.': ' .$x .', ' . $y . ' _________/__________ <br>';
		
		imageline($image, $x_last, $y_last, $x, $y, $colorBlue);

		$x_last = $x;
		$y_last = $y;
	}
}

function getX($value) {
	global $width, $height, $padding, $x_0, $y_0, $x_end, $y_end, $x_max, $y_max;
	return $x_end * $value / $x_max + $x_0;
}

function getY($value) {
	global $width, $height, $padding, $x_0, $y_0, $x_end, $y_end, $x_max, $y_max;
	return $y_end * ($y_max - $value) / $y_max + $x_0;
}

$data = array(0, 10, 20, 30, 40, 50, 70, 80, 90, 100, 50, 100, 0);

setup($data);
drawCoordinateSystem();
draw($data);


header ("Content-type: image/png");
imagepng($image);