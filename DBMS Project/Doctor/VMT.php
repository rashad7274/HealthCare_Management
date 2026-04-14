<?php
session_start();
//$_SESSION['Doctor_ID'] = '2001';//for test
include "../db.php"; // Ensure this points to your DB connection file

// 1. Check if the Doctor is logged in
if (!isset($_SESSION['Doctor_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../SystemAccess.html'>log in</a> to view patient test records.</h2>";
    exit;
}

$doctor_id = $_SESSION['Doctor_ID'];

// 2. Handle Search Filter
$search_query = "";
if (isset($_GET['search_test_id']) && !empty(trim($_GET['search_test_id']))) {
    $search_id = $conn->real_escape_string($_GET['search_test_id']);
    // Ensure 'test_id' matches your actual database column name
    $search_query = " AND test_id = '$search_id'"; 
}

// 3. Fetch only the tests ordered by THIS doctor
// IMPORTANT: Change 'medicalreport' to your actual table name (e.g., 'medicaltest')
$sql = "SELECT * FROM medicaltest WHERE Doctor_ID = '$doctor_id' $search_query";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Medical Tests</title>

<style>
    body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
    
    /* Added Logout Button Styling to match your previous pages */
    header{ background-color:#d6ecff; padding:20px; position: relative; }
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
    .test-container{
        width:90%;
        margin:40px auto;
        background:white;
        padding:25px;
        border-radius:10px;
        box-shadow:0px 2px 5px rgba(0,0,0,0.1);
    }

    /* Search Bar Styling */
    .search-box {
        margin-bottom: 25px;
        background: #eef7ff;
        padding: 15px;
        border-radius: 8px;
        display: flex;
        justify-content: center;
        gap: 10px;
        align-items: center;
    }

    .search-box input {
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        width: 250px;
    }

    .search-box button {
        padding: 8px 20px;
        background-color: #4da6ff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    .search-box button:hover { background-color: #3399ff; }

    table{ width:100%; border-collapse:collapse; margin-top:20px; font-size: 14px;}
    th, td{ border:1px solid #ddd; padding:12px 8px; text-align: center; }
    th{ background-color:#eef7ff; }
    tr:hover{ background-color:#f5f5f5; }

    .completed{ color:green; font-weight:bold; }
    .pending{ color:orange; font-weight:bold; }
    
    .file-link { color: #4da6ff; text-decoration: none; font-weight: bold; }

    footer{ background:#e8eef2; padding:15px; margin-top:40px; font-size:14px; color:#444; }
</style>
</head>

<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Medical Investigation Department</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.html'">Logout</button>
</header>

<div class="test-container">
    <h2>Patient Test Records</h2>

    <div class="search-box">
        <form action="" method="GET" style="display:flex; gap:10px;">
            <label for="search_id">Search by Test ID:</label>
            <input type="text" name="search_test_id" id="search_id" placeholder="Enter Test ID" value="<?php echo isset($_GET['search_test_id']) ? htmlspecialchars($_GET['search_test_id']) : ''; ?>">
            <button type="submit">Filter Results</button>
            <a href="?" style="padding: 8px 15px; background: #ccc; color: black; text-decoration: none; border-radius: 4px; font-weight: bold;">Clear</a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Test ID</th>
                <th>Patient ID</th>
                <th>Doctor ID</th>
                <th>Test Type</th>
                <th>Result Details</th>
                <th>Report File</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    
                    $status = $row['Result_Status'];
                    $status_class = (strtolower($status) == 'completed') ? 'completed' : 'pending';
                    
                    // Display file link only if it exists
                    $report_file = $row['Report_File_Path'];
                    $file_link = !empty($report_file) ? "<a href='" . htmlspecialchars($report_file) . "' class='file-link' target='_blank'>View File</a>" : "---";

                    // Handle empty Result Details
                    $result_details = !empty($row['Result_Details']) ? $row['Result_Details'] : "Awaiting Analysis";

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['test_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Patient_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Doctor_ID']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Test_Type']) . "</td>";
                    echo "<td>" . htmlspecialchars($result_details) . "</td>";
                    echo "<td>" . $file_link . "</td>";
                    echo "<td><span class='$status_class'>" . htmlspecialchars($status) . "</span></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No test records found.</td></tr>";
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