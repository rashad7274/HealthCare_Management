<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['patient_name'];
    $pid = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $medications = $_POST['medications'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO prescriptions (patient_name, patient_id, diagnosis, medications, notes)
            VALUES ('$name', '$pid', '$diagnosis', '$medications', '$notes')";

    if ($conn->query($sql)) {
        echo "✅ Prescription Saved Successfully";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Write Prescription</title>

<style>

body{
    font-family: Arial;
    background:#f2f6f8;
    text-align:center;
}

.form-container{
    width:450px;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    text-align:left;
}

input, textarea{
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

<h2>Write Prescription</h2>

<div class="form-container">

<form method="POST">

<label>Patient Name</label>
<input type="text" name="patient_name" required>

<label>Patient ID</label>
<input type="text" name="patient_id" required>

<label>Diagnosis</label>
<textarea name="diagnosis" required></textarea>

<label>Medications</label>
<textarea name="medications" required></textarea>

<label>Notes</label>
<textarea name="notes"></textarea>

<button type="submit">Save Prescription</button>

</form>

</div>

</body>
</html>