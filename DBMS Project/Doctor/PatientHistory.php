<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['patient_name'];
    $date = $_POST['visit_date'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];

    $sql = "INSERT INTO patient_history (patient_name, visit_date, diagnosis, treatment)
            VALUES ('$name', '$date', '$diagnosis', '$treatment')";

    if ($conn->query($sql)) {
        echo "✅ Patient Added Successfully";
        echo "<br><br><a href='patient_history.php'>⬅ Back to History</a>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Patient</title>

<style>
body{
    font-family: Arial;
    background:#f2f6f8;
    text-align:center;
}

.form-box{
    width:400px;
    margin:50px auto;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    text-align:left;
}

input{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#28a745;
    color:white;
    border:none;
    cursor:pointer;
}

button:hover{
    background:#218838;
}
</style>

</head>

<body>

<h2>Add New Patient</h2>

<div class="form-box">

<form method="POST">

    <label>Patient Name</label>
    <input type="text" name="patient_name" placeholder="Enter patient name" required>

    <label>Visit Date</label>
    <input type="date" name="visit_date" required>

    <label>Diagnosis</label>
    <input type="text" name="diagnosis" placeholder="Enter diagnosis" required>

    <label>Treatment</label>
    <input type="text" name="treatment" placeholder="Enter treatment" required>

    <button type="submit">Save Patient</button>

</form>

</div>

</body>
</html>