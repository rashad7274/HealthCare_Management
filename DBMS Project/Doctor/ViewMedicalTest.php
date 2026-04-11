<?php
include "db.php";

// search
$search = "";
if (isset($_GET['search_test_id'])) {
    $search = $_GET['search_test_id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Medical Tests</title>

<style>
body{ font-family: Arial; background:#f2f6f8; margin:0; text-align:center; }

header{ background:#d6ecff; padding:20px; }

.test-container{
    width:90%;
    margin:40px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.search-box{
    margin-bottom:20px;
}

input{
    padding:8px;
    width:200px;
}

button{
    padding:8px 15px;
    background:#4da6ff;
    color:white;
    border:none;
    cursor:pointer;
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
    background:#eef7ff;
}

.completed{ color:green; font-weight:bold; }
.pending{ color:orange; font-weight:bold; }

.file-link{ color:#4da6ff; font-weight:bold; text-decoration:none; }

</style>
</head>

<body>

<header>
<h1>Smart Healthcare Management System</h1>
<h3>Medical Investigation Department</h3>
</header>

<div class="test-container">

<h2>Patient Test Records</h2>

<!-- SEARCH -->
<form method="GET" class="search-box">
    <input type="text" name="search_test_id" placeholder="Search Test ID..." value="<?php echo $search; ?>">
    <button type="submit">Search</button>
</form>

<table>

<tr>
<th>Test ID</th>
<th>Patient ID</th>
<th>Doctor ID</th>
<th>Test Type</th>
<th>Result</th>
<th>Report</th>
<th>Status</th>
</tr>

<?php

if ($search != "") {
    $sql = "SELECT * FROM medical_tests WHERE test_id='$search'";
} else {
    $sql = "SELECT * FROM medical_tests";
}

$result = $conn->query($sql);

// fallback demo
$demo = [
    [5001, 'P-101', 'D-202', 'Blood Test', 'Hemoglobin normal', 'reports/5001.pdf', 'Completed'],
    [5002, 'P-105', 'D-205', 'MRI Scan', 'Awaiting Analysis', '', 'Pending']
];

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        $statusClass = ($row['status'] == "Completed") ? "completed" : "pending";

        echo "<tr>
            <td>{$row['test_id']}</td>
            <td>{$row['patient_id']}</td>
            <td>{$row['doctor_id']}</td>
            <td>{$row['test_type']}</td>
            <td>{$row['result_details']}</td>
            <td>";

        if ($row['report_file']) {
            echo "<a class='file-link' href='{$row['report_file']}'>View PDF</a>";
        } else {
            echo "---";
        }

        echo "</td>
            <td><span class='$statusClass'>{$row['status']}</span></td>
        </tr>";
    }
} else {
    foreach ($demo as $d) {
        echo "<tr>
            <td>{$d[0]}</td>
            <td>{$d[1]}</td>
            <td>{$d[2]}</td>
            <td>{$d[3]}</td>
            <td>{$d[4]}</td>
            <td>" . (!empty($d[5]) ? $d[5] : '---') . "</td>
            <td><span class='pending'>{$d[6]}</span></td>
        </tr>";
    }
}
?>

</table>

</div>

</body>
</html>