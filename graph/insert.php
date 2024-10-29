<?php
include 'server_test.php'; // ตรวจสอบการเชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $state = $_POST['state'];
    $fips = $_POST['fips'];
    $cases = $_POST['cases'];
    $deaths = $_POST['deaths'];
    $date = $_POST['date'];

    // สร้างคำสั่ง SQL สำหรับการเพิ่มข้อมูล
    $sql = "INSERT INTO us_state_miss (state, fips, cases, deaths, date) VALUES (:state, :fips, :cases, :deaths, :date)";
    $stmt = $pdo->prepare($sql);
    
    // Binding parameters
    $stmt->bindParam(':state', $state);
    $stmt->bindParam(':fips', $fips);
    $stmt->bindParam(':cases', $cases);
    $stmt->bindParam(':deaths', $deaths);
    $stmt->bindParam(':date', $date);
    
    // Execute the statement
    if ($stmt->execute()) {
        // อัปเดตสำเร็จ
        header("Location: manage_data.php"); // กลับไปที่หน้า manage_data.php
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูล";
    }
}
?>