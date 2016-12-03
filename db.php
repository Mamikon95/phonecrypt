<?php
/**
 * Database connector
 */
$serverName = "localhost";
$userName = "phonecrypt";
$password = "phonecrypt";
$dbName = "phonecrypt";

try {
    $conn = new PDO("mysql:host=$serverName;dbname=$dbName", $userName, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    header('Content-Type: application/json');
    print json_encode(['errors' => ["Database connection failed: " . $e->getMessage()]]);
    exit();
}