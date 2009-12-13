<?php
/*
 Stendhal website - a website to manage and ease playing of Stendhal game
 Copyright (C) 2008-2009  The Arianne Project

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

                # images
                RewriteRule ^/images/creature/(.*)\.png$ /monsterimage.php?url=data/sprites/monsters/$1.png [L]
                RewriteRule ^/images/item/(.*)\.png$ /itemimage.php?url=data/sprites/items/$1.png [L]
                RewriteRule ^/images/npc/(.*)\.png$ /monsterimage.php?url=data/sprites/npc/$1.png [L]
                RewriteRule ^/images/outfit/(.*)\.png$ /createoutfit.php?outfit=$1 [L]

                # characters
                RewriteRule ^/character/(.*)\.html$ /index.php?id=content/scripts/character&name=$1&exact [L]

                # creatures
                RewriteRule ^/creature/?$ /index.php?id=content/game/creatures [L]
                RewriteRule ^/creature/(.*)\.html$ /index.php?id=content/scripts/monster&name=$1&exact [L]

                # development
                RewriteRule ^/development/bug\.html$ /index.php?id=content/game/bug [L]
                RewriteRule ^/development/chat\.html$ /index.php?id=content/game/chat [L]
                RewriteRule ^/development/cvslog\.html$ /index.php?id=content/game/cvslog [L]
                RewriteRule ^/development/?$ /index.php?id=content/game/development [L]

                # items
                RewriteRule ^/item/?$ /index.php?id=content/game/items [L]
                RewriteRule ^/item/([^/]*)/(.*)\.html$ /index.php?id=content/scripts/item&class=$1&name=$2&exact [L]
                RewriteRule ^/item/([^/]*)\.html$ /index.php?id=content/game/items&class=$1 [L]

                # npcs
                RewriteRule ^/npc/?$ /index.php?id=content/game/npcs [L]
                RewriteRule ^/npc/(.*)\.html$ /index.php?id=content/scripts/npc&name=$1&exact [L]

                # world
                RewriteRule ^/world/atlas\.html$ /index.php?id=content/game/atlas [L]
                RewriteRule ^/world/hall-of-fame\.html$ /index.php?id=content/halloffame [L]
                RewriteRule ^/world/newsarchive\.html$ /index.php?id=content/newsarchive [L]
                RewriteRule ^/world/kill-stats\.html$ /index.php?id=content/scripts/killedstats [L]
                RewriteRule ^/world/online\.html$ /index.php?id=content/scripts/online [L]
                RewriteRule ^/world/server-stats\.html$ /index.php?id=content/scripts/serverstats [L]
*/

