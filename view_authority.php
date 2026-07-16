<?php
include 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_GET['aid'])) {
    echo "<div class='alert error'>Authority ID is missing.</div>";
    exit;
}

$authorityID = mysqli_real_escape_string($conn, $_GET['aid']);

// Fetch general authority info with user details
$query = "
    SELECT a.*, u.first_name, u.last_name, a.email as user_email
    FROM Authority a
    JOIN users u ON a.userID = u.userID
    WHERE a.authorityID = '$authorityID'
";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "<div class='alert error'>Authority not found.</div>";
    exit;
}

$authority = mysqli_fetch_assoc($result);

// Identify department
$department = strtolower($authority['department']);
$departmentData = null;

switch ($department) {
    case 'police':
        $deptQuery = "SELECT * FROM Police WHERE authorityID = '$authorityID'";
        break;
    case 'fire':
        $deptQuery = "SELECT * FROM FireBrigade WHERE authorityID = '$authorityID'";
        break;
    case 'traffic':
        $deptQuery = "SELECT * FROM Traffic WHERE authorityID = '$authorityID'";
        break;
    case 'municipal':
        $deptQuery = "SELECT * FROM Municipal WHERE authorityID = '$authorityID'";
        break;
    default:
        $deptQuery = null;
}

if ($deptQuery) {
    $deptResult = mysqli_query($conn, $deptQuery);
    if ($deptResult && mysqli_num_rows($deptResult) > 0) {
        $departmentData = mysqli_fetch_assoc($deptResult);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authority Details</title>
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
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card h2 {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .field {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
            width: 150px;
            flex-shrink: 0;
        }

        .value {
            color: white;
            flex-grow: 1;
        }

        .section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .section h3 {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .no-data {
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .alert.error {
            background: rgba(220, 53, 69, 0.2);
            border-left: 4px solid var(--danger);
            color: white;
        }

        @media (max-width: 768px) {
            .field {
                flex-direction: column;
            }
            
            .label {
                width: 100%;
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
        <div style="position: absolute; top: 20px; left: 20px;">
        <a href="admin.php" class="btn btn-sm" style="background: var(--secondary);">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    <div class="container">
        <div class="card">

            <h2>Authority Details</h2>

            <div class="field">
                <span class="label">Name:</span>
                <span class="value"><?php echo htmlspecialchars($authority['first_name'] . ' ' . $authority['last_name']); ?></span>
            </div>
            <div class="field">
                <span class="label">Email:</span>
                <span class="value"><?php echo htmlspecialchars($authority['user_email']); ?></span>
            </div>
            <div class="field">
                <span class="label">Department:</span>
                <span class="value"><?php echo htmlspecialchars($authority['department']); ?></span>
            </div>
            <div class="field">
                <span class="label">City:</span>
                <span class="value"><?php echo htmlspecialchars($authority['city']); ?></span>
            </div>
            <div class="field">
                <span class="label">Phone:</span>
                <span class="value"><?php echo htmlspecialchars($authority['contactNumber']); ?></span>
            </div>
            <div class="field">
                <span class="label">Status:</span>
                <span class="value"><?php echo htmlspecialchars($authority['status']); ?></span>
            </div>

            <?php if ($departmentData): ?>
                <div class="section">
                    <h3><?php echo ucfirst($department); ?> Department Info</h3>
                    <?php foreach ($departmentData as $key => $value): ?>
                        <?php if ($key !== 'authorityID'): ?>
                            <div class="field">
                                <span class="label"><?php echo htmlspecialchars(ucwords(str_replace("_", " ", $key))); ?>:</span>
                                <span class="value"><?php echo htmlspecialchars($value); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="section">
                    <p class="no-data">No additional department data available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
