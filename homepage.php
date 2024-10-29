<?php
session_start();
include("connect.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>

<body>
    <div style="text-align:center; padding:15%;">
        <p style="font-size:50px; font-weight:bold;">
            <?php 
            if(isset($_SESSION['email'])){
                $email = $_SESSION['email'];

                // ใช้ PDO ในการ query ข้อมูลผู้ใช้
                $stmt = $conn->prepare("SELECT firstname, lastname FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // ดึงข้อมูลผลลัพธ์จาก query
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($user) {
                    echo "ควยไรไอสัสนรก";
                } else {
                    echo "User not found";
                }
            }
            ?>
        </p>
        <a href="logout.php">Logout</a>
    </div>
</body>

</html>