<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Daily Schedule</title>

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

.schedule-container{
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

</style>
</head>

<body>

<header>
<h1>Smart Healthcare Management System</h1>
<h3>View Daily Schedule</h3>
</header>

<div class="schedule-container">

<h2>Today's Appointments</h2>

<table>

<tr>
<th>Time</th>
<th>Patient Name</th>
<th>Appointment Type</th>
<th>Status</th>
</tr>

<?php
$sql = "SELECT * FROM daily_schedule ORDER BY id ASC";
$result = $conn->query($sql);

// demo fallback
$demoData = [
    ["09:00 AM", "John Smith", "General Checkup", "Confirmed"],
    ["10:30 AM", "Sarah Ahmed", "Follow-up Visit", "Confirmed"],
    ["12:00 PM", "Michael Lee", "Consultation", "Pending"],
    ["02:00 PM", "Fatima Khan", "Medical Review", "Confirmed"]
];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['time']}</td>
            <td>{$row['patient_name']}</td>
            <td>{$row['appointment_type']}</td>
            <td>{$row['status']}</td>
        </tr>";
    }
} else {
    foreach ($demoData as $d) {
        echo "<tr>
            <td>{$d[0]}</td>
            <td>{$d[1]}</td>
            <td>{$d[2]}</td>
            <td>{$d[3]}</td>
        </tr>";
    }
}
?>

</table>

</div>

</body>
</html>