<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มข้อมูลรัฐใหม่</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <?php include 'server_test.php'; // ตรวจสอบการเชื่อมต่อฐานข้อมูล ?>

    <div class="container mt-5">
        <h1 class="mb-4">เพิ่มข้อมูลรัฐใหม่</h1>
        <form action="insert.php" method="post">
            <div class="form-group">
                <label for="state">รัฐ:</label>
                <input type="text" class="form-control" id="state" name="state" required>
            </div>
            <div class="form-group">
                <label for="fips">FIPS:</label>
                <input type="text" class="form-control" id="fips" name="fips" required>
            </div>
            <div class="form-group">
                <label for="cases">จำนวนผู้ติดเชื้อ:</label>
                <input type="number" class="form-control" id="cases" name="cases" required>
            </div>
            <div class="form-group">
                <label for="deaths">จำนวนผู้เสียชีวิต:</label>
                <input type="number" class="form-control" id="deaths" name="deaths" required>
            </div>
            <div class="form-group">
                <label for="date">วันที่:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
            <a href="manage_data.php" class="btn btn-secondary">กลับ</a>
        </form>
    </div>
</body>

</html>