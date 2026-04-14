<?php 
include "../db.php"; 


$status_filter = "";
$current_status = "";
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $current_status = $conn->real_escape_string($_GET['status']);
    $status_filter = " AND insurance_claim.Status = '$current_status'";
}

// 3. SQL Query: Joining insurance_claim, patient, and invoice
$sql = "SELECT insurance_claim.Claim_ID, 
               insurance_claim.Invoice_ID, 
               patient.Patient_ID, 
               patient.Patient_Name, 
               patient.Phone_Number,
               insurance_claim.Amount, 
               insurance_claim.Status,
               invoice.date AS 'Claim_Date'
        FROM insurance_claim
        JOIN patient ON insurance_claim.Patient_ID = patient.Patient_ID
        JOIN invoice ON insurance_claim.Invoice_ID = invoice.invoice_Id
        $status_filter
        ORDER BY insurance_claim.Claim_ID DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insurance Claims Management</title>
    <style>
        body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
        header{ background-color:#d6ecff; padding:20px; }
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
        .container{ width:90%; margin:40px auto; background:white; padding:25px; border-radius:10px; box-shadow:0px 2px 5px rgba(0,0,0,0.1); }
        
        /* Filter Node Styling */
        .filter-box { margin-bottom: 25px; background: #eef7ff; padding: 15px; border-radius: 8px; display: flex; justify-content: center; gap: 10px; align-items: center; }
        .filter-box select, .filter-box button { padding: 8px; border-radius: 4px; border: 1px solid #ccc; }
        .filter-box button { background-color: #4da6ff; color: white; border: none; cursor: pointer; font-weight: bold; }

        table{ width:100%; border-collapse:collapse; margin-top:20px; }
        th, td{ border:1px solid #ddd; padding:12px; text-align: center; }
        th{ background-color:#eef7ff; }

        .status-Approved { color: green; font-weight: bold; }
        .status-Pending { color: orange; font-weight: bold; }
        .status-Rejected { color: red; font-weight: bold; }

        footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Insurance Department</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="container">
    <h2>Submitted Insurance Claims</h2>

    <div class="filter-box">
        <form action="" method="GET" style="display:flex; gap:10px; align-items:center;">
            <label><strong>Filter Status:</strong></label>
            <select name="status">
                <option value="">All Claims</option>
                <option value="Pending" <?php if($current_status == 'Pending') echo 'selected'; ?>>Pending</option>
                <option value="Approved" <?php if($current_status == 'Approved') echo 'selected'; ?>>Approved</option>
                <option value="Rejected" <?php if($current_status == 'Rejected') echo 'selected'; ?>>Rejected</option>
            </select>
            <button type="submit">Filter</button>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" style="text-decoration:none; color:#666; font-size:13px;">Reset</a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Claim ID</th>
                <th>Invoice ID</th>
                <th>Patient Name</th>
                <th>Phone Number</th>
                <th>Claim Date</th>
                <th>Claim Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $statusClass = "status-" . $row['Status'];
                    echo "<tr>";
                    echo "<td>" . $row['Claim_ID'] . "</td>";
                    echo "<td>" . $row['Invoice_ID'] . "</td>";
                    echo "<td>" . $row['Patient_Name'] . "</td>";
                    echo "<td>" . $row['Phone_Number'] . "</td>";
                    echo "<td>" . date('Y-m-d', strtotime($row['Claim_Date'])) . "</td>";
                    echo "<td>$" . number_format($row['Amount'], 2) . "</td>";
                    echo "<td><span class='$statusClass'>" . $row['Status'] . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No claims found matching the criteria.</td></tr>";
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