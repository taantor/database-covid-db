<?php
$host = "dpg-csgrgojtq21c73duks8g-a.singapore-postgres.render.com";
$port = "5432";
$dbname = "covid_u35k";
$user = "tor";
$password = "HMd4Hhy5cPZnWCJqlPaJiRavgdm3H3IP";

try {
    // แก้ไขให้เรียกใช้ตัวแปร $dbname แทน $db
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require", $user, $password);
    // ตั้งค่า PDO ให้แจ้งเตือนข้อผิดพลาด
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ไม่สามารถเชื่อมต่อกับฐานข้อมูล: " . $e->getMessage());
}
?>
