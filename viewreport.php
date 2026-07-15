<?php
include 'db.php';

if (!isset($_GET['incidentID'])) {
    echo "<div class='alert error'>No incident selected.</div>";
    exit();
}

$incidentID = $_GET['incidentID'];

// Get total number of reports for this incident
$report_count_query = "SELECT COUNT(*) AS totalReports FROM Reports WHERE incidentID = '$incidentID'";
$report_count_result = mysqli_query($conn, $report_count_query);
$report_count_row = mysqli_fetch_assoc($report_count_result);
$totalReports = $report_count_row['totalReports'];

// Fetch all reports for this incident
$reports_query = "SELECT R.reportID, R.reportTime,  U.name ,R.reportText,  U.cnic, U.first_name, U.last_name
                  FROM Reports R 
                  JOIN Users U ON R.userID = U.userID 
                  WHERE R.incidentID = '$incidentID'";
$reports_result = mysqli_query($conn, $reports_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports</title>
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
            margin-bottom: 30px;
        }

        .header h2 {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .back-link i {
            margin-right: 8px;
        }

        .reports-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .report-count {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .report-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .report-id {
            font-weight: 600;
            color: white;
        }

        .report-time {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .reporter-info {
            margin-bottom: 15px;
        }

        .reporter-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .report-text {
            line-height: 1.6;
        }

        .no-reports {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 30px 0;
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
           
            <h2>Reports for Incident: <?php echo $incidentID; ?></h2>
            <p class="report-count"><strong>Total Reports:</strong> <?php echo $totalReports; ?></p>
        </div>

        <div class="reports-container">
            <?php if (mysqli_num_rows($reports_result) > 0): ?>
                <?php while ($report = mysqli_fetch_assoc($reports_result)): ?>
                    <div class="report-item">
                        <div class="report-header">
                            <span class="report-id">Report ID: <?php echo $report['reportID']; ?></span>
                            <span class="report-time"><?php echo $report['reportTime']; ?></span>
                        </div>
                        <div class="reporter-info">
                            <div class="reporter-name">Reported By: <?php echo htmlspecialchars($report['name']); ?></div>
                            <div>CNIC: <?php echo htmlspecialchars($report['cnic']); ?></div>
                        </div>
                        <div class="report-text">
                            <strong>Description:</strong> <?php echo htmlspecialchars($report['reportText']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-reports">
                    <p>No reports found for this incident.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
