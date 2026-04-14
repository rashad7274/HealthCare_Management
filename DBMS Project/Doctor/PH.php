<?php 
session_start();
//$_SESSION['Doctor_ID'] = '2001';//for test
include "../db.php";

if (!isset($_SESSION['Doctor_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px;'>Please <a href='../login.php'>log in</a>.</h2>";
    exit;
}

$doctor_id = $_SESSION['Doctor_ID'];

// Date filter logic
$date_filter = "";
if (isset($_GET['visit_date']) && !empty($_GET['visit_date'])) {
    $selected_date = $conn->real_escape_string($_GET['visit_date']);
    // Casting the timestamp date to a simple date format for comparison
    $date_filter = " AND DATE(medical_record.date) = '$selected_date'";
}

// Fixed SQL based on your invoice table structure
$sql = "SELECT patient.Patient_Name, 
               medical_record.date, 
               medical_record.diagnosis, 
               medical_record.treatment, 
               invoice.amount AS 'Fee'
        FROM medical_record
        JOIN patient ON medical_record.Patient_ID = patient.Patient_ID
        LEFT JOIN invoice ON medical_record.Patient_ID = invoice.patient_Id 
                         AND DATE(medical_record.date) = DATE(invoice.date)
        WHERE medical_record.Doctor_ID = '$doctor_id' 
        $date_filter
        ORDER BY medical_record.date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient History</title>
<style>
    body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
    header{ background-color:#d6ecff; padding:20px; }
    header h1{ margin:0; }
    header h3{ margin:5px 0; color:#555; }
.logout {
    position: absolute; 
    right: 20px;     
    top: 20px; 
    width: auto;      
    padding: 8px 20px;  
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none; 
    font-size: 14px;
    white-space: nowrap;
}
        .logout:hover { background-color: #cc0000; }

    .history-container{ width:85%; margin:40px auto; background:white; padding:25px; border-radius:10px; box-shadow:0px 2px 5px rgba(0,0,0,0.1); }
    
    .filter-box { margin-bottom: 25px; background: #eef7ff; padding: 15px; border-radius: 8px; display: flex; justify-content: center; gap: 10px; align-items: center; }
    .filter-box input[type="date"] { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
    .filter-box button { padding: 8px 20px; background-color: #4da6ff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
    .filter-box a { padding: 8px 15px; background: #ccc; color: black; text-decoration: none; border-radius: 4px; font-size: 14px; }

    table{ width:100%; border-collapse:collapse; margin-top:20px; }
    th, td{ border:1px solid #ddd; padding:12px; }
    th{ background-color:#eef7ff; }
    tr:hover{ background-color:#f5f5f5; }
    footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
</style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Patient History</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="history-container">
    <h2>Completed Visit Records</h2>

    <div class="filter-box">
        <form action="" method="GET" style="display:flex; gap:10px; align-items:center;">
            <label for="visit_date"><strong>Filter by Visit Date:</strong></label>
            <input type="date" name="visit_date" id="visit_date" value="<?php echo isset($_GET['visit_date']) ? $_GET['visit_date'] : ''; ?>">
            <button type="submit">Filter</button>

            <a href="PH.php">Clear</a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Patient Name</th>
                <th>Visit Date</th>
                <th>Diagnosis</th>
                <th>Treatment</th>
                <th>Consultant Fee</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Patient_Name'] . "</td>";
                    echo "<td>" . date('d F Y', strtotime($row['date'])) . "</td>";
                    echo "<td>" . $row['diagnosis'] . "</td>";
                    echo "<td>" . $row['treatment'] . "</td>";
                    echo "<td>$" . ($row["Fee"] ?? "0.00") . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No completed records found for the selected criteria.</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
    </table>
</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>