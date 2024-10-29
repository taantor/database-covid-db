<?php
// connect.php

$host = "localhost";
$port = "5432";
$dbname = "login";
$user = "postgres";
$password = "053823989";

try {
    // ใช้ PDO ในการเชื่อมต่อกับ PostgreSQL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // ตั้งค่าให้ PDO โยนข้อผิดพลาดออกมาเมื่อเกิดปัญหา
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>