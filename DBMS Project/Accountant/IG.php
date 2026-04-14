<?php
session_start();

// --- SESSION & DATABASE HEADER ---
// For testing purposes, we use '3002' (Peter Parker) from your SQL data
//$_SESSION['Accountant_ID'] = '3002'; 

// Include your database connection file
include "../db.php"; 

// 1. Check if the Accountant is logged in
if (!isset($_SESSION['Accountant_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../login.php'>log in</a> to generate invoices.</h2>";
    exit;
}

$accountant_id = $_SESSION['Accountant_ID'];

// --- HANDLE FORM SUBMISSION ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_bill'])) {
    // Collect and sanitize inputs matching SQL schema
    $patient_Id  = $_POST['patient_Id'];
    $description = $_POST['description'];
    $amount      = $_POST['amount'];
    $date        = $_POST['date'];
    $status      = $_POST['status'];

    // Prepare SQL to insert into invoice table
    // invoice_Id is omitted as it is AUTO_INCREMENT
    $stmt = $conn->prepare("INSERT INTO invoice (accountantID, patient_Id, description, amount, date, status) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Bind parameters: 'ii' for integers, 's' for string, 'd' for decimal/double
    $stmt->bind_param("iisdss", $accountant_id, $patient_Id, $description, $amount, $date, $status);

    if ($stmt->execute()) {
        echo "<script>alert('Invoice generated successfully!'); window.location.href='SI_Invoice.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}

// Fetch invoice history for the table
$sql = "SELECT * FROM invoice ORDER BY invoice_Id DESC";
$history_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Generation</title>
    <style>
        body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
        
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
        .logout:hover { background-color: #ee5a5a; }

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
        input, select {
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
        .Paid{ color:green; font-weight:bold; }
        .Due{ color:orange; font-weight:bold; }
        footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Invoice Generation</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="form-container">
    <h3>Generate New Invoice</h3>
    <form action="SI_Invoice.php" method="POST">
        <label for="patient_Id">Patient ID</label>
        <input type="number" name="patient_Id" id="patient_Id" placeholder="Enter patient ID" required>

        <label for="description">Service Description</label>
        <input type="text" name="description" id="description" placeholder="e.g. Consultation" required>

        <label for="amount">Total Amount ($)</label>
        <input type="number" step="0.01" name="amount" id="amount" placeholder="0.00" required>

        <label for="date">Invoice Date</label>
        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="status">Payment Status</label>
        <select name="status" id="status" required>
            <option value="Due">Due</option>
            <option value="Paid">Paid</option>
        </select>

        <button type="submit" name="generate_bill" class="submit-btn">Generate Bill</button>
    </form>
</div>

<div class="table-container">
    <h3>Invoice History</h3>
    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Patient ID</th>
                <th>Accountant ID</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($history_result && $history_result->num_rows > 0) {
                while($row = $history_result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . $row["invoice_Id"] . "</td>
                            <td>" . $row["patient_Id"] . "</td>
                            <td>" . $row["accountantID"] . "</td>
                            <td>" . htmlspecialchars($row["description"]) . "</td>
                            <td>$" . number_format($row["amount"], 2) . "</td>
                            <td>" . $row["date"] . "</td>
                            <td class='" . $row["status"] . "'>" . $row["status"] . "</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No invoices found</td></tr>";
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