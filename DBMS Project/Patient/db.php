<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";

$username = "root";

$password = "";

$dbname = "healthcaredb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

}
else{
    echo "<script>console.log('Database Connected successfully');</script>";
}

?>