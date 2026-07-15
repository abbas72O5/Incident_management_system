<?php
include 'db.php';
session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report an Incident</title>
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
        input[type="date"]:focus,
        input[type="time"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
            background: white;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
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
<div style="position: absolute; top: 20px; left: 20px; z-index: 10;">
    
</div>

    <div class="form-container">
        <div class="form-decoration">
            <div class="decoration-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h2>Report an Incident</h2>
            <p>Help us make your community safer by reporting incidents in your area. Your report matters!</p>
            <div class="decoration-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
        </div>
        
        <div class="form-content">
            <div class="form-header">
                <h3>Incident Details</h3>
                <p>Please provide accurate information about the incident</p>
            </div>
            
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST">
                <h4 style="color: var(--primary); margin-bottom: 15px; font-weight: 600;">
                    <i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>Location Details
                </h4>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="province">Province</label>
                        <select id="province" name="province" required>
                            <option value="">--Select Province--</option>
                            <option value="Punjab">Punjab</option>
                            <option value="Sindh">Sindh</option>
                            <option value="Khyber Pakhtunkhwa">Khyber Pakhtunkhwa</option>
                            <option value="Balochistan">Balochistan</option>
                            <option value="Islamabad Capital Territory">Islamabad Capital Territory</option>
                            <option value="Gilgit-Baltistan">Gilgit-Baltistan</option>
                            <option value="Azad Jammu and Kashmir">Azad Jammu and Kashmir</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="text" id="area" name="area" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="locality">Locality (optional)</label>
                        <input type="text" id="locality" name="locality">
                    </div>
                </div>
                
                <h4 style="color: var(--primary); margin: 25px 0 15px; font-weight: 600;">
                    <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>Incident Information
                </h4>
                
                <div class="form-group">
                    <label for="incidentType">Incident Type</label>
                    <select id="incidentType" name="incidentType" required>
                        <option value="">--Select Incident Type--</option>
                        <option value="Traffic Accident">Accident</option>
                        <option value="Road Block">Road Block</option>
                        <option value="Fire">Fire</option>
                        <option value="Crime">Crime</option>
                        <option value="Theft">Theft</option>
                        <option value="Flood">Flood</option>
                        <option value="Sewage Issue">Sewage Issue</option>
                        <option value="Water leakage">Water leakage</option>
                        <option value="Garbage Mismanagement">Garbage Mismanagement</option>
                        <option value="Pothole danger">Pothole danger</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Date of Incident</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="time">Time of Incident</label>
                        <input type="time" id="time" name="time" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" required></textarea>
                </div>
                
                <button type="submit" class="btn-submit">
                    Submit Report <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</body>
</html>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// 1. Extract fields from submitted report
$incidentType = $_POST['incidentType'];
$province = $_POST['province'];
$city = strtolower($_POST['city']);
$area = strtolower($_POST['area']);
$locality = $_POST['locality']; // optional
$date = $_POST['date'];
$time = $_POST['time'];
$description = $_POST['description'];
$userID = $_SESSION['user_id'];
$relevantDept = '';
if ($incidentType == 'Theft' || $incidentType == 'Crime') {
    $relevantDept = 'Police';
} elseif ($incidentType == 'Fire') {
    $relevantDept = 'FireBrigade';
} elseif ($incidentType == 'Traffic Accident' || $incidentType == 'Road Block') {
    $relevantDept = 'Traffic';
} elseif ($incidentType == 'Flood' || $incidentType == 'Sewage Issue' || $incidentType == 'Water leakage' || $incidentType == 'Garbage Mismanagement' || $incidentType == 'Pothole danger') {
    $relevantDept = 'Municipal';
}


// 2. Find locationID first
$sql_location = "SELECT locationID FROM Location 
                 WHERE province = '$province' 
                 AND city = '$city' 
                 AND area = '$area'";

$result_location = mysqli_query($conn, $sql_location);

if (mysqli_num_rows($result_location) > 0) {
    $row = mysqli_fetch_assoc($result_location);
    $locationID = $row['locationID'];
} else {
    // Insert new location if not exists
    $insert_location = "INSERT INTO Location (province, city, area, locality) 
                        VALUES ('$province', '$city', '$area', '$locality')";
    mysqli_query($conn, $insert_location);
    $locationID = mysqli_insert_id($conn);
}

// 3. Find matching incident
$sql = "SELECT i.incidentID 
        FROM incident i
        JOIN Location l ON i.locationID = l.locationID
        WHERE i.incidentType = '$incidentType'
        AND l.province = '$province'
        AND l.city = '$city'
        AND l.area = '$area'
        AND l.locality = '$locality'
        AND i.date = '$date'
       AND ABS(TIMESTAMPDIFF(MINUTE, 
                            CONCAT(i.date, ' ', i.time), 
                            CONCAT('$date', ' ', '$time'))) <= 60";


$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Existing incident found
    $row = mysqli_fetch_assoc($result);
    $incidentID = $row['incidentID'];

    // Insert into Reports table
    $insertReport = "INSERT INTO Reports (incidentID, userID, reportTime,reportText)
                     VALUES ('$incidentID', '$userID', NOW(),'$description')";
    mysqli_query($conn, $insertReport);
    echo "Report added for existing incident!";
    
} else {
    // Determine user role
    $userID = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];
    
    // Set incident status based on role
    $status = ($userRole === 'authority') ? 'verified' : 'unverified';
    $insertIncident = "INSERT INTO Incident (locationID, incidentType, date, time, description, status, relevantDept)
                       VALUES ('$locationID', '$incidentType', '$date', '$time', '$description', '$status','$relevantDept')";
    if (mysqli_query($conn, $insertIncident)) {
        $incidentID = mysqli_insert_id($conn);

        // Insert into Reports table
        $insertReport = "INSERT INTO Reports (incidentID, userID, reportTime,reportText)
                         VALUES ('$incidentID', '$userID', NOW(),'$description')";
        mysqli_query($conn, $insertReport);
        echo "New incident and report added!";
        
    } else {
        echo "Error inserting incident: " . mysqli_error($conn);
    }
}}
?>
