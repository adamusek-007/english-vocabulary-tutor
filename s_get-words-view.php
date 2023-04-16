<?php
include "classes.php";
$unit = $_REQUEST["q"];
$connector = new Connector();
$view_generator = new ViewGenerator();
$connection = $connector->getConnectionToDatabase();
$view_generator->setUnitWordView($connection, $unit);
mysqli_close($connection);
?>