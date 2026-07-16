<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$host = "127.0.0.1"; // Change if needed
$user = "root"; // Default XAMPP username
$pass = ""; // Default XAMPP has no password
$dbname = "incident_db";
$port = 3307;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create connection
try{
$conn = new mysqli($host, $user, $pass, $dbname, $port);
}
catch(mysqli_sql_exception){
    echo "Could not connect.<br>";
}
if($conn)
  // echo " You are connected.<br>";
// Check connection

?>