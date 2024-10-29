<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แสดงข้อมูลรัฐ</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php 
    include 'navbar.php'; 
    include 'server_test.php'; // ตรวจสอบการเชื่อมต่อฐานข้อมูล 
    ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">ข้อมูลรัฐ</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>รัฐ</th>
                    <th>จำนวนผู้ติดเชื้อ</th>
                    <th>จำนวนผู้เสียชีวิต</th>
                    <th>วันที่</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ตรวจสอบว่าตัวแปร $pdo ถูกสร้างขึ้นหรือไม่
                if (!isset($pdo)) {
                    die("ไม่สามารถเชื่อมต่อกับฐานข้อมูล");
                }

                // คำสั่ง SQL สำหรับดึงข้อมูล
                $sql = "SELECT t1.date, t1.state, t1.cases, t1.deaths
                        FROM us_state_miss t1
                        JOIN (
                            SELECT state, MAX(date) AS max_date
                            FROM us_state_miss
                            GROUP BY state
                        ) t2 ON t1.state = t2.state AND t1.date = t2.max_date"; // เรียงลำดับตามวันที่ล่าสุด
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute();

                // แสดงข้อมูลในตาราง
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['state']}</td>
                            <td>{$row['cases']}</td>
                            <td>{$row['deaths']}</td>
                            <td>{$row['date']}</td>
                        </tr>";
                }

                // ตรวจสอบว่ามีข้อมูลในฐานข้อมูลหรือไม่
                if ($stmt->rowCount() == 0) {
                    echo "<tr><td colspan='4' class='text-center'>ไม่มีข้อมูล</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="insert_form.php" class="btn btn-primary">เพิ่มข้อมูลรัฐใหม่</a>
    </div>
</body>
</html>
