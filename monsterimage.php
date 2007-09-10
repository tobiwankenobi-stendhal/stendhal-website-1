<?php

header("Content-type: image/png");
$url = $_GET['url'];

$size=getimagesize($url);
$w=$size[0];
$h=$size[1];

/*
 * Images are tiles of 3x4 so we choose a single tile.
 */
$w=$w/3;
$h=$h/4;

$result=imagecreate($w,$h);

$white=imagecolorallocate($result,255,255,255);
imagefilledrectangle($result, 0,0,$w,$h,$white);

$baseIm=imagecreatefrompng($url);
imagecopy($result,$baseIm,0,0,0,$h*2,$w,$h);

imagepng($result);

?> 