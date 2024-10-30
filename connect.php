<?php
// connect.php

$host = "dpg-csgrgojtq21c73duks8g-a.singapore-postgres.render.com";
$port = "5432";
$dbname = "covid_u35k";
$user = "tor";
$password = "HMd4Hhy5cPZnWCJqlPaJiRavgdm3H3IP";

try {
    // ใช้ PDO ในการเชื่อมต่อกับ PostgreSQL
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    // ตั้งค่าให้ PDO โยนข้อผิดพลาดออกมาเมื่อเกิดปัญหา
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>