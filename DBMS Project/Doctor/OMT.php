<?php
session_start();
//$_SESSION['Doctor_ID'] = '2001';//for test

include "../db.php"; // Make sure this path correctly points to your database connection file

// 1. Check if the Doctor is logged in
if (!isset($_SESSION['Doctor_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../login.php'>log in</a> to order medical tests.</h2>";
    exit;
}

$doctor_id = $_SESSION['Doctor_ID'];
$message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $patient_id = $conn->real_escape_string($_POST['Patient_ID']);
    $test_name = $conn->real_escape_string($_POST['test_name']);
    $priority = $conn->real_escape_string($_POST['priority']);
    $additional_notes = $conn->real_escape_string($_POST['additional_notes']);
    
    $current_date = date('Y-m-d');
    $notes_val = !empty($additional_notes) ? "'$additional_notes'" : "NULL";
// The updated query
    $sql = "INSERT INTO medicaltest (Patient_ID, Doctor_ID, Test_Type, Priority_Level, Result_Status) 
            VALUES ('$patient_id', '$doctor_id', '$test_name', '$priority', 'Pending')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert success'>Test order submitted successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Order Medical Tests</title>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f2f6f8;
    margin: 0;
    padding: 0;
    text-align: center;
}

/* Header */
header {
    background-color: #d6ecff;
    padding: 20px;
}

header h1 { margin: 0; }
header h3 { margin: 5px 0; color: #555; }

/* Form Container */
.form-container {
    width: 420px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    text-align: left;
}

label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
}

input, select, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box; /* Prevents overflow */
}

/* Button */
button {
    width: 100%;
    padding: 10px;
    border: none;
    background-color: #4da6ff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    font-size: 16px;
    margin-top: 10px;
}

button:hover {
    background-color: #3399ff;
}

/* Alerts */
.alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; text-align: center; }
.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

/* Footer */
footer {
    background: #e8eef2;
    padding: 15px;
    margin-top: 40px;
    font-size: 14px;
    color: #444;
}
</style>
</head>

<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Order Medical Tests</h3>
</header>

<div class="form-container">

    <?php echo $message; ?>

    <form action="" method="POST">

        <label>Patient ID</label>
        <input type="number" name="Patient_ID" placeholder="Enter patient ID" required>

        <label>Select Test</label>
        <select name="test_name" required>
            <option value="">-- Choose Test --</option>
            <option value="Blood Test">Blood Test</option>
            <option value="X-Ray">X-Ray</option>
            <option value="MRI Scan">MRI Scan</option>
            <option value="CT Scan">CT Scan</option>
            <option value="Urine Test">Urine Test</option>
        </select>

        <label>Priority Level</label>
        <select name="priority" required>
            <option value="Normal">Normal</option>
            <option value="Urgent">Urgent</option>
            <option value="Emergency">Emergency</option>
        </select>

        <label>Additional Notes</label>
        <textarea name="additional_notes" rows="3" placeholder="Enter instructions for the lab..."></textarea>

        <button type="submit">Submit Test Request</button>

    </form>

</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>