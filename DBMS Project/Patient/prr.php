<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$_SESSION['Patient_ID'] = '1001'; // <--- ADD THIS LINE HERE

include "../db.php"; 

if (!isset($_SESSION['Patient_ID'])) {
    // This code will now be skipped because the session is set above
    die("<h2 style='text-align:center; margin-top:50px;'>Please <a href='../login.php'>log in</a> to access your care requests.</h2>");
}

$patient_id = $_SESSION['Patient_ID'];
$message = "";

// 1. Handle New Request Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $category = $_POST['Category'];
    $condition = $_POST['Patient_Condition'];
    $notes = $_POST['Patient_Notes'];
    $req_date = $_POST['Request_Date'];

    try {
        $sql = "INSERT INTO care_requests (Patient_ID, Category, Patient_Condition, Patient_Notes, Request_Date) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $patient_id, $category, $condition, $notes, $req_date);

        if ($stmt->execute()) {
            $message = "<script>alert('Request submitted successfully!');</script>";
        }
    } catch (Exception $e) {
        $message = "<p style='color:red;'>Error: " . $e->getMessage() . "</p>";
    }
}

// 2. Fetch History for the Logged-in Patient
$history_sql = "SELECT * FROM care_requests WHERE Patient_ID = ? ORDER BY Request_Date DESC";
$stmt_history = $conn->prepare($history_sql);
$stmt_history->bind_param("i", $patient_id);
$stmt_history->execute();
$history_result = $stmt_history->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Request Care Recommendation</title>
<style>
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f2f6f8; margin: 0; padding: 0; text-align: center; }
    header { background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 30px 20px; position: relative; }
    header h1 { margin: 0; font-size: 26px; }
    header p { margin-top: 5px; font-size: 14px; color: #b3d4ff; }

    .logout {
        position: absolute; right: 20px; top: 30px; padding: 8px 20px;
        background-color: #ff4d4d; color: white; border: none; border-radius: 5px;
        cursor: pointer; font-weight: bold; text-decoration: none; font-size: 14px;
    }

    .form-container { width: 500px; margin: -20px auto 40px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0px 8px 15px rgba(0,0,0,0.1); text-align: left; }
    label { font-weight: bold; display: block; margin-top: 15px; color: #444; font-size: 14px; }
    input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; }
    button.submit-btn { width: 100%; padding: 12px; border: none; background-color: #007bff; color: white; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px; }

    .table-container { width: 95%; margin: 20px auto 40px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 2px 5px rgba(0,0,0,0.1); }
    table { width: 100%; border-collapse: collapse; font-size: 14px; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #eef7ff; }
    
    /* Status Tags */
    .status { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; color: white; display: inline-block; }
    .status-pending { background-color: #ffc107; color: #333; }
    .status-responded { background-color: #28a745; }
    
    footer { background: #e8eef2; padding: 15px; font-size: 14px; color: #444; }
</style>
</head>
<body>

<?php echo $message; ?>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Request Preventive Care & Recommendations</h3>
    <p>Logged in as Patient ID: <strong><?php echo $patient_id; ?></strong></p>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="form-container">
    <h3 style="text-align:center; margin-top:0;">Submit a New Request</h3>
    <form action="" method="POST">
        <label>Care Category</label>
        <select name="Category" required>
            <option value="">-- Select Category --</option>
            <option value="Diet/Lifestyle">Diet & Lifestyle</option>
            <option value="Vaccination">Vaccination Inquiry</option>
            <option value="Routine Screening">Routine Screening</option>
            <option value="General Health">General Health Concern</option>
        </select>

        <label>Current Condition / Symptoms</label>
        <input type="text" name="Patient_Condition" placeholder="e.g., Frequent headaches" required>

        <label>Additional Notes</label>
        <textarea name="Patient_Notes" placeholder="Provide extra details..."></textarea>

        <label>Date of Request</label>
        <input type="date" name="Request_Date" value="<?php echo date('Y-m-d'); ?>" required>

        <button type="submit" name="submit_request" class="submit-btn">Submit Request</button>
    </form>
</div>

<div class="table-container">
    <h3 style="text-align: center; color: #333; margin-top: 0;">My Request History</h3>
    <table>
        <thead>
            <tr>
                <th>Req ID</th>
                <th>Date Requested</th>
                <th>Category</th>
                <th>Condition</th>
                <th>Status</th>
                <th>Doctor's Recommendation</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($history_result->num_rows > 0): ?>
                <?php while($row = $history_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['Req_ID']; ?></td>
                        <td><?php echo $row['Request_Date']; ?></td>
                        <td><?php echo $row['Category']; ?></td>
                        <td><?php echo $row['Patient_Condition']; ?></td>
                        <td>
                            <?php 
                                $statusClass = ($row['Status'] == 'Responded') ? 'status-responded' : 'status-pending';
                                echo "<span class='status $statusClass'>".$row['Status']."</span>";
                            ?>
                        </td>
                        <td>
                            <?php echo $row['Doctor_Recommendation'] ? $row['Doctor_Recommendation'] : "<span style='color: #888; font-style: italic;'>Awaiting feedback...</span>"; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center;">No requests found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer>Smart Healthcare Management System © 2026</footer>

</body>
</html>