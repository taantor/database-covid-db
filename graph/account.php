<?php
session_start(); // Start the session to access session variables

include '../connect.php'; // Include your database connection file

// Initialize variables
$firstName = '';
$lastName = '';
$email = '';

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];

    // Prepare and execute the query to fetch user details
    $stmt = $conn->prepare("SELECT firstName, lastName FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch user details
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $firstName = $row['firstname'];
        $lastName = $row['lastname'];
    } else {
        // Handle case where user is not found (optional)
        echo "User not found.";
    }
} else {
    // Redirect to login page if not logged in
    header("Location: /Login/register.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Account</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container">
        <h1>Account Details</h1>
        <p>First Name: <?php echo htmlspecialchars($firstName); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($lastName); ?></p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <a href="">
            <button>Log out</button>
        </a>
    </div>
</body>

</html>