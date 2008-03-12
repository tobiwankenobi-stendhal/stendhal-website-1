<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008  Miguel Angel Blanch Lardin

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
/**
 * This script creates a bar chart diagram.
 */

// This array of values is just here for the example.

$data=$_REQUEST['data'];
$values = explode(",",$data);

// Get the total number of columns we are going to plot

$columns  = count($values)-1;

// Get the height and width of the final image

$width = 560;
$height = 200;

// Set the amount of space between each column

$padding = 2;

// Get the width of 1 column

$column_width = $width / $columns ;

// Generate the image variables

$im        = imagecreate($width,$height+20);
$gray      = imagecolorallocate ($im,0x00,0x11,0xcc);
$gray_lite = imagecolorallocate ($im,0xee,0xee,0xee);
$gray_dark = imagecolorallocate ($im,0x7f,0x7f,0x7f);
$white     = imagecolorallocate ($im,0xff,0xff,0xff);
$black     = imagecolorallocate ($im,0x00,0x00,0x00);

// Fill in the background of the image

imagefilledrectangle($im,0,0,$width,$height+20,$white);

$maxv = 1;

// Calculate the maximum value we are going to plot

for($i=0;$i<$columns;$i++) {
	$maxv = max($values[$i],$maxv);
}

// Now plot each column

for($i=0;$i<$columns;$i++) {
	$column_height = ($height / 100) * (( $values[$i] / $maxv) *100);

	$x1 = $i*$column_width;
	$y1 = $height-$column_height;
	$x2 = (($i+1)*$column_width)-$padding;
	$y2 = $height;

	imagefilledrectangle($im,$x1,$y1,$x2,$y2,$gray);
	imagestring($im, 3, $x1+($x2-$x1)/2,$y2+7, $values[$i], $black);

	// This part is just for 3D effect
	imageline($im,$x1,$y1,$x1,$y2,$black);
	imageline($im,$x1,$y2,$x2,$y2,$black);
	imageline($im,$x2,$y1,$x2,$y2,$black);
}

// Send the PNG header information. Replace for JPEG or GIF or whatever

header ("Content-type: image/png");
imagepng($im);
?>