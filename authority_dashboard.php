<?php
include'db.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'authority') {
    header("Location: ../login.php");
    exit();
}

$authorityID = $_SESSION['authorityID'];

// Count responded incidents by this authority
$responses_query = "SELECT COUNT(*) AS respondedCount FROM Response WHERE authorityID = '$authorityID'";
$responses_result = mysqli_query($conn, $responses_query);
$responses_data = mysqli_fetch_assoc($responses_result);
$respondedCount = $responses_data['respondedCount'];

// Count verified incidents by this authority
$verified_query = "SELECT COUNT(*) AS verifiedCount FROM Response r
Join incident i ON r.incidentID=i.incidentID  WHERE r.authorityID = '$authorityID' AND i.status = 'verified'";
$verified_result = mysqli_query($conn, $verified_query);
$verified_data = mysqli_fetch_assoc($verified_result);
$verifiedCount = $verified_data['verifiedCount'];

// Count completed incidents by this authority
$completed_query = "SELECT COUNT(*) AS completedCount FROM Response WHERE authorityID = '$authorityID' AND status = 'Completed'";
$completed_result = mysqli_query($conn, $completed_query);
$completed_data = mysqli_fetch_assoc($completed_result);
$completedCount = $completed_data['completedCount'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authority Dashboard</title>
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
         background: url('https://images.unsplash.com/photo-1566438480900-0609be27a4be?ixlib=rb-1.2.1&auto=format&fit=crop&w=1500&q=80') center/cover no-repeat fixed;
        min-height: 100vh;
    }

    .card {
        background: #161b22;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        margin-bottom: 25px;
        transition: transform 0.3s;
        color: var(--light);
    }

    .card:hover {
        transform: translateY(-3px);
    }

.dashboard-title {
    font-size: 28px;
    margin-bottom: 25px;
    color: var(--light);
    font-weight: 700;
    text-align: center;       /* Center the text inside the element */
    display: block;
    margin-left: auto;        /* Center the block itself horizontally */
    margin-right: auto;
    
    background-color: rgba(0, 123, 255, 0.2); /* Optional overlay color */
    padding: 6px 12px;
    border-radius: 6px;
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
    border-radius: 6px;
    z-index: -1;
}



    .stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-box {
        background-color: var(--primary);
        color: white;
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        font-size: 18px;
        box-shadow: 0 5px 15px rgba(31, 111, 235, 0.3);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(31, 111, 235, 0.4);
    }

    .stat-box i {
        font-size: 28px;
        margin-bottom: 15px;
        color: rgba(255, 255, 255, 0.9);
    }

    .stat-box div {
        font-weight: 600;
        font-size: 20px;
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
    }
</style>

</head>
<body>

    <div class="sidebar">
        <h2>Authority Panel</h2>
        <a href="incidentreport.php"><i class="fas fa-exclamation-circle"></i> Report New Incident</a>
        <a href="response.php"><i class="fas fa-tasks"></i> View all Incidents</a>
        <a href="unverified.php"><i class="fas fa-question-circle"></i> Unverified incidents</a>
        <a href="responded.php"><i class="fas fa-check-circle"></i> Responded incidents</a>
        <a href="unresponded.php"><i class="fas fa-clock"></i> Pending incidents</a>
        <a class="logout-btn" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="main-content">
        <h1 class="dashboard-title">Welcome to the Dashboard</h1>
        
        <div class="stats">
            <div class="stat-box">
                <i class="fas fa-reply"></i>
                <div>Responded: <?php echo $respondedCount; ?></div>
            </div>
            <div class="stat-box" style="background-color:var(--success);">
                <i class="fas fa-check-circle"></i>
                <div>Verified: <?php echo $verifiedCount; ?></div>
            </div>
            <div class="stat-box" style="background-color:var(--purple);">
                <i class="fas fa-clipboard-check"></i>
                <div>Completed: <?php echo $completedCount; ?></div>
            </div>
        </div>

        <div class="card">
            <h3>Quick Actions</h3>
            <p>Use the sidebar to report new incidents, respond to pending cases, or manage your profile.</p>
        </div>
    </div>

</body>
</html>