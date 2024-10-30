<?php
$host = "dpg-csgrgojtq21c73duks8g-a.singapore-postgres.render.com";
$port = "5432";
$dbname = "covid_u35k";
$user = "tor";
$password = "HMd4Hhy5cPZnWCJqlPaJiRavgdm3H3IP";

try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die(json_encode(['error' => 'Database connection failed.']));
}
?>