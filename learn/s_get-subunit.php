<?php
include ("../classes.php");
$unit = $_REQUEST["s"];
$connector = new Connector();
$view_generator = new ViewGenerator();
$connection = $connector->getConnectionToDatabase();
$view_generator->setSubunitSelectView($connection, $unit);