/*
                # images
                RewriteCond %{QUERY_STRING} url=data/sprites/monsters/(.*)\.png
                RewriteRule ^/monsterimage.php /images/creature/%1.png? [R=301]
                RewriteCond %{QUERY_STRING} url=data/sprites/items/(.*).png
                RewriteRule ^/itemimage.php /images/item/%1.png? [R=301]
                RewriteCond %{QUERY_STRING} url=data/sprites/npc/(.*).png
                RewriteRule ^/monsterimage.php /images/npc/%1.png? [R=301]
                RewriteCond %{QUERY_STRING} outfit=(.*)
                RewriteRule ^/createoutfit.php /images/outfit/%1.png? [R=301]

                # characters
                RewriteCond %{QUERY_STRING} id=content/scripts/character&name=([^&]*)
                RewriteRule ^/.* /character/%1.html? [R=301]

                # creatures
                RewriteCond %{QUERY_STRING} id=content/game/creatures
                RewriteRule ^/.* /creature/? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/monster&name=([^&]*)
                RewriteRule ^/.* /creature/%1.html? [R=301]

                # development
                RewriteCond %{QUERY_STRING} id=content/game/development
                RewriteRule ^/.* /development/? [R=301]
                RewriteCond %{QUERY_STRING} id=content/game/bug
                RewriteRule ^/.* /development/bug\.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/game/chat
                RewriteRule ^/.* /development/chat\.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/game/cvslog
                RewriteRule ^/.* /development/cvslog\.html? [R=301]

                # items
                RewriteCond %{QUERY_STRING} id=content/game/items$
                RewriteRule ^/.* /item/? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/item&class=([^&]*)&name=([^&]*)
                RewriteRule ^/.* /item/%1/%2.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/item&name=([^&]*)
                RewriteRule ^/.* /item/all/%1.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/game/items&class=([^&]*)
                RewriteRule ^/.* /item/%1.html? [R=301]

                # npcs
                RewriteCond %{QUERY_STRING} id=content/game/npcs
                RewriteRule ^/.* /npc/? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/npc&name=([^&]*)
                RewriteRule ^/.* /npc/%1.html? [R=301]

                # world
                RewriteCond %{QUERY_STRING} id=content/game/atlas
                RewriteRule ^/.* /world/atlas.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/halloffame
                RewriteRule ^/.* /world/hall-of-fame.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/newsarchive
                RewriteRule ^/.* /world/newsarchive.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/killedstats
                RewriteRule ^/.* /world/kill-stats.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/online
                RewriteRule ^/.* /world/online.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/serverstats
                RewriteRule ^/.* /world/server-stats.html? [R=301]



        </IfModule>



Note: You need to restart apache after editing these files.
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
	

	// images
	if (preg_match('|^/images/.*|', $url)) {
		if (preg_match('|^/images/creature/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/creature/(.*)\.png$|', '/monsterimage.php?url=data/sprites/monsters/$1.png', $url);
		} else if (preg_match('|^/images/item/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/item/(.*)\.png$|', '/itemimage.php?url=data/sprites/items/$1.png', $url);
		} else if (preg_match('|^/images/npc/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/npc/(.*)\.png$|', '/monsterimage.php?url=data/sprites/npc/$1.png', $url);
		} else if (preg_match('|^/images/outfit/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/outfit/(.*)\.png$|', '/createoutfit.php?outfit=$1', $url);
		}

	// characters
	} else if (preg_match('|^/character.*|', $url)) {
		if (preg_match('|^/character/(.*)\.html$|', $url)) {
			return preg_replace('|^/character/(.*)\.html$|', '/?id=content/scripts/character&amp;name=$1&amp;exact', $url);
		}

	// creatures
	} else if (preg_match('|^/creature.*|', $url)) {
		if (preg_match('|^/creature/?$|', $url)) {
			return preg_replace('|^/creature/?$|', '/?id=content/game/creatures', $url);
		} else if (preg_match('|^/creature/(.*)\.html$|', $url)) {
			return preg_replace('|^/creature/(.*)\.html$|', '/?id=content/scripts/monster&amp;name=$1&amp;exact', $url);
		}

	// development
	} else if (preg_match('|^/development.*|', $url)) {
		if (preg_match('|^/development/?$|', $url)) {
			return preg_replace('|^/development/?$|', '/?id=content/game/development', $url);
		} else if (preg_match('|^/development/bug\.html$|', $url)) {
			return preg_replace('|^/development/bug\.html$|', '/?id=content/game/bug', $url);
		} else if (preg_match('|^/development/chat\.html$|', $url)) {
			return preg_replace('|^/development/chat\.html$|', '/?id=content/game/chat', $url);
		} else if (preg_match('|^/development/cvslog\.html$|', $url)) {
			return preg_replace('|^/development/cvslog\.html$|', '/?id=content/game/cvslog', $url);
		}

	// items
	} else if (preg_match('|^/item.*|', $url)) {
		if (preg_match('|^/item/?$|', $url)) {
			return preg_replace('|^/item/?$|', '/?id=content/game/items', $url);
		} else if (preg_match('|^/item/[^/]*/(.*)\.html$|', $url)) {
			return preg_replace('|^/item/([^/]*)/(.*)\.html$|', '/?id=content/scripts/item&amp;class=$1&amp;name=$2&amp;exact', $url);
		} else if (preg_match('|^/item/([^/]*)\.html$|', $url)) {
			return preg_replace('|^/item/([^/]*)\.html$|', '/?id=content/game/items&amp;class=$1', $url);
		}

	// npcs
	} else if (preg_match('|^/npc.*|', $url)) {
		if (preg_match('|^/npc/?$|', $url)) {
			return preg_replace('|^/npc/?$|', '/?id=content/game/npcs', $url);
		} else if (preg_match('|^/npc/(.*)\.html$|', $url)) {
			return preg_replace('|^/npc/(.*)\.html$|', '/?id=content/scripts/npc&amp;name=$1&amp;exact', $url);
		}

	// world
	} else if (preg_match('|^/world.*|', $url)) {
		
		if (preg_match('|^/world/atlas\.html$|', $url)) {
			return preg_replace('|^/world/atlas\.html$|', '/?id=content/game/atlas', $url);
		} else if (preg_match('|^/world/hall-of-fame\.html$|', $url)) {
			return preg_replace('|^/world/hall-of-fame\.html$|', '/?id=content/halloffame', $url);
		} else if (preg_match('|^/world/newsarchive\.html$|', $url)) {
			return preg_replace('|^/world/newsarchive\.html$|', '/?id=content/newsarchive', $url);
		} else if (preg_match('|^/world/kill-stats\.html$|', $url)) {
			return preg_replace('|^/world/kill-stats\.html$|', '/?id=content/scripts/killedstats', $url);
		} else if (preg_match('|^/world/online\.html$|', $url)) {
			return preg_replace('|^/world/online\.html$|', '/?id=content/scripts/online', $url);
		} else if (preg_match('|^/world/server-stats\.html$|', $url)) {
			return preg_replace('|^/world/server-stats\.html$|', '/?id=content/scripts/serverstats', $url);
		}


	} else {
		echo '">Error parsing link';
	}

}
?>