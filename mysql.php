<?php

include_once('configuration.inc');

function connect() {
    mysql_connect(STENDHAL_WEB_HOSTNAME,STENDHAL_WEB_USERNAME,STENDHAL_WEB_PASSWORD);
    @mysql_select_db(STENDHAL_WEB_DB) or die( "Unable to select database");
}

function disconnect() {
    mysql_close();
}
?>