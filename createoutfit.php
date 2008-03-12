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

$OUTFITS_BASE="data/sprites/outfit";

/**
 * Adds the image pointed by index to base image if the index != 0
 */
function conditionalAddToImage($index, &$baseIm, $path)
{
	if($index!=0)
	{
		addToImage($index,$baseIm,$path);
	}
}

/**
 * Add an image to the base image.
 */
function addToImage($index, &$baseIm, $path)
{
	$tmpIm=imagecreatefrompng($path.$index.'.png');
	$transColor=imagecolorat($tmpIm, 0,0);
	imagecolortransparent($tmpIm, $transColor);
	imagecopymerge($baseIm,$tmpIm,0,0,0,128,48,64,100);
	imagedestroy($tmpIm);
}

/**
 * Generates a character outfit based on the outfit number.
 * An outfit is made is a mandatory base image and optional
 * head, hair and dress images.
 */

$outfit = $_GET['outfit'];

/*
 * Create base image
 */
$result=imagecreatetruecolor(48,64);
$white=imagecolorallocate($result,255,255,0);
imagefill($result,0,0,$white);

$transColor=imagecolorat($result, 0,0);
imagecolortransparent($result, $transColor);

/*
 * Load base character.
 */
$baseIndex=($outfit % 100);
$outfit=$outfit/100;
$baseIm=imagecreatefrompng($OUTFITS_BASE.'/player_base_'.$baseIndex.'.png');
$transColor=imagecolorat($baseIm, 0,0);
imagecolortransparent($baseIm, $transColor);
imagecopymerge($result,$baseIm,0,0,0,128,48,64,100);
imagedestroy($baseIm);

/*
 * Load dress image and apply.
 */
$dressIndex=($outfit % 100);
$outfit=$outfit/100;
conditionalAddToImage($dressIndex,$result,$OUTFITS_BASE.'/dress_');

/*
 * Load head image and display
 */
$headIndex=($outfit % 100);
$outfit=$outfit/100;
addToImage($headIndex,$result,$OUTFITS_BASE.'/head_');

/*
 * Finally load hair image and display.
 */
$hairIndex=($outfit % 100);
$outfit=$outfit/100;
conditionalAddToImage($hairIndex,$result,$OUTFITS_BASE.'/hair_');

header("Content-type: image/png");
imagepng($result);

?>
