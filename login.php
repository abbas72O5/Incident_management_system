<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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

    .admin-link {
        text-align: center;
        margin-top: 20px;
    }

    .admin-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .admin-link a:hover {
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
    <h2>Login</h2>
    <form action="login.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your username">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password">

        <input type="submit" value="Login">
    </form>

    <div class="admin-link">
        <a href="admin_login.php">Admin Login</a>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['username'];
        $password = $_POST['password'];
        
        $sql = "SELECT * FROM users WHERE name='$name'";
        $result = $conn->query($sql);
        
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            
            if ($password == $user['password']) {
                $_SESSION['user_id'] = $user['userID'];
                $_SESSION['role'] = strtolower($user['role']);

                if ($_SESSION['role'] == 'authority') {
                    $uid = $user['userID'];
                    $auth_sql = "SELECT * FROM Authority WHERE userID = '$uid'";
                    $auth_result = mysqli_query($conn, $auth_sql);

                    if (mysqli_num_rows($auth_result) == 1) {
                        $auth = mysqli_fetch_assoc($auth_result);
                        
                        if ($auth['status'] == 'approved') {
                            $_SESSION['authorityID'] = $auth['authorityID'];
                            header("Location: authority_dashboard.php");
                            exit();
                        } else {
                            echo "<p class='error'>❌ Your registration is still pending admin approval.</p>";
                        }
                    } else {
                        echo "<p class='error'>❌ Authority details not found. Please complete registration.</p>";
                    }
                } 
                else if ($_SESSION['role'] == 'citizen') {
                    header("Location: citizen_dashboard.php");
                    exit();
                }

            } else {
                echo "<p class='error'>❌ Invalid credentials.</p>";
            }
        } else {
            echo "<p class='error'>❌ User not found.</p>";
        }
    }
    ?>
</div>

</body>
</html>
