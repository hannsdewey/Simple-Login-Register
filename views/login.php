<?php
session_start();

// If already logged in, redirect to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Login</title>
</head>

<body>
    <?php if (!empty($error)): ?>
        <p style="color: red; text-align: center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <div class="log_container">
        <form method="POST" action="">
            <h1>Palace</h1>
            <div class="input-field">
                <input type="text" name="username" placeholder="Enter Your Username" required>
                <input type="password" name="password" placeholder="Enter Your Password" id="myInput" required>
            </div>
            <div class="password-options">
                <label for="show">
                    <input type="checkbox" onclick="myFunction()" id="showpass">
                    <p>Show Password</p>
                </label>
                <a href="">Forgot Password?</a>
            </div>
            <button type="submit" name="login">Login</button>
            <div class="account-options">
                <p>Don't have an account? <a href="registration.php" class="pseudolink">Sign up
                    </a></p>
            </div>
        </form>
    </div>
</body>

</html>

<?php

$error = "";
require_once __DIR__ . '/../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = $_POST["password"] ?? '';

    // Prepare query
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Auth success
            $_SESSION["username"] = $user['username'];
            $_SESSION["user_id"] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "❌ Invalid username or password.";
        }
    } else {
        $error = "⚠️ Login error. Please try again later.";
    }

    $stmt->close();
    $conn->close();
}
?>

<script>
    function myFunction() {
        var x = document.getElementById("myInput");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>