<?php 
include "../db.php";

$searchFilter = "";
if (isset($_GET['search_test_id']) && $_GET['search_test_id'] != "") {
    $search_id = $conn->real_escape_string($_GET['search_test_id']);
    $searchFilter = " WHERE test_ID = '$search_id'";
}

$sql = "SELECT test_ID, Patient_ID, Doctor_ID, Test_Type, Result_Details, Report_File_Path, Result_Status 
        FROM medicaltest" . $searchFilter;

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Medical Tests</title>

<style>
    body{ font-family: Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
    header{ background-color:#d6ecff; padding:20px; }
    header h1{ margin:0; }
    header h3{ margin:5px 0; color:#555; }

    .test-container{
        width:90%;
        margin:40px auto;
        background:white;
        padding:25px;
        border-radius:10px;
        box-shadow:0px 2px 5px rgba(0,0,0,0.1);
    }

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
    .search-box input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 250px; }
    .search-box button { padding: 8px 20px; background-color: #4da6ff; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
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
</header>

<div class="test-container">
    <h2>Patient Test Records</h2>

    <div class="search-box">
        <form action="" method="GET" style="display:flex; gap:10px;">
            <label for="search_id">Search by Test ID:</label>
            <input type="text" name="search_test_id" id="search_id" placeholder="Enter Test ID (e.g. 301)">
            <button type="submit">Filter Results</button>
            <a href="VMT.php" style="padding: 8px 15px; background: #ccc; color: black; text-decoration: none; border-radius: 4px;">Clear</a>
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
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    
                    if ($row['Result_Status'] == 'Done') {
                        $statusClass = 'completed';
                    } else {
                        $statusClass = 'pending';
                    }

                    if (!empty($row['Report_File_Path'])) {
                        $fileLink = "<a href='" . $row['Report_File_Path'] . "' class='file-link' target='_blank'>View PDF</a>";
                    } else {
                        $fileLink = "---";
                    }
            ?>
            <tr>
                <td><?php echo $row['test_ID']; ?></td>
                <td><?php echo $row['Patient_ID']; ?></td>
                <td><?php echo $row['Doctor_ID']; ?></td>
                <td><?php echo $row['Test_Type']; ?></td>
                <td><?php echo $row['Result_Details']; ?></td>
                <td><?php echo $fileLink; ?></td>
                <td><span class="<?php echo $statusClass; ?>"><?php echo $row['Result_Status']; ?></span></td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='7'>No medical tests found.</td></tr>";
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