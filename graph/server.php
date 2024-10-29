<?php
$host = "localhost";
$port = "5432";
$dbname = "Covid";
$user = "postgres";
$password = "053823989";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die(json_encode(['error' => 'Database connection failed.']));
}
?>