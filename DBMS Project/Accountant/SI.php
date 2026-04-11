<?php
session_start();
include "../db.php"; // Ensure this path points to your database connection file

// 1. Check if the Insurance Officer is logged in
if (!isset($_SESSION['iOfficerID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../SystemAccess.php'>log in</a> to manage insurance claims.</h2>";
    exit;
}

$officer_id = $_SESSION['iOfficerID'];
$message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_claim'])) {
    // Sanitize inputs
    $Invoice_ID  = $conn->real_escape_string($_POST['Invoice_ID']);
    $Patient_ID  = $conn->real_escape_string($_POST['Patient_ID']);
    $Amount      = $conn->real_escape_string($_POST['Amount']);
    $date        = $conn->real_escape_string($_POST['date']);
    $Status      = $conn->real_escape_string($_POST['Status']);
    $Description = $conn->real_escape_string($_POST['Description']);

    // Prepare SQL to insert into insurance_claim table
    $sql = "INSERT INTO insurance_claim (Invoice_ID, Patient_ID, Amount, Officer_ID, Status, Description, date) 
            VALUES ('$Invoice_ID', '$Patient_ID', '$Amount', '$officer_id', '$Status', '$Description', '$date')";

    if ($conn->query($sql) === TRUE) {
        $message = "<p style='color:green; font-weight:bold; text-align:center;'>Claim submitted successfully!</p>";
    } else {
        $message = "<p style='color:red; font-weight:bold; text-align:center;'>Error: " . $conn->error . "</p>";
    }
}

// 3. Fetch claims history for the table
$fetch_sql = "SELECT * FROM insurance_claim ORDER BY Claim_ID DESC";
$result = $conn->query($fetch_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Submit Insurance</title>

<style>
    body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
    header{ background-color:#d6ecff; padding:20px; position:relative; }
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
}

.logout:hover {
    background-color: #cc0000;
}

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
        margin-bottom:15px;
        border-radius:5px;
        border:1px solid #ccc;
        box-sizing: border-box;
        font-family: inherit;
    }

    textarea {
        height: 80px;
        resize: vertical;
    }

    button{ width:100%; padding:10px; border:none; background-color:#4da6ff; color:white; border-radius:5px; cursor:pointer; font-weight: bold; }
    button:hover{ background-color:#3399ff; }

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

    .approved{ color:green; font-weight:bold; }
    .pending{ color:orange; font-weight:bold; }
    .rejected{ color:red; font-weight:bold; }

    footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
</style>
</head>

<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Submit Insurance</h3>
    <button class="logout" onclick="window.location.href='../logout.php'">Logout</button>
</header>

<div class="form-container">
    <h3>New Insurance Claim</h3>
    
    <?php echo $message; ?>

    <form action="" method="POST">

        <label for="Invoice_ID">Invoice ID</label>
        <input type="number" name="Invoice_ID" id="Invoice_ID" placeholder="Enter associated Invoice ID" required>

        <label for="Patient_ID">Patient ID</label>
        <input type="number" name="Patient_ID" id="Patient_ID" placeholder="Enter patient ID" required>

        <label for="Amount">Claim Amount ($)</label>
        <input type="number" step="0.01" name="Amount" id="Amount" placeholder="0.00" required>

        <label for="date">Claim Date</label>
        <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" required>

        <label for="Status">Initial Status</label>
        <select name="Status" id="Status">
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>

        <label for="Description">Claim Description / Notes</label>
        <textarea name="Description" id="Description" placeholder="Provide details or reasons for this claim..."></textarea>

        <button type="submit" name="submit_claim">Submit to Database</button>

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
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th> 
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    // Convert status to lowercase to match your CSS classes (approved, pending, rejected)
                    $status_class = strtolower($row['Status']);
                    
                    // Handle empty descriptions nicely
                    $desc = !empty($row['Description']) ? htmlspecialchars($row['Description']) : "(None)";

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Claim_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Invoice_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Patient_ID']) . "</td>";
                    echo "<td>$" . number_format($row['Amount'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                    echo "<td>" . $desc . "</td>";
                    echo "<td class='" . $status_class . "'>" . htmlspecialchars($row['Status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No claims found in the database.</td></tr>";
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