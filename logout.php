<?php
session_start();

if (isset($_POST['confirm_logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logout Confirmation</title>
</head>
<body>
    <h2>Are you sure you want to log out?</h2>
    <form method="POST" action="logout.php">
        <button type="submit" name="confirm_logout">Yes, Log Out</button>
        <button type="button" onclick="window.location.href='dashboard.php';">Cancel</button>
    </form>
</body>
</html>
