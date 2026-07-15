<?php
include 'db.php';

if (!isset($_GET['incidentID'])) {
    echo "<div class='alert error'>No incident selected.</div>";
    exit();
}

$incidentID = $_GET['incidentID'];

// Fetch response details for the incident
$response_query = "SELECT U.name AS authorityName, A.department, A.contactNumber, R.responseText, R.responseTime 
                   FROM Response R
                   JOIN Authority A ON R.authorityID = A.authorityID
                   JOIN users U on U.userID=A.userID
                   WHERE R.incidentID = '$incidentID'";
$response_result = mysqli_query($conn, $response_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Response</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
     body {
    font-family: 'Montserrat', sans-serif;
    background-image: url('https://images.unsplash.com/photo-1566438480900-0609be27a4be?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80');
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
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
    background: rgba(255,255,255,0); /* Less dark for visibility */
    z-index: -1;
}

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .back-link {
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin-bottom: 20px;
        }
        .back-link i {
            margin-right: 8px;
        }
     .response-box {
    background: rgba(0, 0, 0, 0.7); /* Darker translucent background */
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 20px;
    color: white; /* Optional: ensure text is readable */
}


        .authority-info {
            margin-bottom: 15px;
        }
        .authority-info strong {
            display: inline-block;
            width: 120px;
        }
        .response-text {
            margin-top: 10px;
            line-height: 1.6;
            background: rgba(0,0,0,0.2);
            padding: 15px;
            border-radius: 10px;
        }
        .no-response {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
<div class="container">
    <a href="incidentview.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Incidents</a>
    <div class="header">
        <h2>Response for Incident: <?php echo htmlspecialchars($incidentID); ?></h2>
    </div>

    <?php if (mysqli_num_rows($response_result) > 0): ?>
        <?php while ($response = mysqli_fetch_assoc($response_result)): ?>
            <div class="response-box">
                <div class="authority-info">
                    <p><strong>Authority:</strong> <?php echo htmlspecialchars($response['authorityName']); ?></p>
                    <p><strong>Department:</strong> <?php echo htmlspecialchars($response['department']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($response['contactNumber']); ?></p>
                    <p><strong>Response Time:</strong> <?php echo htmlspecialchars($response['responseTime']); ?></p>
                </div>
                <div class="response-text">
                    <strong>Description:</strong><br>
                    <?php echo nl2br(htmlspecialchars($response['responseText'])); ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-response">
            <p>No response has been submitted for this incident yet.</p>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
