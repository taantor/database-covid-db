<?php
include 'server_test.php';

$sql = "SELECT * FROM us_state_miss ORDER BY date DESC";
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($results as $row) {
    echo "รัฐ: " . $row['state'] . " - จำนวนผู้ติดเชื้อ: " . $row['cases'] . " - จำนวนผู้เสียชีวิต: " . $row['deaths'] . " - วันที่: " . $row['date'] . "<br>";
}
?>