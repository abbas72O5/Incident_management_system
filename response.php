<?php
include 'db.php';

// Check if authority is logged in
if (!isset($_SESSION['authorityID'])) {
    header("Location: login.php");
    exit();
}

$authorityID = $_SESSION['authorityID'];

// Fetch the department of the logged-in authority
$dept_query = "SELECT department FROM Authority WHERE authorityID = '$authorityID'";
$dept_result = mysqli_query($conn, $dept_query);
$dept_row = mysqli_fetch_assoc($dept_result);
$department = $dept_row['department'];

// Handle form submission for vstatus update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['statusUpdate'], $_POST['incidentID'])) {
    $incidentID = $_POST['incidentID'];
    $newStatus = $_POST['statusUpdate'] === 'verified' ? 'verified' : 'unverified';

    $update_status = "UPDATE Incident SET status = '$newStatus' WHERE incidentID = '$incidentID'";
    if (mysqli_query($conn, $update_status)) {
        $statusMessage = "<div class='alert success'><i class='fas fa-check-circle'></i> Incident status updated to $newStatus.</div>";
    } else {
        $statusMessage = "<div class='alert error'><i class='fas fa-exclamation-circle'></i> Error updating status.</div>";
    }
}
// Handle form submission for solution status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateSolutionStatus'], $_POST['responseID'], $_POST['solutionStatus'])) {
    $responseID = $_POST['responseID'];
    $newSolutionStatus = ($_POST['solutionStatus'] === 'completed') ? 'completed' : 'in progress';

    $update_solution_status = "UPDATE Response SET status = '$newSolutionStatus' WHERE responseID = '$responseID'";
    if (mysqli_query($conn, $update_solution_status)) {
        $statusMessage = "<div class='alert success'><i class='fas fa-check-circle'></i> Solution status updated to $newSolutionStatus.</div>";
    } else {
        $statusMessage = "<div class='alert error'><i class='fas fa-exclamation-circle'></i> Error updating solution status.</div>";
    }
}

// Handle form submission for response
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['responseText']) && !isset($_POST['statusUpdate'])) {
    $incidentID = $_POST['incidentID'];
    $responseText = mysqli_real_escape_string($conn, $_POST['responseText']);

    // Check if authority has already responded to this incident
    $check_query = "SELECT * FROM Response WHERE incidentID = '$incidentID' AND authorityID = '$authorityID'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // Insert response
        $insert = "INSERT INTO Response (incidentID, authorityID, responseText) 
                   VALUES ('$incidentID', '$authorityID', '$responseText')";
        if (mysqli_query($conn, $insert)) {
            $statusMessage = "<div class='alert success'><i class='fas fa-check-circle'></i> Response submitted successfully.</div>";
        } else {
            $statusMessage = "<div class='alert error'><i class='fas fa-exclamation-circle'></i> Error submitting response.</div>";
        }
    } else {
        $statusMessage = "<div class='alert warning'><i class='fas fa-info-circle'></i> You have already responded to this incident.</div>";
    }
}

