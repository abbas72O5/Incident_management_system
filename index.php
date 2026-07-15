<?php
include 'db.php';

// Redirect logged-in users to their respective dashboards
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'citizen') {
        header("Location: citizen_dashboard.php");
        exit();
    } elseif ($_SESSION['role'] == 'authority') {
        header("Location: authority_dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Recording Database</title>
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
            color: var(--dark);
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.0);
            z-index: -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        /* Header Section with Background */
        .header-section {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
            text-align: center;
        }

        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
            color: var(--secondary);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 25px;
        }

        /* Section Title with Background */
        .section-title {
           background: rgba(255, 255, 255, 0.8);
            padding: 15px 25px;
            border-radius: 8px;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 30px 0;
        }

        nav a {
            display: inline-flex;
            align-items: center;
            padding: 12px 25px;
            background: var(--gradient-bg);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        nav a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.4);
        }

        nav a i {
            margin-right: 8px;
        }

        .incidents-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 25px;
        }

        .incident {
            background: rgba(255, 255, 255, 0.8);
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .incident:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .incident h4 {
            color: var(--secondary);
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 8px;
        }

        .incident p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .incident p b {
            color: var(--dark);
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
            padding: 40px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px 15px;
            }
            
            nav {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }
            
            .incidents-container {
                grid-template-columns: 1fr;
            }
            
            .header-section {
                padding: 20px;
            }
            
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-section">
        <h1>Incident Management System</h1>
        <p>Community-powered incident reporting system</p>
    </div>

    <!-- Navigation Links -->
    <nav>
        <a href="reg.php"><i class="fas fa-user-plus"></i> Register</a>
        <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
    </nav>

    <div class="section-title">
        <h2>Recent Incidents</h2>
    </div>

    <div class="incidents-container">
    <?php
    // Fetch recent incidents with location details
    $query = "SELECT i.*, l.city, l.area FROM Incident i 
              JOIN Location l ON i.locationID = l.locationID 
              ORDER BY i.date DESC, i.time DESC
              LIMIT 6"; // Limit to 6 most recent incidents

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($incident = $result->fetch_assoc()) {
            echo "<div class='incident'>";
            echo "<h4>" . htmlspecialchars($incident['incidentType']) . "</h4>";
            echo "<p><b>Description:</b> " . htmlspecialchars($incident['description']) . "</p>";
            echo "<p><b>Location:</b> " . htmlspecialchars($incident['area']) . ", " . htmlspecialchars($incident['city']) . "</p>";
            echo "<p><b>Date & Time:</b> " . htmlspecialchars($incident['date']) . " " . htmlspecialchars($incident['time']) . "</p>";
            echo "<p class='" . ($incident['status'] == 'verified' ? "verified" : "unverified") . "'><b>Status:</b> " . ucfirst($incident['status']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='no-incidents'><p>No incidents reported yet. Be the first to report!</p></div>";
    }
    ?>
    </div>
</div>

</body>
</html>