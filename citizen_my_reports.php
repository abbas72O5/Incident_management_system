<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Fetch reports submitted by this user
$query = "
   SELECT r.reportTime, r.reportText, i.incidentType,i.description,i.status, i.date, i.time,
   l.province,l.city,l.area
   FROM incident i JOIN reports r ON i.incidentID=r.incidentID JOIN location l
   ON l.locationID=i.locationID 
   WHERE r.userID='$userID';
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reports</title>
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
        text-align: center;
        margin-bottom: 40px;
    }

    .header h2 {
        font-family: 'Playfair Display', serif;
        color: white;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .reports-container {
        background: rgba(14, 1, 1, 0.42);
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        color: white;
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    th {
        background: rgba(255, 255, 255, 0.2);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    tr:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .status {
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        text-transform: capitalize;
    }

    .verified {
        background-color: rgba(40, 167, 69, 0.2);
        color: #28A745;
        border: 1px solid #28A745;
    }

    .unverified {
        background-color: rgba(220, 53, 69, 0.2);
        color: #DC3545;
        border: 1px solid #DC3545;
    }

    .no-reports {
        text-align: center;
        padding: 30px;
        color: rgba(255, 255, 255, 0.7);
        font-style: italic;
    }

    @media (max-width: 768px) {
        table {
            display: block;
            overflow-x: auto;
        }

        .header h2 {
            font-size: 2rem;
        }
    }
</style>

</head>
<body>
    <div class="container">
        <div class="header">
            <h2>My Submitted Reports</h2>
            <p>View all reports you've submitted</p>
        </div>

        <div class="reports-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Incident Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Area</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['incidentType']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td><?php echo htmlspecialchars($row['area']); ?></td>
                        <td class="status <?php echo strtolower($row['status']); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="no-reports">
                    <p>No reports submitted yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
