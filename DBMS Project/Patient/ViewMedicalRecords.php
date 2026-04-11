<?php
include "db.php";

$patient_id = 1; // later you can replace with SESSION patient id

$sql = "SELECT * FROM medical_records WHERE patient_id = $patient_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Medical Records</title>

<style>

body{
    font-family: Arial, sans-serif;
    background-color:#f2f6f8;
    margin:0;
    padding:0;
    text-align:center;
}

header{
    background-color:#d6ecff;
    padding:20px;
}

header h1{
    margin:0;
}

header h3{
    margin:5px 0;
    color:#555;
}

.records-container{
    width:70%;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0px 2px 5px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th, td{
    border:1px solid #ddd;
    padding:10px;
}

th{
    background-color:#eef7ff;
}

tr:hover{
    background-color:#f5f5f5;
}

footer{
    background:#e8eef2;
    padding:15px;
    margin-top:40px;
    font-size:14px;
    color:#444;
}

</style>
</head>

<body>

<header>
<h1>Smart Healthcare Management System</h1>
<h3>View Medical Records</h3>
</header>

<div class="records-container">

<h2>Your Medical Records</h2>

<table>

<tr>
<th>Date</th>
<th>Doctor</th>
<th>Diagnosis</th>
<th>Prescription</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['visit_date']}</td>
            <td>{$row['doctor_name']}</td>
            <td>{$row['diagnosis']}</td>
            <td>{$row['prescription']}</td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No records found</td></tr>";
}
?>

</table>

</div>

<footer>
Smart Healthcare Management System © 2026
</footer>

</body>
</html>