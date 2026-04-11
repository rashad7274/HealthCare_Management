<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Department</title>
    <style>
        body { font-family: Arial; background: #f2f6f8; text-align: center; padding-top: 80px; }
        .header { margin-bottom: 50px; }
        .container { display: flex; justify-content: center; gap: 40px; }
        .box { 
            background: white; padding: 50px; width: 200px; border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); text-decoration: none; 
            color: #333; transition: 0.3s; 
        }
        .box:hover { transform: scale(1.05); background: #eef7ff; }
        .box h3 { color: #4da6ff; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Identity Verified</h1>
        <p>Select the dashboard you wish to manage today:</p>
    </div>

    <div class="container">
        <a href="SI_Invoice.php" class="box">
            <h3>Accountant</h3>
            <p>Billing & Invoices</p>
        </a>

        <a href="UI.php" class="box">
            <h3>Insurance</h3>
            <p>Claims & Status</p>
        </a>
    </div>

    <p style="margin-top: 50px;">
        <a href="logout.php" style="color: #ff6b6b; text-decoration: none; font-weight: bold;">Logout Session</a>
    </p>
</body>
</html>