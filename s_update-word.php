<?php
include "classes.php";
$unit = $_REQUEST['u'];
$subunit = $_REQUEST['s'];
$id = $_REQUEST['i'];
$en = $_REQUEST['e'];
$pl = $_REQUEST['p'];
$hint = $_REQUEST['v'];
if (isset($hint) == false || $hint == "") {
    $hint = "NULL";
} else {
    $hint = "'" . $hint . "'";
}
$query = "UPDATE `words` SET `english` = '{$en}', `polish` = '{$pl}', `unit` = '{$unit}', `subunit` = '{$subunit}', `hint` = {$hint} WHERE `id` = '{$id}';";
$connector = new Connector();
$connection = $connector->getConnectionToDatabase();
$connection->query($query);
