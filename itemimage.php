<?php

header("Content-type: image/png");
$url = $_GET['url'];

$result=imagecreate(32,32);

$white=imagecolorallocate($result,255,255,255);
imagefilledrectangle($result, 0,0,32,32,$white);

$baseIm=imagecreatefrompng($url);
imagecopy($result,$baseIm,0,0,0,0,32,32);

imagepng($result);

?> 