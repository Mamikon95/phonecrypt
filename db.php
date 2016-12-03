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
    die("Database connection failed: " . $e->getMessage());
}