<?php
include'db.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Same CSS as in fire.php */
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
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        .form-container {
            width: 100%;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-header h2 {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: white;
            font-size: 0.9rem;
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s;
        }
/* Add this to your existing style section */
select option {
    background: var(--dark);
    color: var(--light);
    padding: 10px;
}

/* For better dropdown arrow visibility */
select {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23ffffff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
}
        input[type="text"]:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.3);
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
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
            width: 100%;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.4);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.6);
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert.success {
            background: rgba(40, 167, 69, 0.2);
            border-left: 4px solid var(--success);
            color: white;
        }

        .alert.error {
            background: rgba(220, 53, 69, 0.2);
            border-left: 4px solid var(--danger);
            color: white;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2>Police Department Details</h2>
            <p>Complete your police registration</p>
        </div>

        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">
            <div class="form-group">
                <label>District</label>
                <input type="text" name="district" required>
            </div>

            <div class="form-group">
                <label>Station</label>
                <input type="text" name="station" required>
            </div>

            <div class="form-group">
                <label>Rank</label>
                <select name="rank" required>
                    <option value="Assistant Sub-Inspector">Assistant Sub-Inspector (ASI)</option>
                    <option value="Sub-Inspector">Sub-Inspector (SI)</option>
                    <option value="Inspector">Inspector</option>
                    <option value="Deputy Superintendent of Police">Deputy Superintendent of Police (DSP)</option>
                    <option value="Superintendent of Police">Superintendent of Police (SP)</option>
                    <option value="Senior Superintendent of Police">Senior Superintendent of Police (SSP)</option>
                    <option value="Deputy Inspector General">Deputy Inspector General (DIG)</option>
                    <option value="Additional Inspector General">Additional Inspector General (Addl. IG)</option>
                    <option value="Inspector General">Inspector General of Police (IGP)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Shift Timing</label>
                <select name="shift_timing" required>
                    <option value="Morning">Morning</option>
                    <option value="Evening">Evening</option>
                    <option value="Night">Night</option>
                </select>
            </div>

            <div class="form-group">
                <label>Police ID</label>
                <input type="text" name="pid" required>
            </div>

            <button type="submit" name="submit" class="btn-submit">
                <i class="fas fa-shield-alt"></i> Submit Details
            </button>
        </form>
    </div>
</body>
</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST['submit'])) {
    if (!isset($_SESSION['authorityID'])) {
        die("<div class='alert error'>Session expired. Please re-register authority.</div>");
    }

    $authorityID = $_SESSION['authorityID'];
    $station = $_POST['station'];
    $district = $_POST['district'];
    $shift = $_POST['shift_timing'];
    $rank = $_POST['rank'];
    $policeid = $_POST['pid'];

    $check = "SELECT * FROM Police WHERE policeID = '$policeid'";
    $result = mysqli_query($conn, $check);
    
    if (mysqli_num_rows($result) > 0) {
        echo "<div class='alert error'>❌ Police ID already exists. Please use a different ID.</div>";
    } else {
        $sql = "INSERT INTO Police (policeID, stationName, district, shiftTiming, rank, authorityID) 
                VALUES ('$policeid', '$station', '$district', '$shift', '$rank', '$authorityID')";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='alert success'>✅ Police Registration Complete. Redirecting to Home...</div>";
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 2000);
            </script>";
        } else {
            echo "<div class='alert error'>❌ Error: " . mysqli_error($conn) . "</div>";
        }
    }
}
?>
