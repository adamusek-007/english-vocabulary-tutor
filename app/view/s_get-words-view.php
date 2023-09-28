<?php
include ("../classes.php");
$unit = $_REQUEST["user-selection"];
$connector = new Connector();
$view_generator = new ViewGenerator();
$connection = $connector->getConnectionToDatabase();
$view_generator->setUnitWordView($connection, $unit);
