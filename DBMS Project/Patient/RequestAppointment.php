<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_name = $_POST['patient_name'];
    $doctor_name = $_POST['doctor_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];

    $sql = "INSERT INTO appointments (patient_name, doctor_name, appointment_date, appointment_time, reason)
            VALUES ('$patient_name', '$doctor_name', '$date', '$time', '$reason')";

    if ($conn->query($sql)) {
        echo "✅ Appointment Request Submitted";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Request Appointment</title>

<style>

body{
    font-family: Arial;
    background:#f2f6f8;
    text-align:center;
}

.form-container{
    width:400px;
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
<h3>Request Appointment</h3>
</header>

<div class="form-container">

<form method="POST">

<label>Patient Name</label>
<input type="text" name="patient_name" required>

<label>Select Doctor</label>
<select name="doctor_name" required>
    <option>Dr. Ahmed (Cardiologist)</option>
    <option>Dr. Rahman (Dermatologist)</option>
    <option>Dr. Khan (General Physician)</option>
</select>

<label>Appointment Date</label>
<input type="date" name="date" required>

<label>Preferred Time</label>
<input type="time" name="time" required>

<label>Reason for Visit</label>
<textarea name="reason" required></textarea>

<button type="submit">Submit Appointment</button>

</form>

</div>

</body>
</html>