<?php

header("Content-type: image/png");
$url = $_GET['url'];
$w = $_GET['w'];
$h = $_GET['h'];

if($w==1 and $h==2) {
  $factor=48;
} else {
  $factor=32;
}

$result=imagecreate($factor*$w,32*$h);

$white=imagecolorallocate($result,255,255,255);
imagefilledrectangle($result, 0,0,$factor*$w,32*$h,$white);

$baseIm=imagecreatefrompng($url);
imagecopy($result,$baseIm,0,0,0,32*$h*2,$factor*$w,32*$h);

imagepng($result);

?> 