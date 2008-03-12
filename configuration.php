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

#
# Stendhal Website configuration file.
#
# Please change to match your system configuration.
#
$STENDHAL_VERSION='0.67';

#
# Website database to store news, events and other useful stuff.
#
define('STENDHAL_WEB_HOSTNAME','127.0.0.1');
define('STENDHAL_WEB_USERNAME','FOO');
define('STENDHAL_WEB_PASSWORD','BAR');
define('STENDHAL_WEB_DB','stendhal_website');

#
# This user should only be able to read the tables but the account table that should be read/write
# in order for change password to work.
#
define('STENDHAL_GAME_HOSTNAME','127.0.0.1');
define('STENDHAL_GAME_USERNAME','FOO');
define('STENDHAL_GAME_PASSWORD','BAR');
define('STENDHAL_GAME_DB','stendhal');

?>
