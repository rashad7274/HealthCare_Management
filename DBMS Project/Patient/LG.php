<?php
session_start();
include "../db.php"; // Make sure this path correctly points to your database connection file

// 1. Check if the patient is logged in
if (!isset($_SESSION['Patient_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../login.php'>log in</a> to log your symptoms.</h2>";
    exit;
}

$patient_id = $_SESSION['Patient_ID'];
$message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $symptom = $conn->real_escape_string($_POST['symptom']);
    $severity = $conn->real_escape_string($_POST['severity']);
    $additional_notes = $conn->real_escape_string($_POST['additional_notes']);
    
    // Automatically set the current date
    $current_date = date('Y-m-d');

    // Handle optional additional notes (set to NULL if empty)
    $notes_val = !empty($additional_notes) ? "'$additional_notes'" : "NULL";

    // 3. Insert into Database
    // Updated the table name here to `symptomlog`
    $sql = "INSERT INTO symptomlog (Patient_ID, symptom, additional_notes, severity, date) 
            VALUES ('$patient_id', '$symptom', $notes_val, '$severity', '$current_date')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert success'>Symptoms logged successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Log Symptoms</title>

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
    box-sizing: border-box; 
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

button:hover { background-color: #3399ff; }

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
    <h3>Log Symptoms</h3>
</header>

<div class="form-container">

    <?php echo $message; ?>

    <form action="" method="POST">

        <label>Select Symptom</label>
        <select name="symptom" required>
            <option value="">-- Select a Symptom --</option>
            <option value="Fever">Fever</option>
            <option value="Cough">Cough</option>
            <option value="Headache">Headache</option>
            <option value="Fatigue">Fatigue</option>
            <option value="Other">Other</option>
        </select>

        <label>Severity</label>
        <select name="severity" required>
            <option value="">-- Select Severity --</option>
            <option value="Mild">Mild</option>
            <option value="Moderate">Moderate</option>
            <option value="Severe">Severe</option>
        </select>

        <label>Additional Notes</label>
        <textarea name="additional_notes" rows="3" placeholder="Describe your symptoms..."></textarea>

        <button type="submit">Submit Symptoms</button>

    </form>

</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>