<?php
include 'db.php';
session_start();

// Process form submission first
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (!isset($_SESSION['userID'])) {
        $error = "User session not found. Please register first.";
    } else {
        $userID = $_SESSION['userID'];
        $province = $_POST['province'];
        $city = strtolower($_POST['city']);
        $department = $_POST['department'];
        $contactNumber = $_POST['contactNumber'];
        $status = 'pending';

        $auth_sql = "INSERT INTO Authority (province, city, department, contactNumber, status, userID) 
                    VALUES ('$province', '$city', '$department', '$contactNumber', '$status', '$userID')";

        if (mysqli_query($conn, $auth_sql)) {
            $authorityID = mysqli_insert_id($conn);
            $_SESSION['authorityID'] = $authorityID;
            
            // Determine redirect URL based on department
            $redirectUrl = 'authority_dashboard.php'; // Default
            if ($department == 'Police') $redirectUrl = 'police.php';
            elseif ($department == 'FireBrigade') $redirectUrl = 'fire.php';
            elseif ($department == 'Traffic') $redirectUrl = 'traffic.php';
            elseif ($department == 'Municipal') $redirectUrl = 'municipal.php';
            
            header("Location: $redirectUrl");
            exit();
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authority Registration</title>
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
            --danger: #DC3545;
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

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
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
        select {
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
        select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
            background: white;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }

        select option {
            background: white;
            color: var(--dark);
            padding: 10px;
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
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.6);
        }

        .btn-submit i {
            margin-left: 8px;
            font-size: 1.1rem;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
        }

        .alert.error {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
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
        }

        /* Desktop optimization */
        @media (min-width: 1024px) {
            .form-container {
                max-width: 1000px;
            }

            .form-decoration {
                padding: 60px;
            }

            .form-content {
                padding: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-decoration">
            <div class="decoration-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Authority Registration</h2>
            <p>Complete your department details to access authority features</p>
            <div class="decoration-icon">
                <i class="fas fa-building"></i>
            </div>
        </div>
        
        <div class="form-content">
            <div class="form-header">
                <h3>Department Information</h3>
                <p>Please provide accurate details about your authority</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <div class="form-group">
                    <label for="province">Province</label>
                    <select name="province" id="province" required>
                        <option value="">--Select Province--</option>
                        <option value="Punjab" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Punjab') ? 'selected' : ''; ?>>Punjab</option>
                        <option value="Sindh" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Sindh') ? 'selected' : ''; ?>>Sindh</option>
                        <option value="Khyber Pakhtunkhwa" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Khyber Pakhtunkhwa') ? 'selected' : ''; ?>>Khyber Pakhtunkhwa</option>
                        <option value="Balochistan" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Balochistan') ? 'selected' : ''; ?>>Balochistan</option>
                        <option value="Gilgit-Baltistan" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Gilgit-Baltistan') ? 'selected' : ''; ?>>Gilgit-Baltistan</option>
                        <option value="Azad Jammu and Kashmir" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Azad Jammu and Kashmir') ? 'selected' : ''; ?>>Azad Jammu and Kashmir</option>
                        <option value="Islamabad Capital Territory" <?php echo (isset($_POST['province']) && $_POST['province'] == 'Islamabad Capital Territory') ? 'selected' : ''; ?>>Islamabad Capital Territory</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" id="city" required value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="department">Department</label>
                    <select name="department" id="department" required>
                        <option value="Police" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Police') ? 'selected' : ''; ?>>Police</option>
                        <option value="FireBrigade" <?php echo (isset($_POST['department']) && $_POST['department'] == 'FireBrigade') ? 'selected' : ''; ?>>Fire Brigade</option>
                        <option value="Traffic" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Traffic') ? 'selected' : ''; ?>>Traffic</option>
                        <option value="Municipal" <?php echo (isset($_POST['department']) && $_POST['department'] == 'Municipal') ? 'selected' : ''; ?>>Municipal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contactNumber">Contact Number</label>
                    <input type="text" name="contactNumber" id="contactNumber" required value="<?php echo isset($_POST['contactNumber']) ? htmlspecialchars($_POST['contactNumber']) : ''; ?>">
                </div>

                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Details
                </button>
            </form>
        </div>
    </div>
</body>
</html>