<?php
session_start();
//$_SESSION['Manager_ID'] = '4001';//for test

include "../db.php"; // Make sure this path correctly points to your database connection file

if (!isset($_SESSION['Manager_ID'])) {
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../SystemAccess.html'>log in</a> as a Manager to access this page.</h2>";
    exit;
}

$manager_id = $_SESSION['Manager_ID'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs to prevent SQL injection
    $patient_id = $conn->real_escape_string($_POST['Patient_ID']);
    $doctor_id = $conn->real_escape_string($_POST['Doctor_ID']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $reason = $conn->real_escape_string($_POST['reason']);
    $status = $conn->real_escape_string($_POST['status']);

    // Handle optional fields (set to NULL if empty)
    $time_val = !empty($time) ? "'$time'" : "NULL";
    $reason_val = !empty($reason) ? "'$reason'" : "NULL";

 
    $sql = "INSERT INTO appointment (Doctor_ID, Patient_ID, status, reason, date, time, Manager_ID) 
            VALUES ('$doctor_id', '$patient_id', '$status', $reason_val, '$date', $time_val, '$manager_id')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert success'>Appointment added successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Schedule</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; text-align: center; }
        
        /* Restored Header Background */
        header {
            background-color: #d6ecff;
            padding: 20px;
            position: relative;
            margin-bottom: 30px;
        }

        header h1 { margin: 0; }
        header h3 { margin: 5px 0; color: #555; }

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

        .form-box {
            width: 400px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
            text-align: left;
        }

        label { font-weight: bold; display: block; margin-top: 10px; color: #333; }

        input, select, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button[type="submit"] {
            background: #4da6ff;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: background 0.3s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: #3399ff;
        }

        /* Alerts */
        .alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; text-align: center; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        footer {
            text-align: center;
            padding: 20px;
            background: #e8eef2;
            margin-top: 50px;
            font-size: 14px;
            color: #444;
        }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Create New Appointment</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="form-box">
    
    <?php echo $message; ?>

    <form action="" method="POST">

        <label for="p_id">Patient ID</label>
        <input type="number" id="p_id" name="Patient_ID" placeholder="Enter Patient ID" required>

        <label for="d_id">Doctor ID</label>
        <input type="number" id="d_id" name="Doctor_ID" placeholder="Enter Doctor ID (e.g. 2001)" required>

        <label for="app_date">Appointment Date</label>
        <input type="date" id="app_date" name="date" required min="<?php echo date('Y-m-d'); ?>">

        <label for="app_time">Appointment Time</label>
        <input type="time" id="app_time" name="time">

        <label for="reason">Reason for Visit</label>
        <input type="text" id="reason" name="reason" placeholder="e.g. Regular Checkup, Fever, etc.">

        <label for="status">Appointment Status</label>
        <select id="status" name="status" required>
            <option value="Confirmed">Confirmed</option>
            <option value="Pending">Pending</option>
            <option value="Completed">Completed</option>
        </select>

        <button type="submit" name="add_schedule">Add Appointment</button>
    </form>
</div>

<footer>
    Smart Healthcare Management System © 2026 | Project Management Portal
</footer>

</body>
</html>