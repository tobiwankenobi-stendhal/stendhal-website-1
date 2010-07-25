<?php

define('PHARAUROA_NETWORK_PROTOCOL_VERSION', 32);
define('PHARAUROA_CLIENTID_INVALID', -1);

require_once ('common/game/attributes.php');
require_once ('common/game/result.php');
require_once ('common/game/rpobject.php');

require_once ('common/net/deserializer.php');
require_once ('common/net/serializer.php');
require_once ('common/net/message-factory.php');

require_once ('common/net/message/message-type.php');
require_once ('common/net/message/message.php');

require_once ('common/net/message/message-p2s-createaccount.php');
require_once ('common/net/message/message-p2s-createcharacter.php');
require_once ('common/net/message/message-s2c-loginack.php');
require_once ('common/net/message/message-s2c-createaccountnack.php');

