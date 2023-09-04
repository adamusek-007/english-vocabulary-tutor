<?php
include ("db_config.php");
class Connector
{
    function getConnectionToDatabase()
    {
        static $pdo;
        if (!$pdo) {
            try {$pdo = new PDO(
                sprintf("mysql:host=%s;dbname=%s;charset=UTF8", DB_HOST, DB_NAME),
                DB_USER,
                DB_PASSWORD,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );} catch (PDOException) {
                echo "Nie można połączyć się z bazą danych";
                exit;
            }
        }
        return $pdo;
    }
}