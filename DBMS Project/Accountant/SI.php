<?php
session_start();

// --- SESSION & DATABASE HEADER ---
// For testing purposes, we use '6001' (Bruce Wayne) from your SQL data
//$_SESSION['iOfficerID'] = '6001'; 

// Include your database connection file
include "../db.php"; 

// 1. Check if the Insurance Officer is logged in
if (!isset($_SESSION['Accountant_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../login.php'>log in</a> to manage insurance claims.</h2>";
    exit;
}

$officer_id = $_SESSION['Accountant_ID'];

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_claim'])) {
    // Collect and sanitize inputs matching SQL schema
    $Invoice_ID  = $_POST['Invoice_ID'];
    $Patient_ID  = $_POST['Patient_ID'];
    $Amount      = $_POST['Amount'];
    $Status      = $_POST['Status'];
    $date        = $_POST['date'];
    $Description = $_POST['Description'];

    // Prepare SQL to insert into insurance_claim table
    // Claim_ID is omitted as it is AUTO_INCREMENT
    $stmt = $conn->prepare("INSERT INTO insurance_claim (Invoice_ID, Patient_ID, Amount, Officer_ID, Status, Description, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iidisss", $Invoice_ID, $Patient_ID, $Amount, $officer_id, $Status, $Description, $date);

    if ($stmt->execute()) {
        echo "<script>alert('Claim submitted successfully!'); window.location.href='SI.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch claims history for the table
$sql = "SELECT * FROM insurance_claim ORDER BY Claim_ID DESC";
$history_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Insurance</title>
    <style>
        body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
        
        /* Fixed Header and Logout Button CSS */
        header {
            background-color: #d6ecff;
            padding: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 80px;
        }
        header h1 { margin: 0; font-size: 24px; }
        header h3 { margin: 5px 0 0 0; color: #555; }

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

        .form-container{
            width:400px;
            margin:40px auto;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0px 2px 5px rgba(0,0,0,0.1);
            text-align:left;
        }
        label{ font-weight:bold; display: block; margin-top: 10px; }
        input, select, textarea{
            width:100%;
            padding:8px;
            margin-top:5px;
            border-radius:5px;
            border:1px solid #ccc;
            box-sizing: border-box;
        }
        button.submit-btn {
            width:100%;
            padding:10px;
            border:none;
            background-color:#4da6ff;
            color:white;
            border-radius:5px;
            cursor:pointer;
            font-weight: bold;
            margin-top: 20px;
        }
        button.submit-btn:hover { background-color:#3399ff; }

        .table-container{
            width:90%;
            margin:40px auto;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0px 2px 5px rgba(0,0,0,0.1);
        }
        table{ width:100%; border-collapse:collapse; font-size: 14px;}
        th, td{ border:1px solid #ddd; padding:12px; text-align: center; }
        th{ background-color:#eef7ff; }
        .Approved{ color:green; font-weight:bold; }
        .Pending{ color:orange; font-weight:bold; }
        .Rejected{ color:red; font-weight:bold; }
        footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Submit Insurance</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="form-container">
    <h3>New Insurance Claim</h3>
    <form action="SI.php" method="POST">
        <label for="Invoice_ID">Invoice ID</label>
        <input type="number" name="Invoice_ID" id="Invoice_ID" required>

        <label for="Patient_ID">Patient ID</label>
        <input type="number" name="Patient_ID" id="Patient_ID" required>

        <label for="Amount">Claim Amount ($)</label>
        <input type="number" step="0.01" name="Amount" id="Amount" required>

        <label for="date">Claim Date</label>
        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="Status">Initial Status</label>
        <select name="Status" id="Status">
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>

        <label for="Description">Claim Description / Notes</label>
        <textarea name="Description" id="Description"></textarea>

        <button type="submit" name="submit_claim" class="submit-btn">Submit to Database</button>
    </form>
</div>

<div class="table-container">
    <h3>Insurance Claims History</h3>
    <table>
        <thead>
            <tr>
                <th>Claim ID</th>
                <th>Invoice ID</th>
                <th>Patient ID</th>
                <th>Officer ID</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th> 
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($history_result->num_rows > 0) {
                while($row = $history_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["Claim_ID"] . "</td>
                            <td>" . $row["Invoice_ID"] . "</td>
                            <td>" . $row["Patient_ID"] . "</td>
                            <td>" . $row["Officer_ID"] . "</td>
                            <td>$" . number_format($row["Amount"], 2) . "</td>
                            <td>" . $row["date"] . "</td>
                            <td>" . htmlspecialchars($row["Description"] ?? 'N/A') . "</td>
                            <td class='" . $row["Status"] . "'>" . $row["Status"] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>