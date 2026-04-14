<?php
session_start();
include "../db.php"; // Ensure this path correctly points to your database connection file

// 1. Calculate Total Revenue (Sum of all 'Paid' invoices)
// Based on schema: amount column in invoice table
$revenue_query = "SELECT SUM(amount) AS total_rev FROM invoice WHERE status = 'Paid'";
$revenue_res = $conn->query($revenue_query);
$revenue_data = $revenue_res->fetch_assoc();
$total_revenue = $revenue_data['total_rev'] ?? 0;

// 2. Calculate Pending Invoices (Count of 'Due' invoices)
// Based on schema: status column in invoice table
$pending_query = "SELECT COUNT(*) AS total_pending FROM invoice WHERE status = 'Due'";
$pending_res = $conn->query($pending_query);
$pending_data = $pending_res->fetch_assoc();
$total_pending = $pending_data['total_pending'] ?? 0;

// 3. Calculate Today's Payments (Sum of 'Paid' invoices for the current date)
// Based on schema: date column (timestamp) in invoice table
$today = date('Y-m-d');
$today_query = "SELECT SUM(amount) AS today_rev FROM invoice WHERE status = 'Paid' AND DATE(date) = '$today'";
$today_res = $conn->query($today_query);
$today_data = $today_res->fetch_assoc();
$today_payments = $today_data['today_rev'] ?? 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Financial Overview</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f2f6f8; margin: 0; padding: 0; text-align: center; }
        header { background-color: #d6ecff; padding: 20px; position: relative; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        header h1 { margin: 0; font-size: 24px; }
        header h3 { margin: 5px 0; color: #555; font-size: 16px; }

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
        .logout:hover { background-color: #fa5252; }

        .cards { display: flex; justify-content: center; gap: 20px; margin-top: 50px; flex-wrap: wrap; }
        .card { background: white; width: 220px; padding: 25px; border-radius: 12px; box-shadow: 0px 4px 15px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin-bottom: 10px; color: #7f8c8d; font-size: 14px; text-transform: uppercase; }
        .card p { font-size: 26px; font-weight: bold; color: #2c3e50; margin: 0; }

        footer { background: #e8eef2; padding: 15px; margin-top: 60px; font-size: 14px; color: #444; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Financial Overview</h3>
    <button class="logout" onclick="window.location.href='../SystemAccess.php'">Logout</button>
</header>

<section class="cards">
    <div class="card">
        <h3>Total Revenue</h3>
        <p>$<?php echo number_format($total_revenue, 2); ?></p>
    </div>

    <div class="card">
        <h3>Pending Invoices</h3>
        <p><?php echo $total_pending; ?></p>
    </div>

    <div class="card">
        <h3>Today's Payments</h3>
        <p>$<?php echo number_format($today_payments, 2); ?></p>
    </div>
</section>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>