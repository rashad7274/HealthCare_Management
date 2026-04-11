<?php 
include "../db.php"; 


$priority_filter = "";
$current_priority = "";
if (isset($_GET['priority']) && !empty($_GET['priority'])) {
    $current_priority = $conn->real_escape_string($_GET['priority']);
    $priority_filter = " AND medicaltest.Priority_Level = '$current_priority'";
}

// 3. SQL Query: Mapped to healthcaredb.sql schema
$sql = "SELECT patient.Patient_Name, 
               patient.Patient_ID, 
               doctor.Doctor_Name,
               medicaltest.Test_Type AS 'Test_Type', 
               medicaltest.Priority_Level AS 'Priority', 
               medicaltest.Result_Status AS 'Status'
        FROM medicaltest 
        JOIN patient ON medicaltest.Patient_ID = patient.Patient_ID 
        JOIN doctor ON medicaltest.Doctor_ID = doctor.Doctor_ID
        WHERE medicaltest.Result_Status = 'Pending'
        $priority_filter
        ORDER BY CASE 
            WHEN medicaltest.Priority_Level = 'Emergency' THEN 1 
            WHEN medicaltest.Priority_Level = 'Urgent' THEN 2 
            ELSE 3 
        END";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Investigator - View Test Orders</title>
<style>
    body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
    header{ background-color:#d6ecff; padding:20px; border-bottom: 2px solid #b8d8ff; }
    .container{ width:90%; margin:30px auto; background:white; padding:25px; border-radius:10px; box-shadow:0px 2px 10px rgba(0,0,0,0.05); }
    .filter-node { margin-bottom: 25px; background: #eef7ff; padding: 15px; border-radius: 8px; display: flex; justify-content: center; gap: 10px; align-items: center; }
    .filter-node select, .filter-node button { padding: 8px; border-radius: 4px; border: 1px solid #ccc; }
    .filter-node button { background-color: #4da6ff; color: white; border: none; cursor: pointer; font-weight: bold; }
    table{ width:100%; border-collapse:collapse; }
    th, td{ border:1px solid #ddd; padding:12px; }
    th{ background-color:#eef7ff; color: #333; }
    .badge { padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 12px; }
    .emergency { background-color: #ff4d4d; color: white; }
    .urgent { background-color: #ffc107; color: #333; }
    .normal { background-color: #e9ecef; color: #333; }
    footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
</style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Laboratory Investigation Module</h3>
</header>

<div class="container">
    <h2>Pending Test Queue</h2>

    <div class="filter-node">
        <form action="" method="GET" style="display:flex; gap:10px; align-items:center;">
            <label><strong>Priority Filter:</strong></label>
            <select name="priority">
                <option value="">All Tests</option>
                <option value="Normal" <?php if($current_priority == 'Normal') echo 'selected'; ?>>Normal</option>
                <option value="Urgent" <?php if($current_priority == 'Urgent') echo 'selected'; ?>>Urgent</option>
                <option value="Emergency" <?php if($current_priority == 'Emergency') echo 'selected'; ?>>Emergency</option>
            </select>
            <button type="submit">Update View</button>
            <a href="VTO.php" style="font-size: 13px; color: #555; text-decoration: none;">Reset</a>
        </form>
    </div>

    <table>
        <tr>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Ordering Doctor</th>
            <th>Test Required</th>
            <th>Priority</th>
            <th>Status</th>
        </tr>

        <?php
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pLevel = strtolower($row["Priority"]);
                $priorityClass = ($pLevel == 'emergency') ? 'emergency' : (($pLevel == 'urgent') ? 'urgent' : 'normal');
        ?>
            <tr>
                <td>#<?php echo $row["Patient_ID"]; ?></td>
                <td><?php echo $row["Patient_Name"]; ?></td>
                <td>Dr. <?php echo $row["Doctor_Name"]; ?></td>
                <td><?php echo $row["Test_Type"]; ?></td>
                <td><span class="badge <?php echo $priorityClass; ?>"><?php echo $row["Priority"]; ?></span></td>
                <td style="color:#007bff; font-weight:bold;"><?php echo $row["Status"]; ?></td>
            </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='6'>No pending lab orders found.</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

<footer>Smart Healthcare Management System © 2026</footer>
</body>
</html>