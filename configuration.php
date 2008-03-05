<?php
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
