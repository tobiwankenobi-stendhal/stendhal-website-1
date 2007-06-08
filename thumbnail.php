<?php 

function open_image ($file) {
        // Get extension
        $extension = strrchr($file, '.');
        $extension = strtolower($extension);

        switch($extension) {
                case '.jpg':
                case '.jpeg':
                        $im = @imagecreatefromjpeg($file);
                        break;
                case '.gif':
                        $im = @imagecreatefromgif($file);
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
$new_width = 160;
$new_height = $height * ($new_width/$width);

// Resample
$image_resized = imagecreatetruecolor($new_width, $new_height);
imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

// Display resized image
header('Content-type: image/jpeg');
imagejpeg($image_resized);
die();
?>