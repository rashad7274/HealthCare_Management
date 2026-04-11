<?php
session_start();
include "../db.php"; // Ensure this points to your database connection file

// --- SESSION HANDLING ---
// For testing purposes, we use '6001' (Bruce Wayne) from your SQL data
//$_SESSION['iOfficerID'] = '6001'; 

if (!isset($_SESSION['iOfficerID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../login.php'>log in</a> to manage claims.</h2>";
    exit;
}

$officer_id = $_SESSION['iOfficerID'];

// --- HANDLE SEARCH LOGIC ---
// This allows filtering the table by a specific Claim ID
$search_id = isset($_GET['search_id']) ? mysqli_real_escape_string($conn, $_GET['search_id']) : '';
$where_clause = !empty($search_id) ? " WHERE Claim_ID = '$search_id'" : "";

// Fetch claims from the insurance_claim table
$sql = "SELECT * FROM insurance_claim $where_clause ORDER BY Claim_ID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Insurance Claims</title>
    <style>
        body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
        header{ background-color:#d6ecff; padding:20px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80px; }
        header h1{ margin:0; font-size: 24px; }
        header h3{ margin:5px 0; color:#555; }

        .logout { position: absolute; right: 20px; top: 50%; transform: translateY(-50%); width: auto; padding: 8px 15px; border: none; background-color: #ff6b6b; color: white; border-radius: 5px; cursor: pointer; font-weight: bold; }

        .main-container { display: flex; flex-direction: column; align-items: center; padding: 20px; }

        .search-section { width: 85%; background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0px 2px 5px rgba(0,0,0,0.1); display: flex; justify-content: center; gap: 10px; }
        .search-section input { padding: 8px; width: 250px; border: 1px solid #ccc; border-radius: 5px; }
        .search-section button { width: auto; padding: 8px 20px; background-color: #4da6ff; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;}

        .content-layout { display: flex; width: 95%; gap: 20px; justify-content: center; }

        .form-container{ width: 30%; background:white; padding:25px; border-radius:10px; box-shadow:0px 2px 5px rgba(0,0,0,0.1); text-align:left; height: fit-content; }
        label{ font-weight:bold; font-size: 14px; display: block; margin-top: 10px; }
        input, select, textarea{ width:100%; padding:10px; margin-top:5px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px; box-sizing: border-box; }

        .btn-update { background-color:#4CAF50; color:white; border:none; padding:12px; border-radius:5px; cursor:pointer; width:100%; font-size:16px; font-weight: bold; }
        .btn-update:hover{ background-color:#45a049; }

        .table-container { width: 65%; background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 2px 5px rgba(0,0,0,0.1); }
        table { width:100%; border-collapse:collapse; font-size: 13px; }
        th, td { border:1px solid #ddd; padding:10px; text-align: center; }
        th { background-color:#eef7ff; }
        
        .status-badge { font-weight: bold; padding: 4px 8px; border-radius: 4px; }
        .Pending { color: orange; }
        .Approved { color: green; }
        .Rejected { color: red; }
        .Settled { color: blue; }

        footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Insurance Department - Claims Management</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.html'">Logout</button>
</header>

<div class="main-container">
    
    <div class="search-section">
        <form action="UI.php" method="GET" style="display:flex; gap:10px;">
            <input type="number" name="search_id" placeholder="Search by Claim ID..." value="<?php echo htmlspecialchars($search_id); ?>">
            <button type="submit">Search</button>
            <button type="button" onclick="window.location.href='UI.php'" style="background-color:#6c757d; color:white; border:none; border-radius:5px; padding:8px 20px; cursor:pointer;">Reset</button>
        </form>
    </div>

    <div class="content-layout">
        
        <div class="form-container">
            <h2>Update Claim</h2>
            <form action="update_logic.php" method="POST">
                <input type="hidden" name="Officer_ID" value="<?php echo $officer_id; ?>">

                <label>Claim ID (To Update)</label>
                <input type="number" name="claim_id" placeholder="e.g. 601" required>

                <label>Update Status</label>
                <select name="new_status">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Settled">Settled</option>
                </select>

                <label>Settlement Amount ($)</label>
                <input type="number" step="0.01" name="settlement_amount" placeholder="0.00">

                <label>Update Description / Remarks</label>
                <textarea name="remarks" rows="3" placeholder="Enter notes here..."></textarea>

                <button type="submit" name="btn_update_claim" class="btn-update">Update Record</button>
            </form>
        </div>

        <div class="table-container">
            <h2>Live Claims Records</h2>
            <table>
                <thead>
                    <tr>
                        <th>Claim ID</th>
                        <th>Patient ID</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Dynamically generate table rows from database result
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row["Claim_ID"] . "</td>
                                    <td>" . $row["Patient_ID"] . "</td>
                                    <td>$" . number_format($row["Amount"], 2) . "</td>
                                    <td>" . $row["date"] . "</td>
                                    <td><span class='status-badge " . $row["Status"] . "'>" . $row["Status"] . "</span></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>