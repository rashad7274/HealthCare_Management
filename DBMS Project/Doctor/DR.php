<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Simulated Login - In production, this comes from your login page
//$_SESSION['Doctor_ID'] = 2001; 

include "../db.php"; 

if (!isset($_SESSION['Doctor_ID'])) {
    header("Location: ../login.php");
    exit();
}

$doctor_id = $_SESSION['Doctor_ID'];
$message = "";

// 1. HANDLE UPDATE: When the doctor submits the feedback form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_feedback'])) {
    $req_id = $_POST['Req_ID'];
    $status = $_POST['Feedback_Status'];
    $recommendation = $_POST['Doctor_Recommendation'];

    try {
        // Update the existing request with doctor's feedback and ID
        $sql = "UPDATE care_requests 
                SET Doctor_ID = ?, Status = ?, Doctor_Recommendation = ? 
                WHERE Req_ID = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issi", $doctor_id, $status, $recommendation, $req_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $message = "<script>alert('Feedback successfully sent to Patient portal!');</script>";
            } else {
                $message = "<script>alert('Error: Request ID not found.');</script>";
            }
        }
    } catch (Exception $e) {
        $message = "<p style='color:red;'>System Error: " . $e->getMessage() . "</p>";
    }
}

// 2. FETCH DATA: Get all requests to display in the inbox
$query = "SELECT * FROM care_requests ORDER BY Request_Date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor - Manage Care Recommendations</title>
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

        .dashboard-container { width: 95%; margin: 20px auto; display: flex; flex-direction: column; gap: 30px; align-items: center; }
        .table-container { width: 100%; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); box-sizing: border-box; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #eef7ff; }
        
        .form-container { width: 600px; background: white; padding: 25px; border-radius: 10px; box-shadow: 0px 8px 15px rgba(0,0,0,0.1); text-align: left; }
        label { font-weight: bold; display: block; margin-top: 15px; color: #444; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; box-sizing: border-box; }
        textarea { height: 120px; resize: vertical; }

        button.submit-btn { width: 100%; padding: 12px; border: none; background-color: #28a745; color: white; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 16px; margin-top: 20px; }
        
        .status { padding: 5px 10px; border-radius: 15px; font-size: 12px; font-weight: bold; color: white; display: inline-block; min-width: 80px; text-align: center;}
        .status-pending { background-color: #ffc107; color: #333; }
        .status-responded { background-color: #28a745; }

        footer { background: #e8eef2; padding: 15px; font-size: 14px; color: #444; margin-top: 40px; }
    </style>
</head>
<body>

<?php echo $message; ?>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Patient Requests & Clinical Recommendations</h3>
    <p>Logged in as Doctor ID: <strong><?php echo $doctor_id; ?></strong></p>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="dashboard-container">

    <div class="table-container">
        <h3 style="margin-top:0;">Inbox: Patient Care Requests</h3>
        <table>
            <thead>
                <tr>
                    <th>Req ID</th>
                    <th>Patient ID</th>
                    <th>Date Requested</th>
                    <th>Category</th>
                    <th>Condition</th>
                    <th>Patient Notes</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td style="font-weight:bold;"><?php echo $row['Req_ID']; ?></td>
                        <td><?php echo $row['Patient_ID']; ?></td>
                        <td><?php echo $row['Request_Date']; ?></td>
                        <td><?php echo $row['Category']; ?></td>
                        <td><?php echo $row['Patient_Condition']; ?></td>
                        <td><?php echo $row['Patient_Notes']; ?></td>
                        <td>
                            <?php 
                                $class = ($row['Status'] == 'Responded') ? 'status-responded' : 'status-pending';
                                echo "<span class='status $class'>".$row['Status']."</span>";
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center;">No patient requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="form-container">
        <h3 style="text-align:center; margin-top:0;">Provide Recommendation / Feedback</h3>
        <form action="" method="POST">
            <label>Target Request ID</label>
            <input type="number" name="Req_ID" placeholder="Enter ID from the table above" required>

            <label>Update Status</label>
            <select name="Feedback_Status">
                <option value="Responded">Mark as Responded</option>
                <option value="Pending Review">Keep Pending</option>
            </select>

            <label>Official Recommendation / Medical Feedback</label>
            <textarea name="Doctor_Recommendation" placeholder="Type care plan or advice here..." required></textarea>

            <button type="submit" name="submit_feedback" class="submit-btn">Submit Feedback</button>
        </form>
    </div>

</div>

<footer>Smart Healthcare Management System © 2026</footer>

</body>
</html>