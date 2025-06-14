<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard</title>
</head>

<body>
    <form action="logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
    <h1>Welcome to the Dashboard</h1>
</body>

</html>
<script>
    window.onload = function() {
        if (performance.navigation.type === 2) {
            location.reload(true);
        }
    };
</script>