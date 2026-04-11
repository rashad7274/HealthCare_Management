<?php
// Start the session to access the Officer ID of the logged-in user
session_start();
$_SESSION['Doctor_ID'] = '2001';//for test

// Database configuration based on your SQL dump
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "healthcaredb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if (isset($_POST['submit_claim'])) {
    
    // 1. Get Officer_ID from session (set during login)
    // If you haven't set this in your login script yet, you can use: $_SESSION['iOfficerID'] = 6001; for testing.
    $Officer_ID = isset($_SESSION['iOfficerID']) ? $_SESSION['iOfficerID'] : null;

    if (!$Officer_ID) {
        die("Error: You must be logged in as an Insurance Officer to submit a claim.");
    }

    // 2. Retrieve and sanitize form data
    $Invoice_ID  = $_POST['Invoice_ID'];
    $Patient_ID  = $_POST['Patient_ID'];
    $Amount      = $_POST['Amount'];
    $Status      = $_POST['Status'];
    $date        = $_POST['date'];
    $Description = $_POST['Description'];

    // 3. Prepare the SQL Statement to match your table structure
    // Note: Claim_ID is omitted because it is AUTO_INCREMENT
    $stmt = $conn->prepare("INSERT INTO insurance_claim (Invoice_ID, Patient_ID, Amount, Officer_ID, Status, Description, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // "i" for integer, "d" for double/decimal, "s" for string
    $stmt->bind_param("iidisss", $Invoice_ID, $Patient_ID, $Amount, $Officer_ID, $Status, $Description, $date);

    // 4. Execute and provide feedback
    if ($stmt->execute()) {
        echo "<script>alert('Insurance claim submitted successfully!'); window.location.href='submit_insurance.html';</script>";
    } else {
        // This will trigger if foreign key constraints are violated (e.g., Invoice_ID doesn't exist)
        echo "Error submitting claim: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>