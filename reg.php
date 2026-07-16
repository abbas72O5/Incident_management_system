<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["Register"]) and $_POST["Register"] == "REGISTER") {
    $username = $_POST["username"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $password = $_POST["password"];
    $cnic = $_POST["cnic"];
    $role = strtolower($_POST["role"]);

    $check_sql = "SELECT * FROM users WHERE cnic = '$cnic'";
    $result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>
                alert('❌ CNIC already registered. Please use another.');
                window.location.href = '".$_SERVER["PHP_SELF"]."';
              </script>";
    } else {
        $sql = "INSERT INTO users(name, cnic, password, role, first_name, last_name) 
                VALUES ('$username','$cnic','$password','$role','$firstname','$lastname')";

        if (mysqli_query($conn, $sql)) {
            $userID = mysqli_insert_id($conn);
            $_SESSION['userID'] = $userID;

            if ($role == "authority") {
                header("Location: authorityreg.php");
                exit();
            } else if ($role == "citizen") {
                header("Location: citizenreg.php");
                exit();
            }
        } else {
            echo "<script>
                    alert('Error: ".mysqli_error($conn)."');
                    window.location.href = '".$_SERVER["PHP_SELF"]."';
                  </script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #4D44DB;
            --accent: #FF6584;
            --light: #F8F9FA;
            --dark: #212529;
            --success: #28A745;
            --warning: #FFC107;
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: url('https://images.unsplash.com/photo-1566438480900-0609be27a4be?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80') center/cover no-repeat fixed;
            color: var(--light);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .form-container {
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
        }

        .form-decoration {
            width: 40%;
            background: rgba(0, 0, 0, 0.1);
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .form-decoration h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-decoration p {
            font-size: 1rem;
            margin-bottom: 30px;
            color: rgba(255, 255, 255, 0.9);
        }

        .decoration-icon {
            font-size: 4rem;
            color: white;
            margin-bottom: 20px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-content {
            width: 60%;
            padding: 50px;
            background: white;
        }

        .form-header {
            margin-bottom: 30px;
        }

        .form-header h3 {
            font-family: 'Playfair Display', serif;
            color: var(--dark);
            font-size: 2rem;
            margin-bottom: 10px;
            position: relative;
            display: inline-block;
        }

        .form-header h3::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--primary);
        }

        .form-header p {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="password"],
        input[type="date"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
            background: white;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .radio-option {
            display: flex;
            align-items: center;
        }

        .radio-option input {
            margin-right: 8px;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            background: var(--gradient-bg);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.4);
            width: 100%;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.6);
        }

        .btn-submit i {
            margin-left: 8px;
            font-size: 1.1rem;
        }

        .error-message {
            color: var(--accent);
            text-align: center;
            margin-top: 15px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .form-container {
                flex-direction: column;
            }

            .form-decoration,
            .form-content {
                width: 100%;
            }

            .form-decoration {
                padding: 30px;
            }

            .form-content {
                padding: 30px;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }

        /* Desktop optimization */
        @media (min-width: 1024px) {
            .form-container {
                max-width: 1200px;
                min-height: 85vh;
            }

            .form-decoration {
                padding: 60px;
            }

            .form-content {
                padding: 60px;
            }

            .form-row {
                gap: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-decoration">
            <div class="decoration-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2>User Registration</h2>
            <p>Create your account to start reporting incidents or access authority features</p>
            <div class="decoration-icon">
                <i class="fas fa-id-card"></i>
            </div>
        </div>
        
        <div class="form-content">
            <div class="form-header">
                <h3>Account Information</h3>
                <p>Please provide your personal details for registration</p>
            </div>
            
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" id="lastname" name="lastname" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="cnic">CNIC</label>
                    <input type="text" id="cnic" name="cnic" pattern="[0-9]{5}-[0-9]{7}-[0-9]{1}" 
                           title="Format: 61101-0687187-3" placeholder="61101-0687187-3" required>
                </div>
                
                <div class="form-group">
                    <label>Role</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="authority" name="role" value="Authority" required>
                            <label for="authority">Authority</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="citizen" name="role" value="Citizen" required>
                            <label for="citizen">Citizen</label>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="Register" value="REGISTER" class="btn-submit">
                    Register <i class="fas fa-user-edit"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>
