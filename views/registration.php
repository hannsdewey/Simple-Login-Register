<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php

// Include your database connection file
// require_once __DIR__ . '/../db.php';
// If the above path is incorrect, try the following alternative:
require_once __DIR__ . '/../config/db.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["passwordRepeat"];

    // Validation
    if (empty($fullname) || empty($email) || empty($username) || empty($password) || empty($passwordRepeat)) {
        $errors[] = "All fields are required";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    if ($password !== $passwordRepeat) {
        $errors[] = "Passwords do not match";
    }

    // Proceed if no errors
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (full_name, email, username, password) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssss", $fullname, $email, $username, $passwordHash);
            if ($stmt->execute()) {
                header("Location: registration.php?success=1");
                exit;
            } else {
                $errors[] = "Execute error: " . $stmt->error;
            }
        } else {
            $errors[] = "Prepare failed: " . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/reg.css">
    <title>Registration Form</title>
</head>

<body>
    <div class="reg_container">
        <h1>Palace</h1>
        <p>Register your Account.</p>

        <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
        }

        if (isset($_GET['success'])) {
            echo "<div class='alert alert-success'>Registration successful! <a href='login.php'>Login</a></div>";
        }
        ?>

        <form method="post" action="registration.php">
            <input type="text" name="fullname" placeholder="Enter Your Full Name">
            <input type="email" name="email" placeholder="example@gmail.com">
            <input type="text" name="username" placeholder="Enter Your Username">
            <input type="password" name="password" placeholder="Enter Password" minlength="6" maxlength="20">
            <input type="password" name="passwordRepeat" placeholder="Confirm Password">
            <p>Do you have an account? <a href="login.php" class="pseudolink">Sign In</a></p>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>

</html>