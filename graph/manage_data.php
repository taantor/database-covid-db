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

        <!-- Form สำหรับเลือกการเรียงลำดับ การแสดงผล และการค้นหา -->
        <form method="GET" class="mb-4">
            <!-- กลุ่มการเรียงลำดับและจำนวนที่ต้องการแสดง -->
            <div class="form-row">
                <div class="col-md-3">
                    <label for="sort">เรียงลำดับโดย:</label>
                    <select name="sort" id="sort" class="custom-select">
                        <option value="state"
                            <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'state') ? 'selected' : ''; ?>>ชื่อรัฐ
                        </option>
                        <option value="cases"
                            <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'cases') ? 'selected' : ''; ?>>
                            จำนวนผู้ติดเชื้อ</option>
                        <option value="deaths"
                            <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'deaths') ? 'selected' : ''; ?>>
                            จำนวนผู้เสียชีวิต</option>
                        <option value="date"
                            <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'date') ? 'selected' : ''; ?>>วันที่
                        </option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="order">ลำดับ:</label>
                    <select name="order" id="order" class="custom-select">
                        <option value="ASC"
                            <?php echo (isset($_GET['order']) && $_GET['order'] == 'ASC') ? 'selected' : ''; ?>>
                            น้อยไปมาก</option>
                        <option value="DESC"
                            <?php echo (isset($_GET['order']) && $_GET['order'] == 'DESC') ? 'selected' : ''; ?>>
                            มากไปน้อย</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="limit">แสดง:</label>
                    <select name="limit" id="limit" class="custom-select">
                        <option value="10"
                            <?php echo (isset($_GET['limit']) && $_GET['limit'] == '10') ? 'selected' : ''; ?>>Top 10
                        </option>
                        <option value="20"
                            <?php echo (isset($_GET['limit']) && $_GET['limit'] == '20') ? 'selected' : ''; ?>>Top 20
                        </option>
                        <option value="50"
                            <?php echo (isset($_GET['limit']) && $_GET['limit'] == '50') ? 'selected' : ''; ?>>Top 50
                        </option>
                        <option value="all"
                            <?php echo (isset($_GET['limit']) && $_GET['limit'] == 'all') ? 'selected' : ''; ?>>
                            แสดงทั้งหมด</option>
                    </select>
                </div>
            </div>

            <!-- กลุ่มสำหรับค้นหาและกรองข้อมูล -->
            <div class="form-row mt-4">
                <div class="col-md-2">
                    <label for="cases_min">จำนวนผู้ติดเชื้อขั้นต่ำ:</label>
                    <input type="number" name="cases_min" id="cases_min" class="form-control" placeholder="min"
                        value="<?php echo isset($_GET['cases_min']) ? $_GET['cases_min'] : '0'; ?>">
                </div>
                <div class="col-md-2">
                    <label for="cases_max">จำนวนผู้ติดเชื้อสูงสุด:</label>
                    <input type="number" name="cases_max" id="cases_max" class="form-control" placeholder="max"
                        value="<?php echo isset($_GET['cases_max']) ? $_GET['cases_max'] : '10000000'; ?>">
                </div>
                <div class="col-md-2">
                    <label for="deaths_min">จำนวนผู้เสียชีวิตขั้นต่ำ:</label>
                    <input type="number" name="deaths_min" id="deaths_min" class="form-control" placeholder="min"
                        value="<?php echo isset($_GET['deaths_min']) ? $_GET['deaths_min'] : '0'; ?>">
                </div>
                <div class="col-md-2">
                    <label for="deaths_max">จำนวนผู้เสียชีวิตสูงสุด:</label>
                    <input type="number" name="deaths_max" id="deaths_max" class="form-control" placeholder="max"
                        value="<?php echo isset($_GET['deaths_max']) ? $_GET['deaths_max'] : '10000000'; ?>">
                </div>
            </div>

            <div class="form-row mt-4">
                <div class="col-md-4">
                    <label for="state_search">ค้นหาชื่อรัฐ:</label>
                    <input type="text" name="state_search" id="state_search" class="form-control" placeholder="ชื่อรัฐ">
                </div>
            </div>

            <button type="submit" class="btn btn-secondary mt-4">ค้นหาและเรียงลำดับ</button>
        </form>

        <a href="insert_form.php" class="btn btn-primary mb-4">เพิ่มข้อมูล</a>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th>ชื่อรัฐ</th>
                    <th>จำนวนผู้ติดเชื้อ</th>
                    <th>จำนวนผู้เสียชีวิต</th>
                    <th>วันที่</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!isset($pdo)) {
                    die("ไม่สามารถเชื่อมต่อกับฐานข้อมูล");
                }

                // ตรวจสอบและตั้งค่าเริ่มต้นสำหรับตัวกรอง
                $sort = $_GET['sort'] ?? 'date';
                $order = $_GET['order'] ?? 'DESC';
                $limit = $_GET['limit'] ?? '10';
                $state_search = $_GET['state_search'] ?? '';

                // ดึงค่าขั้นต่ำและสูงสุดจากฐานข้อมูลถ้าไม่ได้ระบุค่า
                $cases_min = $_GET['cases_min'] ?? null;
                $cases_max = $_GET['cases_max'] ?? null;
                $deaths_min = $_GET['deaths_min'] ?? null;
                $deaths_max = $_GET['deaths_max'] ?? null;

                if (is_null($cases_min) || is_null($cases_max) || is_null($deaths_min) || is_null($deaths_max)) {
                    $range_sql = "SELECT MIN(cases) as min_cases, MAX(cases) as max_cases, MIN(deaths) as min_deaths, MAX(deaths) as max_deaths FROM us_state_miss";
                    $range_stmt = $pdo->prepare($range_sql);
                    $range_stmt->execute();
                    $range = $range_stmt->fetch(PDO::FETCH_ASSOC);

                    if (is_null($cases_min)) $cases_min = $range['min_cases'];
                    if (is_null($cases_max)) $cases_max = $range['max_cases'];
                    if (is_null($deaths_min)) $deaths_min = $range['min_deaths'];
                    if (is_null($deaths_max)) $deaths_max = $range['max_deaths'];
                }

                $sql = "SELECT t1.date, t1.state, t1.cases, t1.deaths
                        FROM us_state_miss t1
                        JOIN (
                            SELECT state, MAX(date) AS max_date
                            FROM us_state_miss
                            GROUP BY state
                        ) t2 ON t1.state = t2.state AND t1.date = t2.max_date
                        WHERE t1.cases BETWEEN :cases_min AND :cases_max
                        AND t1.deaths BETWEEN :deaths_min AND :deaths_max";

                if (!empty($state_search)) {
                    $sql .= " AND t1.state LIKE :state_search";
                    $state_search = "%$state_search%";
                }

                $sql .= " ORDER BY $sort $order";

                if ($limit !== 'all') {
                    $sql .= " LIMIT $limit";
                }

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':cases_min', $cases_min, PDO::PARAM_INT);
                $stmt->bindParam(':cases_max', $cases_max, PDO::PARAM_INT);
                $stmt->bindParam(':deaths_min', $deaths_min, PDO::PARAM_INT);
                $stmt->bindParam(':deaths_max', $deaths_max, PDO::PARAM_INT);

                if (!empty($state_search)) {
                    $stmt->bindParam(':state_search', $state_search, PDO::PARAM_STR);
                }

                $stmt->execute();
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($results as $row) {
                    echo "<tr>
                            <td>{$row['state']}</td>
                            <td>{$row['cases']}</td>
                            <td>{$row['deaths']}</td>
                            <td>{$row['date']}</td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>