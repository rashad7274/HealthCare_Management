<?php
session_start();

// Connect to your database
include "db.php"; 

$error_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Grab the inputs from the HTML form below
    $username_id = $conn->real_escape_string($_POST['username']); 
    $password = $_POST['password']; 
    $role = $_POST['role'];

    // 1. Check for the dummy password
    if ($password !== "123") {
        $error_message = "Invalid password. (Hint: use 123 for testing)";
    } else {
        // 2. Navigate based on the Combobox selected text
        if ($role === "Doctor") {
            $sql = "SELECT * FROM doctor WHERE Doctor_ID = '$username_id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $_SESSION['Doctor_ID'] = $username_id;
                header("Location: Doctor/Doctor.html"); // Navigates to Doctor Dashboard
                exit;
            } else { $error_message = "Doctor ID not found."; }

        } elseif ($role === "Patient") {
            $sql = "SELECT * FROM patient WHERE Patient_ID = '$username_id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $_SESSION['Patient_ID'] = $username_id; 
                header("Location: Patient/Patient.html"); // Navigates to Patient Dashboard
                exit;
            } else { $error_message = "Patient ID not found."; }

        } elseif ($role === "Accountant") {
            $sql = "SELECT * FROM accountant WHERE Accountant_ID = '$username_id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $_SESSION['Accountant_ID'] = $username_id; 
                header("Location: Accountant/Accountant.html"); // Navigates to Accountant Dashboard
                exit;
            } else { $error_message = "Accountant ID not found."; }

        } elseif ($role === "Investigator") {
            $sql = "SELECT * FROM investigator WHERE Investigator_ID = '$username_id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $_SESSION['Investigator_ID'] = $username_id; 
                header("Location: Investigator/Investigator.html"); // Navigates to Investigator Dashboard
                exit;
            } else { $error_message = "Investigator ID not found."; }

        } elseif ($role === "Insurance") {
            $sql = "SELECT * FROM insuranceofficer WHERE iOfficerID = '$username_id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $_SESSION['iOfficerID'] = $username_id; 
                header("Location: Insurance/Insurance.html"); // Navigates to Insurance Dashboard
                exit;
            } else { $error_message = "Insurance Officer ID not found."; }

        } elseif ($role === "Appointment Manager") {
            $sql = "SELECT * FROM appointment_manager WHERE Appointment_Manager_ID = '$username_id'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $_SESSION['Appointment_Manager_ID'] = $username_id; 
                header("Location: AppointmentManager/AppointmentManager.html"); // Navigates to Appt Manager Dashboard
                exit;
            } else { $error_message = "Appointment Manager ID not found."; }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>System Access</title>

<style>
body{
    font-family: Arial, sans-serif;
    background-color:#f2f6f8;
    margin:0;
    padding:0;
    text-align:center;
}

header{
    background-color:#d6ecff;
    padding:20px;
}

header h1{ margin:0; }
header h3{ margin:5px 0; color:#555; }

.login-container{
    width:350px;
    margin:80px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0px 2px 5px rgba(0,0,0,0.1);
    text-align:left;
}

.login-container h3{
    text-align:center;
    margin-bottom:20px;
}

label{ font-weight:bold; }

input, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border-radius:5px;
    border:1px solid #ccc;
    box-sizing: border-box; /* Prevents overflow */
}

input:focus, select:focus{
    border-color:#4da6ff;
    outline:none;
}

button{
    width:100%;
    padding:10px;
    border:none;
    background-color:#4da6ff;
    color:white;
    border-radius:5px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background-color:#3399ff;
}

.extra-links{
    text-align:center;
    margin-top:10px;
    font-size:14px;
}

.extra-links a{
    text-decoration:none;
    color:#4da6ff;
}

.extra-links a:hover{ text-decoration:underline; }

footer{
    background:#e8eef2;
    padding:15px;
    margin-top:60px;
    font-size:14px;
    color:#444;
}

/* Error message styling */
.error-box { 
    background-color: #f8d7da; 
    color: #721c24; 
    padding: 10px; 
    border-radius: 5px; 
    border: 1px solid #f5c6cb; 
    margin-bottom: 15px; 
    text-align: center; 
    font-weight: bold;
}
</style>
</head>

<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>System Access (Login)</h3>
</header>

<div class="login-container">

    <h3>User Login</h3>

    <?php if(!empty($error_message)) { echo "<div class='error-box'>$error_message</div>"; } ?>

    <form action="" method="POST">

        <label>Username (Enter ID)</label>
        <input type="number" name="username" placeholder="e.g. 2001 or 1001" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <label>Select Role</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="Accountant">Accountant</option>
            <option value="Doctor">Doctor</option>
            <option value="Patient">Patient</option>
            <option value="Investigator">Investigator</option>
            <option value="Insurance">Insurance</option>
            <option value="Appointment Manager">Appointment Manager</option>
        </select>

        <button type="submit">Login</button>

    </form>

    <div class="extra-links">
        <p><a href="#">Forgot Password?</a></p>
    </div>

</div>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>