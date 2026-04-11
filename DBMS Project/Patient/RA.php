<?php
session_start();
include "../db.php"; // Make sure this path correctly points to your database connection file

// 1. Check if the patient is logged in
if (!isset($_SESSION['Patient_ID'])) {
    $_SESSION['Patient_ID'] = 1001;
    echo "<h2 style='text-align:center; margin-top:50px; font-family: Arial;'>Please <a href='../login.php'>log in</a> to request an appointment.</h2>";
    exit;
}

$patient_id = $_SESSION['Patient_ID'];
$message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $conn->real_escape_string($_POST['Doctor_ID']);
    $date = $conn->real_escape_string($_POST['date']);
    $time = $conn->real_escape_string($_POST['time']);
    $reason = $conn->real_escape_string($_POST['reason']);
    $status = $conn->real_escape_string($_POST['status']); 

    $time_val = !empty($time) ? "'$time'" : "NULL";
    $reason_val = !empty($reason) ? "'$reason'" : "NULL";

    // 3. Insert into Database
    $sql = "INSERT INTO appointment (Doctor_ID, Patient_ID, status, reason, date, time, Manager_ID) 
            VALUES ('$doctor_id', '$patient_id', '$status', $reason_val, '$date', $time_val, NULL)";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert success'>Appointment request submitted successfully!</div>";
    } else {
        $message = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}

// 4. Fetch Doctors AND their Specialization for the Dropdown Menu
// Added 'Specialization' to the SELECT query
$doctors_result = $conn->query("SELECT Doctor_ID, Doctor_Name, Specialization FROM doctor");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Request Appointment</title>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f2f6f8;
    margin: 0;
    padding: 0;
    text-align: center;
}

header {
    background-color: #d6ecff;
    padding: 20px;
}
header h1 { margin: 0; }
header h3 { margin: 5px 0; color: #555; }
.logout {
            position: absolute; 
            right: 20px;     
            top: 50%;       
            transform: translateY(-50%);
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

.form-container {
    width: 400px;
    margin: 40px auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0px 2px 5px rgba(0,0,0,0.1);
    text-align: left;
}

label { font-weight: bold; display: block; margin-top: 10px; }

input, select, textarea {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 15px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box; 
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    background-color: #4da6ff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    margin-top: 10px;
}
button:hover { background-color: #3399ff; }

.alert { padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; text-align: center; }
.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

footer {
    background: #e8eef2;
    padding: 15px;
    margin-top: 40px;
    font-size: 14px;
    color: #444;
}
</style>
</head>

<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Request Appointment</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<div class="form-container">

    <?php echo $message; ?>

    <form action="" method="POST">

        <input type="hidden" name="status" value="Pending">
        <input type="hidden" name="Manager_ID" value="">

        <label>Select Doctor</label>
        <select name="Doctor_ID" required>
            <option value="">-- Choose a Doctor --</option>
            <?php 
            if ($doctors_result && $doctors_result->num_rows > 0) {
                while($doc = $doctors_result->fetch_assoc()) {
                    // Added ($doc['Specialization']) inside the option string
                    echo "<option value='" . $doc['Doctor_ID'] . "'>Dr. " . $doc['Doctor_Name'] . " (" . $doc['Specialization'] . ")</option>";
                }
            } else {
                echo "<option value=''>No doctors available</option>";
            }
            ?>
        </select>

        <label>Appointment Date</label>
        <input type="date" name="date" required min="<?php echo date('Y-m-d'); ?>">

        <label>Preferred Time</label>
        <input type="time" name="time">

        <label>Reason for Visit</label>
        <textarea name="reason" rows="3" placeholder="Describe your problem..."></textarea>

        <button type="submit">Submit Appointment Request</button>

    </form>

</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>