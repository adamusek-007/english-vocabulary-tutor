<?php
include ("classes.php");
$unit = $_REQUEST["unit"];
$subunit = $_REQUEST["subunit"];
$mode = $_REQUEST["mode"];

$connector = new Connector();
$view_generator = new ViewGenerator();
$connection = $connector->getConnectionToDatabase();
$view_generator->setWordCount($connection, $unit, $subunit, $mode);
