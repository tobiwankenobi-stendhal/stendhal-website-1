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
 
function open_image ($file) {
        // Get extension
        $extension = strrchr($file, '.');
        $extension = strtolower($extension);

        switch($extension) {
                case '.jpg':
                case '.jpeg':
                        $im = imagecreatefromjpeg($file);
                        break;
                case '.gif':
                        $im = imagecreatefromgif($file);
                        break;
		case '.png':
			$im = imagecreatefrompng($file);
			break;
                // ... etc

                default:
                        $im = false;
                        break;
        }

        return $im;
}


// Load image
$image = open_image($_GET['img']);
if ($image === false) { die ('Unable to open image'); }

// Get original width and height
$width = imagesx($image);
$height = imagesy($image);

// New width and height
$new_width = 158;
$new_height = $height * ($new_width/$width);

// Resample
$image_resized = imagecreatetruecolor($new_width, $new_height);
imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Display resized image
header('Content-type: image/jpeg');
imagejpeg($image_resized);
die();
?>
