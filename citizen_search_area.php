<?php
include 'db.php';
session_start();

function getRiskLevel($count) {
    if ($count <= 10) return 'Low';
    if ($count <= 20) return 'Medium';
    return 'High';
}

$searchResults = [];
$areaTerm = '';
$cityTerm = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['area'])) {
        $areaTerm = mysqli_real_escape_string($conn, $_POST['area']);
    }
    if (isset($_POST['city'])) {
        $cityTerm = mysqli_real_escape_string($conn, $_POST['city']);
    }
}

// Base query with optional search filter
$areaQuery = "
    SELECT l.locationID, l.area, l.city, MAX(i.time) as latest_time
    FROM Location l
    JOIN Incident i ON l.locationID = i.locationID
    WHERE i.status = 'verified'
";

if (!empty($areaTerm) && !empty($cityTerm)) {
    $areaQuery .= " AND l.area LIKE '%$areaTerm%' AND l.city LIKE '%$cityTerm%' ";
} elseif (!empty($cityTerm)) {
    $areaQuery .= " AND l.city LIKE '%$cityTerm%' ";
}

$areaQuery .= "
    GROUP BY l.locationID
    ORDER BY latest_time DESC
    LIMIT 10
";

$areaResult = mysqli_query($conn, $areaQuery);

while ($location = mysqli_fetch_assoc($areaResult)) {
    $locationID = $location['locationID'];
    $areaName = $location['area'];
    $city = $location['city'];

    // Get count of verified incidents grouped by type for this area
    $incidentQuery = "
     SELECT i.incidentType, COUNT(*) as total
     FROM Incident i
     WHERE 
        i.locationID = '$locationID' 
        AND i.status = 'verified' 
        AND i.time >= NOW() - INTERVAL 30 DAY
    GROUP BY i.incidentType
    ";
    $incidentResult = mysqli_query($conn, $incidentQuery);
    $riskData = [];

    while ($row = mysqli_fetch_assoc($incidentResult)) {
        $riskData[] = [
            'type' => $row['incidentType'],
            'count' => $row['total'],
            'level' => getRiskLevel($row['total'])
        ];
    }

    $searchResults[] = [
        'area' => $areaName,
        'risks' => $riskData,
        'city' => $city
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Area Risk</title>
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

        .search-form {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
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

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 8px;
             background: rgba(255, 255, 255, 0.4);
            color: white;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s;
             box-shadow: 0 0 0 4px rgba(108, 99, 255, 0.4);
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.3);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 15px 30px;
            background: var(--gradient-bg);
            color: white;
            border: none;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.4);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.6);
        }

        .area-block {
             background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .area-title {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .incident-list {
            list-style: none;
            margin-top: 15px;
        }

        .incident-item {
             background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            color: #ffffff;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .low {
    color: #ffee58; /* brighter yellow */
    font-weight: bold;
}

.medium {
    color: #ff9800; /* orange */
    font-weight: bold;
}

.high {
    color: #f44336; /* red */
    font-weight: bold;
}


        .no-results {
            text-align: center;
            padding: 30px;
            color: rgba(255, 255, 255, 0.7);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .header h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Search Area Risk Levels</h2>
            <p>Check safety levels in different areas</p>
        </div>

        <form method="POST" class="search-form">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" placeholder="Enter city name..." value="<?php echo htmlspecialchars($cityTerm ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="area">Area</label>
                <input type="text" id="area" name="area" placeholder="Enter area name..." value="<?php echo htmlspecialchars($areaTerm); ?>">
            </div>
            <button type="submit" class="btn">
                <i class="fas fa-search"></i> Search Area
            </button>
        </form>

        <?php if (!empty($searchResults)): ?>
            <?php foreach ($searchResults as $areaInfo): ?>
                <div class="area-block">
                    <h3 class="area-title"><?php echo htmlspecialchars($areaInfo['area']); ?> (<?php echo htmlspecialchars($areaInfo['city']); ?>)</h3>
                    <?php if (!empty($areaInfo['risks'])): ?>
                        <ul class="incident-list">
                            <?php foreach ($areaInfo['risks'] as $risk): ?>
                                <li class="incident-item">
                                    <strong><?php echo htmlspecialchars($risk['type']); ?>:</strong>
                                    <span class="<?php echo strtolower($risk['level']); ?>">
                                        <?php echo $risk['level']; ?> (<?php echo $risk['count']; ?> reports)
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No verified incidents reported in this area.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <p>No areas matched your search or no incidents yet.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
