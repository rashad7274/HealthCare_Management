<?php 
session_start();
//$_SESSION['Patient_ID'] = '1001';//for test
include "../db.php"; 

if (!isset($_SESSION['Patient_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px;'>Please <a href='../login.php'>log in</a> to view your records.</h2>";
    exit;
}

$logged_in_patient = $_SESSION['Patient_ID'];

$sql = "SELECT medical_record.date AS 'Date', 
               doctor.Doctor_Name AS 'Doctor', 
               medical_record.diagnosis AS 'Diagnosis', 
               medical_record.treatment AS 'Prescription' 
        FROM medical_record 
        JOIN doctor ON medical_record.doctor_id = doctor.Doctor_ID
        WHERE medical_record.Patient_ID = '$logged_in_patient'";

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
    width:80%;
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

<h2>Your Personal Medical History</h2>

<table>

<tr>
<th>Date</th>
<th>Doctor</th>
<th>Diagnosis</th>
<th>Prescription</th>
</tr>

<?php
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
    <tr>
        <td><?php echo $row["Date"]; ?></td>
        <td><?php echo $row["Doctor"]; ?></td>
        <td><?php echo $row["Diagnosis"]; ?></td>
        <td><?php echo $row["Prescription"]; ?></td>
    </tr>
<?php
    }
} else {
    echo "<tr><td colspan='4'>No completed medical records found for your account.</td></tr>";
}
$conn->close();
?>

</table>

</div>

<footer>
Smart Healthcare Management System © 2026
</footer>

</body>
</html>