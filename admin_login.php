<?php
include 'db.php';

$error = ""; // Ensure error is initialized

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['admin_username'];
    $password = $_POST['admin_password'];

    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error = "❌ Invalid admin credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
    :root {
        --primary: #1f6feb;
        --secondary: #0d1117;
        --accent: #f778ba;
        --light: #c9d1d9;
        --dark: #010409;
        --success: #2ea043;
        --danger: #f85149;
        --purple: #8250df;
    }

    body {
        font-family: 'Montserrat', sans-serif;
        background: url('https://images.unsplash.com/photo-1566438480900-0609be27a4be?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80') center/cover no-repeat fixed;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        color: var(--light);
    }

    .login-container {
        background-color: #161b22;
        padding: 35px 40px;
        border-radius: 12px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
        width: 350px;
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: var(--light);
        font-weight: 600;
    }

    label {
        display: block;
        margin-bottom: 6px;
        color: var(--light);
        font-weight: 500;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 20px;
        border: 1px solid #444c56;
        background-color: #0d1117;
        color: var(--light);
        border-radius: 6px;
        font-size: 14px;
        outline: none;
    }

    input[type="text"]::placeholder,
    input[type="password"]::placeholder {
        color: #999;
    }

    input[type="submit"] {
        width: 100%;
        padding: 12px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease, transform 0.2s;
    }

    input[type="submit"]:hover {
        background-color: #1a65d1;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(31, 111, 235, 0.3);
    }

    .link {
        text-align: center;
        margin-top: 20px;
    }

    .link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .link a:hover {
        text-decoration: underline;
    }

    .error {
        color: var(--danger);
        text-align: center;
        font-weight: 500;
        margin-top: 15px;
    }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Admin Login</h2>
    <form method="POST">
        <label for="admin_username">Username:</label>
        <input type="text" name="admin_username" id="admin_username" placeholder="Enter admin username" required>

        <label for="admin_password">Password:</label>
        <input type="password" name="admin_password" id="admin_password" placeholder="Enter password" required>

        <input type="submit" value="Login">
    </form>

    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <div class="link">
        <a href="login.php">← Back to User Login</a>
    </div>
</div>

</body>
</html>
