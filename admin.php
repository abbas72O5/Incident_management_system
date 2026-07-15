<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['authorityID'], $_POST['action'])) {
    $authorityID = mysqli_real_escape_string($conn, $_POST['authorityID']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $infoQuery = "
            SELECT u.name, a.department
            FROM Authority a
            JOIN users u ON a.userID = u.userID
            WHERE a.authorityID = '$authorityID'
        ";
        $infoResult = mysqli_query($conn, $infoQuery);
        if ($infoResult && mysqli_num_rows($infoResult) > 0) {
            $info = mysqli_fetch_assoc($infoResult);
            $firstName = strtolower(preg_replace('/\s+/', '', $info['name']));
            $department = strtolower(preg_replace('/\s+/', '', $info['department']));
            $generatedEmail = "{$firstName}@{$department}.pk";
            $updateQuery = "
                UPDATE Authority 
                SET status = 'approved', email = '$generatedEmail' 
                WHERE authorityID = '$authorityID'
            ";
            mysqli_query($conn, $updateQuery);
        }
    } else {
        $updateQuery = "UPDATE Authority SET status = 'rejected' WHERE authorityID = '$authorityID'";
        mysqli_query($conn, $updateQuery);
    }
}

$query = "
    SELECT a.*, u.first_name, u.last_name 
    FROM Authority a
    JOIN users u ON a.userID = u.userID
    ORDER BY a.status DESC, a.authorityID DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Authorities</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
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
        padding: 40px 30px;
    }

  h2 {
    font-size: 28px;
    color: var(--light);
    font-weight: 900;
    text-align: center;
    background-color: rgba(255, 255, 255, 0); /* remains transparent */
    padding: 10px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
    display: inline-block;
    position: relative; /* required for positioning the pseudo-element */
    z-index: 1;
}

h2::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    border-radius: 8px;
    z-index: -1;
}

    table {
        width: 100%;
        border-collapse: collapse;
        background: rgba(22, 27, 34, 0.9);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    th, td {
        padding: 14px 16px;
        border-bottom: 1px solid #30363d;
        text-align: left;
        font-size: 14px;
    }

    th {
        background: var(--primary);
        color: white;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tr:nth-child(even) {
        background: rgba(255,255,255,0.02);
    }

    .btn {
        padding: 8px 14px;
        border: none;
        color: white;
        cursor: pointer;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
    }

    .approve {
        background-color: var(--success);
    }

    .reject {
        background-color: var(--danger);
    }

    .view {
        background-color: var(--purple);
        text-decoration: none;
        display: inline-block;
    }

    .btn:hover {
        opacity: 0.9;
        transform: scale(1.05);
    }

    .status {
        font-weight: bold;
        text-transform: capitalize;
    }

    .approved { color: var(--success); }
    .rejected { color: var(--danger); }
    .pending { color: orange; }

    form {
        display: inline;
    }

    @media (max-width: 768px) {
        body {
            padding: 20px 10px;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        tr {
            margin-bottom: 15px;
            background: rgba(22, 27, 34, 0.9);
            padding: 15px;
            border-radius: 10px;
        }

        td {
            padding: 10px 0;
            border: none;
            display: flex;
            justify-content: space-between;
        }

        td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--light);
        }

        th {
            display: none;
        }
    }
    </style>
</head>
<body>
<div style="position: absolute; top: 20px; left: 20px;">
        <a href="login.php" class="btn btn-sm" style="background: var(--secondary);">
            <i class="fas fa-tachometer-alt"></i> logout
        </a></div>
    <h2>Admin Dashboard - Registered Authorities</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Department</th>
                <th>City</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td data-label="ID"><?php echo $row['authorityID']; ?></td>
                    <td data-label="Name"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td data-label="Department"><?php echo htmlspecialchars($row['department']); ?></td>
                    <td data-label="City"><?php echo htmlspecialchars($row['city']); ?></td>
                    <td data-label="Status" class="status <?php echo $row['status']; ?>"><?php echo $row['status']; ?></td>
                    <td data-label="Actions">
                        <?php if ($row['status'] == 'pending'): ?>
                            <form method="POST">
                                <input type="hidden" name="authorityID" value="<?php echo $row['authorityID']; ?>">
                                <button type="submit" name="action" value="approve" class="btn approve">Approve</button>
                            </form>
                            <form method="POST">
                                <input type="hidden" name="authorityID" value="<?php echo $row['authorityID']; ?>">
                                <button type="submit" name="action" value="reject" class="btn reject">Reject</button>
                            </form>
                        <?php endif; ?>
                        <a class="btn view" href="view_authority.php?aid=<?php echo $row['authorityID']; ?>">View</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
