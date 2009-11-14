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




/*

You need to enable mod_rewrite by creating a symlink 
/etc/apache2/mods-enabled/rewrite.load pointing to ../mods-available/rewrite.load

Then edit your sites-enabled virtual host configuration file and add these commands:

        <IfModule mod_rewrite.c>
                RewriteEngine on
                RewriteRule ^/images/outfit/(.*)\.png$ /createoutfit.php?outfit=$1
        </IfModule>


*/


/**
 * rewrite nice urls to ugly once (in case mod_rewrite is not used).
 *
 * @param string $url
 * @return real url
 */
function rewriteURL($url) {
	
	if (STENDHAL_MODE_REWRITE) {
		return $url;
	}
	

	if (preg_match('|^/images/outfit/(.*)\.png$|', $url)) {
		return preg_replace('|^/images/outfit/(.*)\.png$|', '/createoutfit.php?outfit=$1', $url);
	}
}
?>