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
                RewriteRule ^/images/image/(.*)$ /image.php?img=$1 [L]
                RewriteRule ^/images/item/(.*)\.png$ /itemimage.php?url=data/sprites/items/$1.png [L]
                RewriteRule ^/images/npc/(.*)\.png$ /monsterimage.php?url=data/sprites/npc/$1.png [L]
                RewriteRule ^/images/outfit/(.*)\.png$ /createoutfit.php?outfit=$1 [L]
                RewriteRule ^/images/thumbnail/(.*)$ /thumbnail.php?img=$1 [L]

                # account
                RewriteRule ^/account/approve-password.html$ /index.php?id=content/account/approve [L]
                RewriteRule ^/account/history.html$ /index.php?id=content/account/loginhistory [L]
                RewriteRule ^/account/change-email.html$ /index.php?id=content/account/email [L]
                RewriteRule ^/account/change-password.html$ /index.php?id=content/account/changepassword [L]
                RewriteRule ^/account/login.html$ /index.php?id=content/account/login [L]
                RewriteRule ^/account/logout.html$ /index.php?id=content/account/logout [L]
                RewriteRule ^/account/mycharacters.html$ /index.php?id=content/account/mycharacters [L]
                RewriteRule ^/account/remind-mail.html$ /index.php?id=content/account/remind [L]

                # characters
                RewriteRule ^/character/(.*)\.html$ /index.php?id=content/scripts/character&name=$1&exact [L]

                # chat
                RewriteRule ^/chat/?$ /index.php?id=content/game/chat [L]
                RewriteRule ^/chat/(.*)\.html$ /index.php?id=content/game/chat&date=$1 [L]

                # creatures
                RewriteRule ^/creature/?$ /index.php?id=content/game/creatures [L]
                RewriteRule ^/creature/(.*)\.html$ /index.php?id=content/scripts/monster&name=$1&exact [L]

                # development
                RewriteRule ^/development/bug\.html$ /index.php?id=content/game/bug [L]
                RewriteRule ^/development/chat\.html$ /chat/ [R=301]
                RewriteRule ^/development/cvslog\.html$ /index.php?id=content/game/cvslog [L]
                RewriteRule ^/development/cvslog/(.*)\.html$ /index.php?id=content/game/cvslog&month=$1 [L]
                RewriteRule ^/development/?$ /index.php?id=content/game/development [L]

                # items
                RewriteRule ^/item/?$ /index.php?id=content/game/items [L]
                RewriteRule ^/item/([^/]*)/(.*)\.html$ /index.php?id=content/scripts/item&class=$1&name=$2&exact [L]
                RewriteRule ^/item/([^/]*)\.html$ /index.php?id=content/game/items&class=$1 [L]

                # news
                RewriteRule ^/news/(.*)$ /index.php?id=content/news/newss&news=$1 [L]
                RewriteRule ^/(-.*)$ /index.php?id=content/news/newss&news=$1 [L]

                # npcs
                RewriteRule ^/npc/?$ /index.php?id=content/game/npcs [L]
                RewriteRule ^/npc/(.*)\.html$ /index.php?id=content/scripts/npc&name=$1&exact [L]

                # rss
                RewriteRule ^/rss/news.rss$ /index.php?id=content/news/rss [L]

                # world
                RewriteRule ^/world/atlas\.html$ /index.php?id=content/game/atlas [L]
                RewriteRule ^/world/hall-of-fame\.html$ /index.php?id=content/halloffame [L]
                RewriteRule ^/world/newsarchive\.html$ /index.php?id=content/newsarchive [L]
                RewriteRule ^/world/kill-stats\.html$ /index.php?id=content/scripts/killedstats [L]
                RewriteRule ^/world/online\.html$ /index.php?id=content/scripts/online [L]
                RewriteRule ^/world/server-stats\.html$ /index.php?id=content/scripts/serverstats [L]




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

                # chat
                RewriteCond %{QUERY_STRING} id=content/game/chat&date=([^&]*)
                RewriteRule ^/.* /chat/%1.html? [R=301]
                RewriteCond %{QUERY_STRING} id=content/game/chat$
                RewriteRule ^/.* /chat/? [R=301]

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
                RewriteCond %{QUERY_STRING} id=content/game/cvslog&month=([^&]*)
                RewriteRule ^/.* /development/cvslog/%1.html? [R=301]
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

                # news
                RewriteCond %{QUERY_STRING} id=content/news/news&news=([^&]*)
                RewriteRule ^/.* /news/%1? [R=301]

                # npcs
                RewriteCond %{QUERY_STRING} id=content/game/npcs
                RewriteRule ^/.* /npc/? [R=301]
                RewriteCond %{QUERY_STRING} id=content/scripts/npc&name=([^&]*)
                RewriteRule ^/.* /npc/%1.html? [R=301]

                # world
                RewriteCond %{QUERY_STRING} id=content/game/atlas
                RewriteRule ^/.* /world/atlas.html? [R=301]
                RewriteCond %{QUERY_STRING} ^id=content/halloffame$
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

	$folder = STENDHAL_FOLDER;

	// images
	if (preg_match('|^/images/.*|', $url)) {
		if (preg_match('|^/images/creature/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/creature/(.*)\.png$|', $folder.'/monsterimage.php?url=data/sprites/monsters/$1.png', $url);
		} else if (preg_match('|^/images/item/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/item/(.*)\.png$|', $folder.'/itemimage.php?url=data/sprites/items/$1.png', $url);
		} else if (preg_match('|^/images/npc/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/npc/(.*)\.png$|', $folder.'/monsterimage.php?url=data/sprites/npc/$1.png', $url);
		} else if (preg_match('|^/images/outfit/(.*)\.png$|', $url)) {
			return preg_replace('|^/images/outfit/(.*)\.png$|', $folder.'/createoutfit.php?outfit=$1', $url);
		} else if (preg_match('|^/images/thumbnail/(.*)$|', $url)) {
			return preg_replace('|^/images/thumbnail/(.*)$|', $folder.'/thumbnail.php?img=$1', $url);
		} else if (preg_match('|^/images/image/(.*)$|', $url)) {
			return preg_replace('|^/images/image/(.*)$|', $folder.'/image.php?img=$1', $url);
		}

	// account
	} else if (preg_match('|^/account.*|', $url)) {
		if (preg_match('|^/account/approve-password.html$|', $url)) {
			return preg_replace('|^/account/approve-password.html$|', $folder.'/?id=content/account/approve', $url);
		} else if (preg_match('|^/account/change-password.html$|', $url)) {
			return preg_replace('|^/account/change-password.html$|', $folder.'/?id=content/account/changepassword', $url);
		} else if (preg_match('|^/account/change-email.html$|', $url)) {
			return preg_replace('|^/account/change-email.html$|', $folder.'/?id=content/account/email', $url);
		} else if (preg_match('|^/account/login.html$|', $url)) {
			return preg_replace('|^/account/login.html$|', $folder.'/?id=content/account/login', $url);
		} else if (preg_match('|^/account/history.html$|', $url)) {
			return preg_replace('|^/account/history.html$|', $folder.'/?id=content/account/loginhistory', $url);
		} else if (preg_match('|^/account/logout.html$|', $url)) {
			return preg_replace('|^/account/logout.html$|', $folder.'/?id=content/account/logout', $url);
		} else if (preg_match('|^/account/mycharacters.html$|', $url)) {
			return preg_replace('|^/account/mycharacters.html$|', $folder.'/?id=content/account/mycharacters', $url);
		} else if (preg_match('|^/account/remind-mail.html$|', $url)) {
			return preg_replace('|^/account/remind-mail.html$|', $folder.'/?id=content/account/remind', $url);
		}

	// chat
	} else if (preg_match('|^/chat.*|', $url)) {
		if (preg_match('|^/chat/(.*)\.html$|', $url)) {
			return preg_replace('|^/chat/(.*)\.html$|', $folder.'/?id=content/game/chat&amp;date=$1', $url);
		} else if (preg_match('|^/chat/?$|', $url)) {
			return preg_replace('|^/chat/?$|', $folder.'/?id=content/game/chat', $url);
		}

	// characters
	} else if (preg_match('|^/character.*|', $url)) {
		if (preg_match('|^/character/(.*)\.html$|', $url)) {
			return preg_replace('|^/character/(.*)\.html$|', $folder.'/?id=content/scripts/character&amp;name=$1&amp;exact', $url);
		}

	// creatures
	} else if (preg_match('|^/creature.*|', $url)) {
		if (preg_match('|^/creature/?$|', $url)) {
			return preg_replace('|^/creature/?$|', $folder.'/?id=content/game/creatures', $url);
		} else if (preg_match('|^/creature/(.*)\.html$|', $url)) {
			return preg_replace('|^/creature/(.*)\.html$|', $folder.'/?id=content/scripts/monster&amp;name=$1&amp;exact', $url);
		}

	// development
	} else if (preg_match('|^/development.*|', $url)) {
		if (preg_match('|^/development/?$|', $url)) {
			return preg_replace('|^/development/?$|', $folder.'/?id=content/game/development', $url);
		} else if (preg_match('|^/development/bug\.html$|', $url)) {
			return preg_replace('|^/development/bug\.html$|', $folder.'/?id=content/game/bug', $url);
		} else if (preg_match('|^/development/chat\.html$|', $url)) {
			return preg_replace('|^/development/chat\.html$|', $folder.'/?id=content/game/chat', $url);
		} else if (preg_match('|^/development/cvslog\.html$|', $url)) {
			return preg_replace('|^/development/cvslog\.html$|', $folder.'/?id=content/game/cvslog', $url);
		} else if (preg_match('|^/development/cvslog/(.*)\.html$|', $url)) {
			return preg_replace('|^/development/cvslog/(.*)\.html$|', $folder.'/?id=content/game/cvslog&amp;month=$1', $url);
		}

	// items
	} else if (preg_match('|^/item.*|', $url)) {
		if (preg_match('|^/item/?$|', $url)) {
			return preg_replace('|^/item/?$|', $folder.'/?id=content/game/items', $url);
		} else if (preg_match('|^/item/[^/]*/(.*)\.html$|', $url)) {
			return preg_replace('|^/item/([^/]*)/(.*)\.html$|', $folder.'/?id=content/scripts/item&amp;class=$1&amp;name=$2&amp;exact', $url);
		} else if (preg_match('|^/item/([^/]*)\.html$|', $url)) {
			return preg_replace('|^/item/([^/]*)\.html$|', $folder.'/?id=content/game/items&amp;class=$1', $url);
		}
	
	// news
	} else if (preg_match('|^/news/.*|', $url)) {
		return preg_replace('|^/news/(.*)$|', $folder.'/?id=content/news/news&amp;news=$1', $url);
	

	// npcs
	} else if (preg_match('|^/npc.*|', $url)) {
		if (preg_match('|^/npc/?$|', $url)) {
			return preg_replace('|^/npc/?$|', $folder.'/?id=content/game/npcs', $url);
		} else if (preg_match('|^/npc/(.*)\.html$|', $url)) {
			return preg_replace('|^/npc/(.*)\.html$|', $folder.'/?id=content/scripts/npc&amp;name=$1&amp;exact', $url);
		}

	# rss
	} else if (preg_match('|^/rss.*|', $url)) {
		if (preg_match('|^/rss/news.rss$|', $url)) {
			return preg_replace('|^/rss/news.rss$|', $folder.'/?id=content/news/rss', $url);
		}

	// world
	} else if (preg_match('|^/world.*|', $url)) {
		
		if (preg_match('|^/world/atlas\.html$|', $url)) {
			return preg_replace('|^/world/atlas\.html$|', $folder.'/?id=content/game/atlas', $url);
		} else if (preg_match('|^/world/hall-of-fame\.html$|', $url)) {
			return preg_replace('|^/world/hall-of-fame\.html$|', $folder.'/?id=content/halloffame', $url);
		} else if (preg_match('|^/world/newsarchive\.html$|', $url)) {
			return preg_replace('|^/world/newsarchive\.html$|', $folder.'/?id=content/newsarchive', $url);
		} else if (preg_match('|^/world/kill-stats\.html$|', $url)) {
			return preg_replace('|^/world/kill-stats\.html$|', $folder.'/?id=content/scripts/killedstats', $url);
		} else if (preg_match('|^/world/online\.html$|', $url)) {
			return preg_replace('|^/world/online\.html$|', $folder.'/?id=content/scripts/online', $url);
		} else if (preg_match('|^/world/server-stats\.html$|', $url)) {
			return preg_replace('|^/world/server-stats\.html$|', $folder.'/?id=content/scripts/serverstats', $url);
		}


	} else {
		echo '">Error parsing link';
	}
}

function surlencode($url) {
	return urlencode(preg_replace('/[ +]/', '_', $url));
}
?>