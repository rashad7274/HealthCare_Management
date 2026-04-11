<?php 
session_start(); 
$_SESSION['Doctor_ID'] = '2001';
include "../db.php";

if (!isset($_SESSION['Doctor_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px;'>Please <a href='../login.php'>log in</a> to view your schedule.</h2>";
    exit; 
}

$logged_in_doctor = $_SESSION['Doctor_ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $app_id = $conn->real_escape_string($_POST['appointment_id']);
    $new_status = $conn->real_escape_string($_POST['new_status']);
    
    $update_sql = "UPDATE appointment SET status = '$new_status' WHERE Appointment_ID = '$app_id'";
    $conn->query($update_sql);
}

$statusFilter = "";
$current_filter = "";
if (isset($_GET['filter_status']) && $_GET['filter_status'] != "") {
    $current_filter = $conn->real_escape_string($_GET['filter_status']);
    $statusFilter = " AND appointment.status = '$current_filter'";
}

$sql = "SELECT appointment.Appointment_ID AS 'App_ID',
               appointment.time AS 'Time', 
               patient.Patient_Name AS 'Patient Name', 
               appointment.reason AS 'Appointment Type', 
               appointment.status AS 'Status' 
        FROM appointment 
        JOIN patient ON appointment.Patient_ID = patient.Patient_ID 
        WHERE appointment.Doctor_ID = '$logged_in_doctor' $statusFilter
        ORDER BY appointment.date, appointment.time";

$result = $conn->query($sql);
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

header h1{
    margin:0;
}

header h3{
    margin:5px 0;
    color:#555;
}

.schedule-container{
    width:80%;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0px 2px 5px rgba(0,0,0,0.1);
}

.filter-box {
    margin-bottom: 25px;
    background: #eef7ff;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: center;
    gap: 10px;
    align-items: center;
}

select, input {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 8px 15px;
    background-color: #4da6ff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
}

button:hover {
    background-color: #3399ff;
}

.modify-btn {
    background-color: #28a745;
}

.modify-btn:hover {
    background-color: #218838;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th, td{
    border:1px solid #ddd;
    padding:10px;
    vertical-align: middle;
}

th{
    background-color:#eef7ff;
}

tr:hover{
    background-color:#f5f5f5;
}

form {
    margin: 0;
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
<h3>View Daily Schedule</h3>
</header>

<div class="schedule-container">

<h2>Your Appointments</h2>

<div class="filter-box">
    <form action="" method="GET" style="display:flex; gap:10px; align-items: center;">
        <label for="filter_status"><strong>Filter by Status:</strong></label>
        <select name="filter_status" id="filter_status">
            <option value="">All Appointments</option>
            <option value="Pending" <?php if($current_filter == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Confirmed" <?php if($current_filter == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
            <option value="Completed" <?php if($current_filter == 'Completed') echo 'selected'; ?>>Completed</option>
        </select>
        <button type="submit">Filter</button>
    </form>
</div>

<table>

<tr>
<th>Time</th>
<th>Patient Name</th>
<th>Appointment Type</th>
<th>Current Status</th>
<th>Modify Status</th>
</tr>

<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $row["Time"]; ?></td>
    <td><?php echo $row["Patient Name"]; ?></td>
    <td><?php echo $row["Appointment Type"]; ?></td>
    <td><strong><?php echo $row["Status"]; ?></strong></td>
    <td>
        <form action="" method="POST" style="display:flex; gap:5px; justify-content:center;">
            <input type="hidden" name="appointment_id" value="<?php echo $row["App_ID"]; ?>">
            <select name="new_status">
                <option value="Pending" <?php if($row["Status"] == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Confirmed" <?php if($row["Status"] == 'Confirmed') echo 'selected'; ?>>Confirmed</option>
                <option value="Completed" <?php if($row["Status"] == 'Completed') echo 'selected'; ?>>Completed</option>
            </select>
            <button type="submit" name="update_status" class="modify-btn">Update</button>
        </form>
    </td>
</tr>
<?php
    }
} else {
    echo "<tr><td colspan='5'>You have no appointments matching this status.</td></tr>";
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