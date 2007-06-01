<?php
$name=$_REQUEST["name"];
$players=getPlayers('where name="'.$name.'"', 'name');
$choosen=$players[0];

$choosen->showExtended();



?>