<?php

$host = 'localhost'; // ที่อยู่เซิร์ฟเวอร์
$db = 'Covid'; // ชื่อฐานข้อมูล
$user = 'postgres'; // ชื่อผู้ใช้
$password = '053823989'; // รหัสผ่าน

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $password);
    // ตั้งค่าความผิดพลาดของ PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ไม่สามารถเชื่อมต่อกับฐานข้อมูล: " . $e->getMessage());
}
?>
