<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Citizen Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
    :root {
        --primary: #1f6feb;
        --secondary: #0d1117;
        --accent: #f778ba;
        --light: #c9d1d9;
        --dark: #010409;
        --success: #2ea043;
        --danger: #f85149;
        --purple: #8250df;
    }

    body {
        margin: 0;
        font-family: 'Montserrat', sans-serif;
        background: url('https://images.unsplash.com/photo-1566438480900-0609be27a4be?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80') center/cover no-repeat fixed;
        color: var(--light);
        min-height: 100vh;
    }

    .sidebar {
        height: 100vh;
        width: 220px;
        position: fixed;
        background-color: #161b22;
        color: white;
        padding-top: 30px;
        box-shadow: 2px 0 15px rgba(0, 0, 0, 0.5);
    }

    .sidebar h2 {
        text-align: center;
        margin-bottom: 40px;
        font-size: 20px;
        color: var(--light);
        font-weight: 600;
    }

    .sidebar a {
        display: block;
        padding: 15px 20px;
        color: var(--light);
        text-decoration: none;
        font-size: 15px;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .sidebar a:hover {
        background-color: #21262d;
        border-left: 3px solid var(--primary);
        padding-left: 25px;
    }

    .sidebar a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .main-content {
        margin-left: 220px;
        padding: 30px;
        background: rgba(1, 1, 1, 0);
        min-height: 100vh;
    }

.dashboard-title {
    font-size: 28px;
    margin-bottom: 25px;
    color: var(--light);
    font-weight: 700;
    text-align: center;
    display: inline-block;
    background-color: rgba(0, 123, 255, 0.2); /* Light blue background */
    padding: 4px 8px;
    border-radius: 5px;
    position: relative;
    z-index: 1;
}

.dashboard-title::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    border-radius: 5px;
    z-index: -1;
}


    .action-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .action-btn {
        background-color: var(--primary);
        color: white;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        font-size: 18px;
        border: none;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(31, 111, 235, 0.3);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(31, 111, 235, 0.4);
        background-color: #1a65d1;
    }

    .action-btn i {
        font-size: 28px;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.9);
    }

    .logout-btn {
        margin-top: 40px;
        background: var(--danger);
        padding: 12px 18px;
        display: inline-block;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        width: calc(100% - 40px);
        text-align: center;
        margin-left: 20px;
        font-weight: 500;
    }

    .logout-btn:hover {
        background: #c53030;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(248, 81, 73, 0.3);
    }

    .logout-btn i {
        margin-right: 8px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }

        .main-content {
            margin-left: 0;
        }

        .action-buttons {
            grid-template-columns: 1fr;
        }
    }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Citizen Panel</h2>
        <a href="incidentreport.php"><i class="fas fa-exclamation-circle"></i> Report Incident</a>
        <a href="incidentview.php"><i class="fas fa-list"></i> View All Incidents</a>
        <a href="citizen_my_reports.php"><i class="fas fa-file-alt"></i> My Reports</a>
        <a href="citizen_search_area.php"><i class="fas fa-search-location"></i> Search Area</a>
        <a class="logout-btn" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1 class="dashboard-title">Welcome to Citizen Dashboard</h1>
        
        <div class="action-buttons">
            <button class="action-btn" onclick="window.location.href='incidentview.php'">
                <i class="fas fa-list"></i>
                <div>View All Incidents</div>
            </button>
            
            <button class="action-btn" onclick="window.location.href='citizen_my_reports.php'">
                <i class="fas fa-file-alt"></i>
                <div>My Reports</div>
            </button>
            
            <button class="action-btn" onclick="window.location.href='citizen_search_area.php'">
                <i class="fas fa-search-location"></i>
                <div>Search Area</div>
            </button>
            
            <button class="action-btn" onclick="window.location.href='incidentreport.php'">
                <i class="fas fa-exclamation-circle"></i>
                <div>Report Incident</div>
            </button>
        </div>
    </div>

</body>
</html>