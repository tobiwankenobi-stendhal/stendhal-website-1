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

$url = $_GET['url'];

if (strpos($url, '..') !== false) {
	die("Access denied.");
}


$size=getimagesize($url);
$w=$size[0];
$h=$size[1];

if(strpos($url,"/ent/")!=false) {
  /*
   * Ent images are tiles of 1x2 so we choose a single tile.
   */	
  $w=$w;
  $h=$h/2;	
  $loc=0;
} else {
  /*
   * Images are tiles of 3x4 so we choose a single tile.
   */
  $w=$w/3;
  $h=$h/4;
  
  $loc=$h*2;
}

$result=imagecreate($w,$h);

$white=imagecolorallocate($result,255,255,255);
imagefilledrectangle($result, 0,0,$w,$h,$white);

$baseIm=imagecreatefrompng($url);
imagecopy($result,$baseIm,0,0,0,$loc,$w,$h);

header("Content-type: image/png");
header("Cache-Control: max-age=3888000"); // 45 * 24 * 60 * 60
imagepng($result);

?>
