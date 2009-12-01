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
                RewriteRule ^/images/creature/(.*)\.png$ /monsterimage.php?url=data/sprites/monsters/$1.png
                RewriteRule ^/images/item/(.*)\.png$ /itemimage.php?url=data/sprites/items/$1.png
                RewriteRule ^/images/npc/(.*)\.png$ /monsterimage.php?url=data/sprites/npc/$1.png
                RewriteRule ^/images/outfit/(.*)\.png$ /createoutfit.php?outfit=$1

                # characters
                RewriteRule ^/character/(.*)\.html$ /index.php?id=content/scripts/character&name=$1&exact

                # creatures
                RewriteRule ^/creature/?$ /index.php?id=content/game/creatures
                RewriteRule ^/creature/(.*)\.html$ /index.php?id=content/scripts/monster&name=$1&exact

                # development
                RewriteRule ^/development/?$ /?id=content/game/development
                RewriteRule ^/development/bug\.html$ /?id=content/game/bug
                RewriteRule ^/development/chat\.html$ /?id=content/game/chat
                RewriteRule ^/development/cvs\.html$ /?id=content/game/cvslog

                # items
                RewriteRule ^/item/?$ /index.php?id=content/game/items
                RewriteRule ^/item/([^/]*)/(.*)\.html$ /index.php?id=content/scripts/item&class=$1&name=$2&exact
                RewriteRule ^/item/([^/]*)\.html$ /index.php?id=content/game/items&class=$1

                # npcs
                RewriteRule ^/npc/?$ /index.php?id=content/game/npcs
                RewriteRule ^/npc/(.*)\.html$ /index.php?id=content/scripts/npc&name=$1&exact

                # world
                RewriteRule ^/world/atlas\.html$ /?id=content/game/atlas
                RewriteRule ^/world/hall-of-fame\.html$ /?id=content/halloffame
                RewriteRule ^/world/kill-stats\.html$ /?id=content/scripts/killedstats
                RewriteRule ^/world/online\.html$ /?id=content/scripts/online
                RewriteRule ^/world/server-stats\.html$ /?id=content/scripts/serverstats

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
			return preg_replace('|^/character/(.*)\.html$|', '/?id=content/scripts/character&name=$1&exact', $url);
		}

	// creatures
	} else if (preg_match('|^/creature.*|', $url)) {
		if (preg_match('|^/creature/?$|', $url)) {
			return preg_replace('|^/creature/?$|', '/?id=content/game/creatures', $url);
		} else if (preg_match('|^/creature/(.*)\.html$|', $url)) {
			return preg_replace('|^/creature/(.*)\.html$|', '/?id=content/scripts/monster&name=$1&exact', $url);
		}

	// development
	} else if (preg_match('|^/development.*|', $url)) {
		if (preg_match('|^/development/?$|', $url)) {
			return preg_replace('|^/development/?$|', '/?id=content/game/development', $url);
		} else if (preg_match('|^/development/bug\.html$|', $url)) {
			return preg_replace('|^/development/bug\.html$|', '/?id=content/game/bug', $url);
		} else if (preg_match('|^/development/chat\.html$|', $url)) {
			return preg_replace('|^/development/chat\.html$|', '/?id=content/game/chat', $url);
		} else if (preg_match('|^/development/cvs\.html$|', $url)) {
			return preg_replace('|^/development/cvs\.html$|', '/?id=content/game/cvslog', $url);
		}

	// items
	} else if (preg_match('|^/item.*|', $url)) {
		if (preg_match('|^/item/?$|', $url)) {
			return preg_replace('|^/item/?$|', '/?id=content/game/items', $url);
		} else if (preg_match('|^/item/[^/]*/(.*)\.html$|', $url)) {
			return preg_replace('|^/item/([^/]*)/(.*)\.html$|', '/?id=content/scripts/item&class=$1&name=$2&exact', $url);
		} else if (preg_match('|^/item/([^/]*)\.html$|', $url)) {
			return preg_replace('|^/item/([^/]*)\.html$|', '/?id=content/game/items&class=$1', $url);
		}

	// npcs
	} else if (preg_match('|^/npc.*|', $url)) {
		if (preg_match('|^/npc/?$|', $url)) {
			return preg_replace('|^/npc/?$|', '/?id=content/game/npcs', $url);
		} else if (preg_match('|^/npc/(.*)\.html$|', $url)) {
			return preg_replace('|^/npc/(.*)\.html$|', '/?id=content/scripts/npc&name=$1&exact', $url);
		}

	// world
	} else if (preg_match('|^/world.*|', $url)) {
		
		if (preg_match('|^/world/atlas\.html$|', $url)) {
			return preg_replace('|^/world/atlas\.html$|', '/?id=content/game/atlas', $url);
		} else if (preg_match('|^/world/hall-of-fame\.html$|', $url)) {
			return preg_replace('|^/world/hall-of-fame\.html$|', '/?id=content/halloffame', $url);
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