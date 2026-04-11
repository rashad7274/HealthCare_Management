<?php
session_start();
include "db.php"; 

if (isset($_POST['login_btn'])) {
    $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $password = $_POST['password']; // In a real app, use password_verify() with hashed passwords

    // Check Accountant table
    $query_acc = "SELECT * FROM accountant WHERE Accountant_ID = '$user_id' AND password = '$password'";
    $res_acc = $conn->query($query_acc);

    // Check Insurance Officer table
    $query_off = "SELECT * FROM insuranceofficer WHERE iOfficerID = '$user_id' AND password = '$password'";
    $res_off = $conn->query($query_off);

    if ($res_acc->num_rows > 0 || $res_off->num_rows > 0) {
        // Authentication successful
        $_SESSION['user_id'] = $user_id;
        header("Location: choice.php"); // Send them to the dashboard selection page
        exit();
    } else {
        // Authentication failed
        echo "<script>alert('Invalid ID or Password'); window.location.href='login.php';</script>";
    }
}
?>