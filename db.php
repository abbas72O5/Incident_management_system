<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$host = "localhost"; // Change if needed
$user = "root"; // Default XAMPP username
$pass = ""; // Default XAMPP has no password
$dbname = "incident_db";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Create connection
try{
$conn = new mysqli($host, $user, $pass, $dbname);
}
catch(mysqli_sql_exception){
    echo "Could not connect.<br>";
}
if($conn)
  // echo " You are connected.<br>";
// Check connection

?>