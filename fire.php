<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (!isset($_SESSION['authorityID'])) {
        $error = "Session expired. Please re-register authority.";
    } else {
        $authorityID = $_SESSION['authorityID'];
        $area = $_POST['areaAssigned'];
        $station = $_POST['station'];
        $shift = $_POST['shift_timing'];
        $rank = $_POST['rank'];
        $fid = $_POST['fid'];

        $sql = "INSERT INTO firebrigade (fireBrigadeID, areaAssigned, stationName, shiftTiming, `rank`, authorityID)
                VALUES ('$fid', '$area', '$station', '$shift', '$rank', '$authorityID')";

        if (mysqli_query($conn, $sql)) {
            $success = "✅ Fire Brigade Registration Complete. Redirecting...";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 2000);
            </script>";
        } else {
            $error = "❌ Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fire Brigade Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Same CSS as authorityreg.php */
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

        .alert.success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: var(--secondary);
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
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-decoration">
            <div class="decoration-icon">
                <i class="fas fa-fire-extinguisher"></i>
            </div>
            <h2>Fire Brigade</h2>
            <p>Complete your fire brigade details for registration</p>
            <div class="decoration-icon">
                <i class="fas fa-fire"></i>
            </div>
        </div>
        
        <div class="form-content">
            <div class="form-header">
                <h3>Fire Brigade Details</h3>
                <p>Please provide accurate information about your station</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($success)): ?>
                <div class="alert success">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
                <div class="form-group">
                    <label for="areaAssigned">Area Assigned</label>
                    <input type="text" id="areaAssigned" name="areaAssigned" required>
                </div>
                
                <div class="form-group">
                    <label for="station">Station</label>
                    <input type="text" id="station" name="station" required>
                </div>
                
                <div class="form-group">
                    <label for="rank">Rank</label>
                    <select id="rank" name="rank" required>
                        <option value="Fireman">Fireman / Firefighter</option>
                        <option value="Leading Fireman">Leading Fireman</option>
                        <option value="Driver">Driver / Operator</option>
                        <option value="Fire Officer">Sub Officer / Fire Officer</option>
                        <option value="Station Officer">Station Officer / In-charge</option>
                        <option value="Assistant Divisional Officer">Assistant Divisional Officer (ADO)</option>
                        <option value="Divisional Officer">Divisional Officer (DO)</option>
                        <option value="Senior Divisional Officer">Senior Divisional Officer (SDO)</option>
                        <option value="Deputy Chief Fire Officer">Deputy Chief Fire Officer (DCFO)</option>
                        <option value="Chief Fire Officer">Chief Fire Officer (CFO)</option>
                        <option value="Director">Director / DG Fire Services</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="shift_timing">Shift Timing</label>
                    <select id="shift_timing" name="shift_timing" required>
                        <option value="Morning">Morning</option>
                        <option value="Evening">Evening</option>
                        <option value="Night">Night</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fid">Fire Brigade ID</label>
                    <input type="text" id="fid" name="fid" required>
                </div>
                
                <button type="submit" name="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Submit Details
                </button>
                
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </form>
        </div>
    </div>
</body>
</html>