<?php
include 'connect.php';

$firstName = '';
$lastName = '';
$email = '';
$errorMessage = '';

if (isset($_POST['signUp'])) {
    $firstName = $_POST['fName'];
    $lastName = $_POST['lName'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $checkEmail = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $checkEmail->bindParam(':email', $email);
    $checkEmail->execute();
    $result = $checkEmail->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $errorMessage = "This email already register. Please Sign In!";
    } else {
        $insertQuery = $conn->prepare("INSERT INTO users (firstName, lastName, email, password) VALUES (:firstName, :lastName, :email, :password)");
        $insertQuery->bindParam(':firstName', $firstName);
        $insertQuery->bindParam(':lastName', $lastName);
        $insertQuery->bindParam(':email', $email);
        $insertQuery->bindParam(':password', $password);

        if ($insertQuery->execute()) {
            header("location: index.php");
            exit();
        } else {
            $errorMessage = "Error: " . $conn->errorInfo()[2];
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        session_start();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['email'] = $row['email'];
        header("Location: graph/start.php");
        exit();
    } else {
        $errorMessage = "Incorrect Email or Password Please try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register & Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
    /* Popup styles */
    .popup {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        justify-content: center;
        align-items: center;
        z-index: 999;
    }

    .popup-content {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        width: 400px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.4);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    </style>
</head>

<body>
    <div class="container" id="signup">
        <h1 class="form-title">Register</h1>
        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="fName" id="fName" placeholder="First Name" required
                    value="<?php echo htmlspecialchars($firstName); ?>">
                <label for="fName">First Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="lName" id="lName" placeholder="Last Name" required
                    value="<?php echo htmlspecialchars($lastName); ?>">
                <label for="lName">Last Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required
                    value="<?php echo htmlspecialchars($email); ?>">
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign Up" name="signUp">
        </form>
        <div class="links">
            <p>Already Have an Account?</p>
            <button id="signInButton">Sign In</button>
        </div>
    </div>

    <div class="container" id="signIn" style="display:none;">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="signInEmail" placeholder="Email" required
                    value="<?php echo htmlspecialchars($email); ?>">
                <label for="signInEmail">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="signInPassword" placeholder="Password" required>
                <label for="signInPassword">Password</label>
            </div>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <div class="links">
            <p>Don't have an account yet?</p>
            <button id="signUpButton">Sign Up</button>
        </div>
    </div>

    <div class="popup" id="popup">
        <div class="popup-content">
            <h2><?php echo $errorMessage; ?></h2>
            <br>
            <button onclick="closePopup()">OK</button>
        </div>
    </div>

    <script>
    const signInButton = document.getElementById('signInButton');
    const signUpButton = document.getElementById('signUpButton');
    const signInContainer = document.getElementById('signIn');
    const signUpContainer = document.getElementById('signup');

    signInButton.onclick = function() {
        // Show the Sign In form and hide the Sign Up form
        signInContainer.style.display = 'block';
        signUpContainer.style.display = 'none';
    }

    signUpButton.onclick = function() {
        // Show the Sign Up form and hide the Sign In form
        signUpContainer.style.display = 'block';
        signInContainer.style.display = 'none';
    }

    function closePopup() {
        document.getElementById('popup').style.display = 'none';
    }

    <?php if ($errorMessage): ?>
    // Show the popup on error
    document.getElementById('popup').style.display = 'flex';
    // If there's an error, show the Sign In form
    signInContainer.style.display = 'block';
    signUpContainer.style.display = 'none';
    <?php endif; ?>
    </script>
</body>

</html>