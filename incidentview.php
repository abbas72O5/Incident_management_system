<?php
include 'db.php';
session_start();
$query = "
    SELECT 
        i.incidentID,
        i.incidentType,
        i.date,
        i.time,
        l.area,
        l.city,
        i.status,
        r1.responseText,
        COUNT(r.reportID) AS report_count
    FROM Incident i
    JOIN Location l ON i.locationID = l.locationID
    LEFT JOIN reports r ON i.incidentID = r.incidentID
    LEFT JOIN response r1 ON i.incidentID=r1.incidentID
    GROUP BY i.incidentID
    ORDER BY i.date DESC, i.time DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Verified Incidents</title>
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

        .incidents-container {
            background: rgba(14, 1, 1, 0.42);
          
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        .view-reports-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 15px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-top: 5px;
        }

        .view-reports-btn:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        .view-reports-btn i {
            margin-right: 5px;
        }

        .verified {
            color: var(--success);
            font-weight: 600;
        }

        .unverified {
            color: var(--danger);
            font-weight: 600;
        }

        .no-incidents {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 2rem;
            }
            
            th, td {
                padding: 10px 5px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Incident Reports</h2>
            <p>View all reported incidents in the system</p>
        </div>

        <div class="incidents-container">
            <?php if (mysqli_num_rows($result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Incident Type</th>
                        <th>Area</th>
                        <th>District</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Reports</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['incidentType']); ?></td>
                        <td><?php echo htmlspecialchars($row['area']); ?></td>
                        <td><?php echo htmlspecialchars($row['city']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['time']); ?></td>
                        <td>
                            <?php echo $row['report_count']; ?>
                            <form method='GET' action='viewreport.php'>
                                <input type='hidden' name='incidentID' value='<?php echo $row['incidentID']; ?>'>
                                <button type='submit' class='view-reports-btn'>
                                    <i class='fas fa-file-alt'></i> View Reports
                                </button>
                            </form><br>
                            <form method='GET' action='viewresponse.php'>
                                <input type='hidden' name='incidentID' value='<?php echo $row['incidentID']; ?>'>
                                <button type='submit' class='view-reports-btn'>
                                    <i class='fas fa-file-alt'></i> View Response
                                </button>
                            </form>
                        </td>
                        
                      
                        <td class="<?php echo strtolower($row['status']); ?>">
                            <?php echo $row['status'] == 'verified' ? '✔ Verified' : '❌ Unverified'; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php else: ?>
                <div class="no-incidents">
                    <p>No incidents found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
