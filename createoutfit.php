<?php

function conditionalAddToImage($index, &$baseIm, $path)
  {
  if($index!=0)
    {
    addToImage($index,$baseIm,$path);
    }
  }
  
function addToImage($index, &$baseIm, $path)
  {
  $tmpIm=imagecreatefrompng($path.$index.'.png');
  $transColor=imagecolorat($tmpIm, 0,0);
  imagecolortransparent($tmpIm, $transColor);
  imagecopymerge($baseIm,$tmpIm,0,0,0,128,48,64,100);
  imagedestroy($tmpIm);  
  }
  

header("Content-type: image/png");
$outfit = $_GET['outfit'];

$result=imagecreatetruecolor(48,64);
$white=imagecolorallocate($result,255,255,255);
imagefill($result,0,0,$white);

$baseIndex=($outfit % 100);
$outfit=$outfit/100;
$baseIm=imagecreatefrompng('images/outfit/player_base_'.$baseIndex.'.png');
$transColor=imagecolorat($baseIm, 0,0);
imagecolortransparent($baseIm, $transColor);
imagecopymerge($result,$baseIm,0,0,0,128,48,64,100);
imagedestroy($baseIm);

$dressIndex=($outfit % 100);
$outfit=$outfit/100;
conditionalAddToImage($dressIndex,$result,'images/outfit/dress_');

$headIndex=($outfit % 100);
$outfit=$outfit/100;
addToImage($headIndex,$result,'images/outfit/head_');

$hairIndex=($outfit % 100);
$outfit=$outfit/100;
conditionalAddToImage($hairIndex,$result,'images/outfit/hair_');

imagepng($result);

?> 
