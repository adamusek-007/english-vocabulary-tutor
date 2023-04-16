<?php
class Connector
{
    function getConnectionToDatabase()
    {
        include(dirname(dirname(__FILE__))."/connection.php");
        $connection = getConnectionToDatabase();
        return $connection;
    }
}