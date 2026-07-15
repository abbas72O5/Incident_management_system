<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'incident_db';
$port = 3307;

$sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'incident_db.sql');
if ($sql === false) {
    fwrite(STDERR, "Could not read incident_db.sql\n");
    exit(1);
}

try {
    $conn = new mysqli($host, $user, $pass, '', $port);
    $conn->set_charset('utf8mb4');
    $conn->multi_query($sql);

    do {
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->more_results() && $conn->next_result());

    echo "Database initialized successfully.\n";
} catch (Throwable $error) {
    fwrite(STDERR, "Database initialization failed: " . $error->getMessage() . "\n");
    exit(1);
}
