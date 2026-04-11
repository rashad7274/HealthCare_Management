<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Smart Healthcare Management System</title>

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
    position:relative;
}

header h1{
    margin:0;
}

header h3{
    margin:5px 0;
    color:#555;
}

.logout{
    position:absolute;
    right:20px;
    top:20px;
    padding:8px 15px;
    border:none;
    background-color:#ff6b6b;
    color:white;
    border-radius:5px;
    cursor:pointer;
}

.welcome{
    margin-top:30px;
}

.welcome p{
    color:#555;
}

.cards{
    display:flex;
    justify-content:center;
    gap:20px;
    margin-top:30px;
}

.card{
    background:white;
    width:200px;
    padding:20px;
    border-radius:10px;
    box-shadow:0px 2px 5px rgba(0,0,0,0.1);
    cursor:pointer;
    transition:0.3s;
}

.card:hover{
    transform:scale(1.05);
    background:#eef7ff;
}

.health-tip{
    margin:40px auto;
    width:60%;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0px 2px 5px rgba(0,0,0,0.1);
}

footer{
    background:#e8eef2;
    padding:15px;
    margin-top:40px;
    font-size:14px;
    color:#444;
}

</style>
</head>

<body>

<header>
<h1>Smart Healthcare Management System</h1>
<h3>Patient Dashboard</h3>
<button class="logout" onclick="window.location.href='../SystemAccess.html'">Logout</button>
</header>

<section class="welcome">
<h2>Welcome, Patient!</h2>
<p>Manage your appointments, medical records, and symptoms here.</p>
</section>

<section class="cards">

<a href="RequestAppointment.php">
<div class="card">
<h3>Request Appointment</h3>
<p>Book a visit with a doctor.</p>
</div>
</a>

<a href="ViewMedicalRecords.php">
<div class="card">
<h3>View Medical Records</h3>
<p>Check your diagnosis and prescriptions.</p>
</div>
</a>

<a href="LogSymptoms.php">
<div class="card">
<h3>Log Symptoms</h3>
<p>Record your current symptoms.</p>
</div>
</a>

</section>

<section class="health-tip">
<h3>Daily Health Tip</h3>
<p>Drink enough water and get at least 7–8 hours of sleep.</p>
</section>

<footer>
Smart Healthcare Management System © 2026
</footer>

</body>
</html>