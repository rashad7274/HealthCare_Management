<?php 
session_start();
include "../db.php"; 

$doctor_list = $conn->query("SELECT Doctor_ID, Doctor_Name FROM doctor");

// 2. Initialize Filter Variables
$where_clauses = [];
$doctor_filter = $_GET['doctor_id'] ?? '';
$date_filter = $_GET['appt_date'] ?? '';
$id_filter = $_GET['appt_id'] ?? '';

// 3. Build Dynamic Query
if (!empty($doctor_filter)) {
    $where_clauses[] = "appointment.Doctor_ID = '" . $conn->real_escape_string($doctor_filter) . "'";
}
if (!empty($date_filter)) {
    $where_clauses[] = "appointment.date = '" . $conn->real_escape_string($date_filter) . "'";
}
if (!empty($id_filter)) {
    $where_clauses[] = "appointment.appointment_id = '" . $conn->real_escape_string($id_filter) . "'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = " WHERE " . implode(" AND ", $where_clauses);
}

$sql = "SELECT 
            appointment.appointment_id,
            patient.Patient_Name, 
            patient.Phone_Number AS Patient_Phone, 
            doctor.Doctor_Name, 
            doctor.Phone_Number AS Doctor_Phone, 
            appointment.date, 
            appointment.time, 
            appointment.status 
        FROM appointment
        JOIN patient ON appointment.Patient_ID = patient.Patient_ID
        JOIN doctor ON appointment.Doctor_ID = doctor.Doctor_ID
        $where_sql
        ORDER BY appointment.date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Management</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        
        header {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            color: white;
            padding: 25px 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .filter-container {
            width: 95%;
            max-width: 1100px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
            align-items: flex-end;
        }

        .filter-group { display: flex; flex-direction: column; text-align: left; }
        .filter-group label { font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #555; }
        .filter-group input, .filter-group select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }

        .btn-filter { padding: 8px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-reset { padding: 8px 15px; background: #e2e8f0; color: #333; text-decoration: none; border-radius: 4px; font-size: 14px; }

        table {
            width: 95%;
            max-width: 1150px;
            margin: 10px auto 40px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        th, td { padding: 12px; border: 1px solid #edf2f7; text-align: center; }
        th { background: #e2e8f0; color: #2d3748; font-size: 12px; text-transform: uppercase; }
        tr:hover { background-color: #f1f5f9; }

        footer { text-align: center; padding: 20px; background: #2d3748; color: #a0aec0; margin-top: 30px; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <p>Appointment Administration Panel</p>
</header>

<div class="filter-container">
    <form method="GET" style="display:contents;">
        <div class="filter-group">
            <label>Appointment ID</label>
            <input type="number" name="appt_id" value="<?php echo htmlspecialchars($id_filter); ?>" placeholder="Ex: 101">
        </div>

        <div class="filter-group">
            <label>Doctor Name</label>
            <select name="doctor_id">
                <option value="">All Doctors</option>
                <?php while($doc = $doctor_list->fetch_assoc()): ?>
                    <option value="<?php echo $doc['Doctor_ID']; ?>" <?php echo ($doctor_filter == $doc['Doctor_ID']) ? 'selected' : ''; ?>>
                        Dr. <?php echo $doc['Doctor_Name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="filter-group">
            <label>Date</label>
            <input type="date" name="appt_date" value="<?php echo htmlspecialchars($date_filter); ?>">
        </div>

        <button type="submit" class="btn-filter">Search</button>
        <a href="ViewAppointments.php" class="btn-reset">Clear All</a>
    </form>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Patient Name</th>
            <th>Patient Phone</th>
            <th>Doctor</th>
            <th>Doctor Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td><strong>" . $row['appointment_id'] . "</strong></td>";
                echo "<td>" . $row['Patient_Name'] . "</td>";
                echo "<td>" . $row['Patient_Phone'] . "</td>";
                echo "<td>Dr. " . $row['Doctor_Name'] . "</td>";
                echo "<td>" . $row['Doctor_Phone'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['time'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No records found matching your search.</td></tr>";
        }
        ?>
    </tbody>
</table>

<footer>
    Smart Healthcare Management System © 2026
</footer>

</body>
</html>