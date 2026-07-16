<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include 'db.php';

if (!isset($_SESSION['authorityID'])) {
    header("Location: login.php");
    exit();
}

$authorityID = $_SESSION['authorityID'];
$dept_result = mysqli_query($conn, "SELECT department FROM Authority WHERE authorityID = '$authorityID'");
$department = mysqli_fetch_assoc($dept_result)['department'];

$query = "SELECT * FROM Incident 
          WHERE relevantDept = '$department' 
          AND incidentID NOT IN (
              SELECT incidentID FROM Response WHERE authorityID = '$authorityID'
          )";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unresponded Incidents</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Same CSS as in responded.php */
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
        
        .btn-sm {
            padding: 8px 15px;
            font-size: 0.8rem;
        }
        
        @media (max-width: 768px) {
            .incident-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Unresponded Incidents</h1>
            <p>Respond to new incidents in your department</p>
            <div class="dept-badge">
                <i class="fas fa-building"></i> <?php echo htmlspecialchars($department); ?> Department
            </div>
        </div>

        <?php while ($incident = mysqli_fetch_assoc($result)) { 
            $statusClass = $incident['status'] === 'verified' ? 'status-verified' : 'status-unverified';
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
                        <div class="detail-value">
                            <?php
                                $loc = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM Location WHERE locationID = '{$incident['locationID']}'"));
                                echo $loc ? htmlspecialchars("{$loc['area']}, {$loc['city']}, {$loc['province']}") : "Unknown";
                            ?>
                        </div>
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

                <form method="POST" action="response.php" class="form-group">
                    <input type="hidden" name="incidentID" value="<?php echo $incident['incidentID']; ?>">
                    <label for="response_<?php echo $incident['incidentID']; ?>">Your Response:</label>
                    <textarea id="response_<?php echo $incident['incidentID']; ?>" name="responseText" required></textarea>
                    <button type="submit" class="btn">
                        <i class="fas fa-paper-plane"></i> Submit Response
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>