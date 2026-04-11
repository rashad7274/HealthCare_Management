<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_id = $_POST['patient_id'];
    $date = $_POST['date'];
    $symptom = $_POST['symptom'];
    $severity = $_POST['severity'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO symptoms (patient_id, date, symptom, severity, notes)
            VALUES ('$patient_id', '$date', '$symptom', '$severity', '$notes')";

    if ($conn->query($sql)) {
        echo "✅ Symptoms logged successfully";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Log Symptoms</title>

<style>

body{
    font-family: Arial;
    background:#f2f6f8;
    text-align:center;
}

.form-container{
    width:420px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    text-align:left;
}

input, select, textarea{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#4da6ff;
    color:white;
    border:none;
    cursor:pointer;
}

button:hover{
    background:#3399ff;
}

</style>
</head>

<body>

<header>
<h1>Smart Healthcare Management System</h1>
<h3>Log Symptoms</h3>
</header>

<div class="form-container">

<form method="POST">

<label>Patient ID</label>
<input type="number" name="patient_id" required>

<label>Date</label>
<input type="date" name="date" required>

<label>Select Symptom</label>
<select name="symptom" required>
    <option>Fever</option>
    <option>Cough</option>
    <option>Headache</option>
    <option>Fatigue</option>
    <option>Other</option>
</select>

<label>Severity</label>
<select name="severity" required>
    <option>Mild</option>
    <option>Moderate</option>
    <option>Severe</option>
</select>

<label>Additional Notes</label>
<textarea name="notes"></textarea>

<button type="submit">Submit Symptoms</button>

</form>

</div>

</body>
</html>