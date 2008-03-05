<?php
/***************************************************************************
 *                      (C) Copyright 2008 - Stendhal                      *
 ***************************************************************************
 ***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/
 
header("Content-type: image/png");
$url = $_GET['url'];

$result=imagecreate(32,32);

$white=imagecolorallocate($result,255,255,255);
imagefilledrectangle($result, 0,0,32,32,$white);

$baseIm=imagecreatefrompng($url);
imagecopy($result,$baseIm,0,0,0,0,32,32);

imagepng($result);

?> 