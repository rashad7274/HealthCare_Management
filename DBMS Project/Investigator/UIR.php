<?php
// 1. Error reporting to diagnose the white screen
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Simulating Investigator Login
//$_SESSION['InvestigatorID'] = '5001'; 

// 2. Database Connection - Double check this path!
include "../db.php"; 

if (!isset($_SESSION['Investigator_ID'])) {
    die("<h2 style='text-align:center; margin-top:50px;'>Please log in first.</h2>");
}

$investigator_id = $_SESSION['Investigator_ID'];
$message = "";

// 3. Handle Update Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_test'])) {
    $test_id = $_POST['test_ID'];
    $status  = $_POST['Result_Status'];
    
    // Result Details can be null
    $details = !empty($_POST['Result_Details']) ? $_POST['Result_Details'] : null;
    
    // File Path/Blob can be null
    $file_data = null;
    if (isset($_FILES['Report_File_Path']) && $_FILES['Report_File_Path']['error'] == 0) {
        $file_data = file_get_contents($_FILES['Report_File_Path']['tmp_name']);
    }

    try {
        // SQL query allows NULLs for Path and Details
        $sql = "UPDATE medicaltest 
                SET Result_Status = ?, 
                    Report_File_Path = ?, 
                    Result_Details = ?, 
                    InvestigatorID = ? 
                WHERE test_ID = ?";

        $stmt = $conn->prepare($sql);
        
        // "sbssi" represents types: string, blob, string, string, integer
        // Send null for the blob if no file was uploaded
        $stmt->bind_param("sbssi", $status, $file_data, $details, $investigator_id, $test_id);

        if ($stmt->execute()) {
            $message = "<div style='color:green; padding:10px; border:1px solid green; border-radius:5px; margin-bottom:15px; background:#eaffea;'>Update Successful!</div>";
        } else {
            $message = "<div style='color:red;'>Database Error: " . $conn->error . "</div>";
        }
    } catch (Exception $e) {
        $message = "<div style='color:red;'>System Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Test Report</title>
    <style>
        body{ font-family: 'Segoe UI', Arial; background:#f2f6f8; margin:0; text-align:center; }
        header { background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 40px; }
       .logout {
    position: absolute; 
    right: 20px;     
    top: 20px; /* Changed from 50% to 20px to pin it to the top */
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
        .form-container { width: 400px; margin: -30px auto 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: left; }
        label { font-weight: bold; display: block; margin-top: 15px; color: #444; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        input[readonly] { background-color: #f9f9f9; color: #777; border-left: 4px solid #1e3c72; }
        button { background: #28a745; color: white; border: none; padding: 12px; width: 100%; margin-top: 25px; cursor: pointer; border-radius: 5px; font-weight: bold; font-size: 16px; }
        button:hover { background: #218838; }
        .optional { font-weight: normal; font-size: 12px; color: #888; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare System</h1>
    <p>Logged in as Investigator ID: <strong><?php echo $investigator_id; ?></strong></p>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
    
</header>

<div class="form-container">
    <?php echo $message; ?>
    
    <form action="" method="POST" enctype="multipart/form-data">
        
        <label>Medical Test ID</label>
            <input type="text" name="test_ID" value="" placeholder="Enter ID to update">

        <label>Result Status</label>
        <select name="Result_Status">
            <option value="Completed">Completed</option>
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
        </select>

        <label>Upload Report File <span class="optional">(Optional)</span></label>
        <input type="file" name="Report_File_Path">

        <label>Result Details <span class="optional">(Optional)</span></label>
        <textarea name="Result_Details" rows="4" placeholder="Enter findings if any..."></textarea>

        <button type="submit" name="update_test">Update Record</button>
    </form>
</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>