// Fetch all incidents
$incident_query = "SELECT * FROM Incident where relevantDept='$department' ";
$incident_result = mysqli_query($conn, $incident_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authority Dashboard</title>
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
            --info: #17A2B8;
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
            padding: 40px 20px;
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: white;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 20px;
        }

        .dept-badge {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 600;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .incident-container {
            background: #2c2f33; /* Dark gray background */
         
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .incident-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .incident-title {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 1.5rem;
        }

        .incident-id {
            background: var(--dark);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.9rem;
        }

        .incident-details {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
        }

        .detail-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .detail-value {
            font-size: 1.1rem;
            color: white;
            font-weight: 500;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-verified {
            background: var(--success);
            color: white;
        }

        .status-unverified {
            background: var(--warning);
            color: var(--dark);
        }

        .status-inprogress {
            background: var(--info);
            color: white;
        }

        .status-completed {
            background: var(--success);
            color: white;
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

        textarea {
            width: 100%;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-family: 'Montserrat', sans-serif;
            resize: vertical;
            min-height: 100px;
            transition: all 0.3s;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.3);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .radio-option input[type="radio"] {
            accent-color: var(--primary);
        }

        .radio-option label {
            margin-bottom: 0;
            color: white;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 25px;
            background: var(--gradient-bg);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .btn i {
            margin-right: 8px;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid white;
            color: white;
            box-shadow: none;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-danger {
            background: var(--danger);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
        }

        .btn-danger:hover {
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.4);
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 0.8rem;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .responses-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .responses-title {
            font-family: 'Playfair Display', serif;
            color: white;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .response-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .response-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .response-dept {
            font-weight: 600;
            color: white;
        }

        .response-time {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.8rem;
        }

        .response-text {
            color: white;
            line-height: 1.5;
        }

        .no-responses {
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
            text-align: center;
            padding: 20px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert i {
            font-size: 1.2rem;
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

        .alert.warning {
            background: rgba(255, 193, 7, 0.2);
            border-left: 4px solid var(--warning);
            color: white;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }

            .incident-details {
                grid-template-columns: 1fr;
            }

            .action-buttons {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Add this dashboard button at the top left corner -->
    <div style="position: absolute; top: 20px; left: 20px;">
        <a href="authority_dashboard.php" class="btn btn-sm" style="background: var(--secondary);">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </div>
    <div class="container">
        <div class="header">
            <h1>Authority Dashboard</h1>
            <p>Manage and respond to incidents in your jurisdiction</p>
            <div class="dept-badge">
                <i class="fas fa-building"></i> <?php echo htmlspecialchars($department); ?> Department
            </div>
        </div>

        <?php if (isset($statusMessage)) echo $statusMessage; ?>

        <?php while ($incident = mysqli_fetch_assoc($incident_result)) { 
            $incidentID = $incident['incidentID'];
            $statusClass = $incident['status'] === 'verified' ? 'status-verified' : 'status-unverified';
            
            // Get location details
            $locID = $incident['locationID'];
            $loc_query = "SELECT * FROM Location WHERE locationID = '$locID'";
            $loc_result = mysqli_query($conn, $loc_query);
            $loc_row = mysqli_fetch_assoc($loc_result);
            $location_display = $loc_row ? $loc_row['area'] . ', ' . $loc_row['city'] . ', ' . $loc_row['province'] : 'Unknown';
        ?>
            <div class="incident-container">
                <div class="incident-header">
                    <h2 class="incident-title"><?php echo htmlspecialchars($incident['incidentType']); ?></h2>
                    <span class="incident-id">ID: <?php echo $incident['incidentID']; ?></span>
                </div>

                <div class="incident-details">
                    <div class="detail-item">
                        <div class="detail-label">Description</div>
                        <div class="detail-value"><?php echo htmlspecialchars($incident['description']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Location</div>
                        <div class="detail-value"><?php echo htmlspecialchars($location_display); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Date & Time</div>
                        <div class="detail-value">
                            <?php echo $incident['date']; ?> at <?php echo $incident['time']; ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="status-badge <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($incident['status']); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="action-buttons">
                    <form method="GET" action="viewreport.php">
                        <input type="hidden" name="incidentID" value="<?php echo $incident['incidentID']; ?>">
                        <button type="submit" class="btn btn-sm">
                            <i class="fas fa-file-alt"></i> View Reports
                        </button>
                    </form>

                    <!-- Status update form -->
                    <form method="POST" action="response.php" class="radio-group">
                        <input type="hidden" name="incidentID" value="<?php echo $incident['incidentID']; ?>">
                        <div class="radio-option">
                            <input type="radio" id="verified_<?php echo $incidentID; ?>" name="statusUpdate" value="verified" <?php if ($incident['status'] === 'verified') echo 'checked'; ?>>
                            <label for="verified_<?php echo $incidentID; ?>">Verified</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="unverified_<?php echo $incidentID; ?>" name="statusUpdate" value="unverified" <?php if ($incident['status'] === 'unverified') echo 'checked'; ?>>
                            <label for="unverified_<?php echo $incidentID; ?>">Unverified</label>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline">
                            <i class="fas fa-sync-alt"></i> Update Status
                        </button>
                    </form>
                </div>

                <?php
                // Fetch the authority's response for this incident
                $resp_query = "SELECT responseID, status FROM Response WHERE incidentID = '$incidentID' ";
                $resp_result = mysqli_query($conn, $resp_query);
                $resp_data = mysqli_fetch_assoc($resp_result);

                if ($resp_data) {
                    $solutionStatus = $resp_data['status'];
                    $solutionStatusClass = $solutionStatus === 'completed' ? 'status-completed' : 'status-inprogress';
                ?>
                    <!-- Solution Status Update Form -->
                    <form method="POST" action="response.php" class="radio-group">
                        <input type="hidden" name="incidentID" value="<?php echo $incidentID; ?>">
                        <input type="hidden" name="responseID" value="<?php echo $resp_data['responseID']; ?>">
                        <div class="radio-option">
                            <input type="radio" id="inprogress_<?php echo $incidentID; ?>" name="solutionStatus" value="in progress" <?php if ($solutionStatus === 'in progress') echo 'checked'; ?>>
                            <label for="inprogress_<?php echo $incidentID; ?>">In Progress</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="completed_<?php echo $incidentID; ?>" name="solutionStatus" value="completed" <?php if ($solutionStatus === 'completed') echo 'checked'; ?>>
                            <label for="completed_<?php echo $incidentID; ?>">Completed</label>
                        </div>
                        <button type="submit" name="updateSolutionStatus" class="btn btn-sm btn-outline">
                            <i class="fas fa-tasks"></i> Update Solution
                        </button>
                        
                    </form>
                <?php } ?>

                <?php
                // Check if authority already responded
                $check_response = "SELECT * FROM Response WHERE incidentID = '$incidentID' AND authorityID = '$authorityID'";
                $already_responded = mysqli_query($conn, $check_response);
                if (mysqli_num_rows($already_responded) == 0) {
                ?>
                    <form method="POST" action="response.php" class="form-group">
                        <input type="hidden" name="incidentID" value="<?php echo $incident['incidentID']; ?>">
                        <label for="response_<?php echo $incidentID; ?>">Your Response:</label>
                        <textarea id="response_<?php echo $incidentID; ?>" name="responseText" required></textarea>
                        <button type="submit" class="btn">
                            <i class="fas fa-paper-plane"></i> Submit Response
                        </button>
                    </form>
                <?php
                } else {
                    echo "<div class='alert warning'><i class='fas fa-info-circle'></i> You have already responded to this incident.</div>";
                ?>
                    <form method="POST" action="response.php" onsubmit="return confirm('Are you sure you want to delete your response?');">
                        <input type="hidden" name="incidentID" value="<?php echo $incident['incidentID']; ?>">
                        <button type="submit" name="deleteResponse" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete My Response
                        </button>
                    </form>
                <?php
                    // Handle response deletion
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteResponse'], $_POST['incidentID'])) {
                        $incidentID = $_POST['incidentID'];
                        
                        // Ensure this authority owns the response they're trying to delete
                        $check_ownership = "SELECT * FROM Response WHERE incidentID = '$incidentID' AND authorityID = '$authorityID'";
                        $ownership_result = mysqli_query($conn, $check_ownership);
                        
                        if (mysqli_num_rows($ownership_result) > 0) {
                            $delete_query = "DELETE FROM Response WHERE incidentID = '$incidentID' AND authorityID = '$authorityID'";
                            if (mysqli_query($conn, $delete_query)) {
                               echo "<div class='alert success'><i class='fas fa-check-circle'></i> Your response has been deleted successfully.</div>";
                               echo "<script>setTimeout(() => location.reload(), 3500);</script>";
                            } else {
                                echo "<div class='alert error'><i class='fas fa-exclamation-circle'></i> Error deleting your response. Please try again.</div>";
                            }
                        } else {
                            echo "<div class='alert error'><i class='fas fa-exclamation-circle'></i> You are not authorized to delete this response.</div>";
                        }
                    }  
                }
                ?>

                <div class="responses-section">
                    <h3 class="responses-title"><i class="fas fa-comments"></i> Previous Responses</h3>
                    
                    <?php  //previous response
                    $res_query = "SELECT R.responseText, R.responseTime, A.department, R.status 
                                FROM Response R 
                                JOIN Authority A ON R.authorityID = A.authorityID 
                                WHERE R.incidentID = '$incidentID'";
                    $res_result = mysqli_query($conn, $res_query);
                    
                    if (mysqli_num_rows($res_result) > 0) {
                        while ($r = mysqli_fetch_assoc($res_result)) {
                            $responseStatusClass = $r['status'] === 'completed' ? 'status-completed' : 'status-inprogress';
                    ?>
                            <div class="response-item">
                                <div class="response-header">
                                    <span class="response-dept">
                                        <i class="fas fa-building"></i> <?php echo htmlspecialchars($r['department']); ?> Dept
                                    </span>
                                    <span class="status-badge <?php echo $responseStatusClass; ?>">
                                        <?php echo htmlspecialchars($r['status']); ?>
                                    </span>
                                </div>
                                <div class="response-text">
                                    <?php echo htmlspecialchars($r['responseText']); ?>
                                </div>
                                <div class="response-time">
                                    <i class="far fa-clock"></i> <?php echo $r['responseTime']; ?>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "<div class='no-responses'>No responses yet for this incident.</div>";
                    }
                    ?>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>