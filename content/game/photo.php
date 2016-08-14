<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2016 Stendhal

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

require_once '../../configuration.php';
require_once '../../scripts/imageprocessing.php';

$OUTFITS_BASE="../../data/sprites/outfit";


header('Content-Type: image/png');

$image = imagecreatefrompng();
$image = new Imagick('../../images/photos/balduin.png');

$completeOutfit = '7080202';
$offset = 1;

$drawer = new OutfitDrawer();
$outfit = $drawer->create_outfit(explode('_', $completeOutfit), $offset);
$image->compositeImage($outfit, Imagick::COMPOSITE_OVER, 170, 190);


echo $image;