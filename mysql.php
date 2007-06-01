<?php

include_once('configuration.inc');

function connect() {
    mysql_connect(STENDHAL_HOSTNAME,STENDHAL_USERNAME,STENDHAL_PASSWORD);
    @mysql_select_db(STENDHAL_DB) or die( "Unable to select database");
}

function disconnect() {
    mysql_close();
}
?>