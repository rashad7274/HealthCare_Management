<?php 
session_start();
include "../db.php"; 

// --- 1. HANDLE UPDATE LOGIC ---
$update_msg = "";
if (isset($_POST['btn_update'])) {
    $app_id = $conn->real_escape_string($_POST['app_id']);
    $new_status = $conn->real_escape_string($_POST['new_status']);
    $reason = $conn->real_escape_string($_POST['reason']);
    $new_date = $conn->real_escape_string($_POST['new_date']);
    $new_time = $conn->real_escape_string($_POST['new_time']);

    $update_fields = "status = '$new_status', reason = '$reason'";
    if (!empty($new_date)) { $update_fields .= ", date = '$new_date'"; }
    if (!empty($new_time)) { $update_fields .= ", time = '$new_time'"; }

    $update_query = "UPDATE appointment SET $update_fields WHERE appointment_id = '$app_id'";
    
    if ($conn->query($update_query)) {
        $update_msg = "<div class='alert success'>Appointment #$app_id updated successfully!</div>";
    } else {
        $update_msg = "<div class='alert error'>Error: " . $conn->error . "</div>";
    }
}

// --- 2. PREPARE FILTERS ---
$filter_doctor = $_GET['filter_doctor'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$search_id = $_GET['search_id'] ?? '';

$where_clauses = [];
if (!empty($filter_doctor)) { $where_clauses[] = "appointment.Doctor_ID = '$filter_doctor'"; }
if (!empty($filter_status)) { $where_clauses[] = "appointment.status = '$filter_status'"; }
if (!empty($search_id)) { 
    $where_clauses[] = "(appointment.appointment_id LIKE '%$search_id%' OR patient.Patient_Name LIKE '%$search_id%')"; 
}

$where_sql = count($where_clauses) > 0 ? " WHERE " . implode(" AND ", $where_clauses) : "";

// --- 3. FETCH DATA ---
$doctors = $conn->query("SELECT Doctor_ID, Doctor_Name FROM doctor");
$sql = "SELECT appointment.*, doctor.Doctor_Name, patient.Patient_Name 
        FROM appointment 
        JOIN doctor ON appointment.Doctor_ID = doctor.Doctor_ID 
        JOIN patient ON appointment.Patient_ID = patient.Patient_ID 
        $where_sql 
        ORDER BY appointment.date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Management Board</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f8; color: #333; margin: 0; padding: 0; }
        header { background-color: #4A90E2; padding: 20px; color: white; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .main-content { width: 95%; margin: 20px auto; display: flex; flex-direction: column; gap: 20px; }

        /* Filter Section */
        .filter-card { background: white; padding: 15px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; justify-content: center; gap: 15px; align-items: flex-end; }
        .filter-group-inline { display: flex; flex-direction: column; gap: 5px; }
        .filter-group-inline label { font-size: 11px; font-weight: bold; color: #7f8c8d; text-transform: uppercase; }
        .filter-card input, .filter-card select { padding: 8px; border: 1px solid #ddd; border-radius: 6px; }

        /* Equal Size Layout Container */
        .content-layout { 
            display: flex; 
            gap: 20px; 
            align-items: stretch; /* This forces both children to be the same height */
        }

        /* Forms and Tables shared height */
        .node-container { 
            background: white; 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
            display: flex;
            flex-direction: column;
        }

        .update-container { width: 30%; padding: 25px; }
        .table-container { width: 70%; overflow: hidden; }

        /* Scrolling Table Body to keep node size fixed */
        .table-wrapper { overflow-y: auto; max-height: 500px; } 

        h3 { margin-top: 0; color: #2c3e50; border-bottom: 2px solid #f4f6f8; padding-bottom: 10px; }
        label { font-weight: bold; font-size: 13px; display: block; margin-top: 12px; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f8f9fa; color: #7f8c8d; text-transform: uppercase; font-size: 11px; padding: 14px; position: sticky; top: 0; }
        td { padding: 14px; text-align: left; border-bottom: 1px solid #eee; font-size: 13px; }

        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .Approved { background: #d4edda; color: #155724; }
        .Pending { background: #fff3cd; color: #856404; }
        .Cancelled { background: #f8d7da; color: #721c24; }

        .alert { padding: 10px; border-radius: 6px; margin-bottom: 15px; font-weight: bold; text-align: center; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <p>Appointment Administration Portal</p>
</header>

<div class="main-content">
    <div class="filter-card">
        <form action="" method="GET" style="display: flex; gap: 15px; align-items: flex-end;">
            <div class="filter-group-inline">
                <label>Search Identity</label>
                <input type="text" name="search_id" value="<?php echo htmlspecialchars($search_id); ?>" placeholder="ID or Name">
            </div>
            <div class="filter-group-inline">
                <label>Doctor Name</label>
                <select name="filter_doctor">
                    <option value="">All Doctors</option>
                    <?php while($doc = $doctors->fetch_assoc()): ?>
                        <option value="<?php echo $doc['Doctor_ID']; ?>" <?php if($filter_doctor == $doc['Doctor_ID']) echo 'selected'; ?>>
                            Dr. <?php echo $doc['Doctor_Name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="filter-group-inline">
                <label>Status</label>
                <select name="filter_status">
                    <option value="">All Statuses</option>
                    <option value="Pending" <?php if($filter_status == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="Approved" <?php if($filter_status == 'Approved') echo 'selected'; ?>>Approved</option>
                    <option value="Cancelled" <?php if($filter_status == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" style="background:#4A90E2; color:white; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:bold;">Filter</button>
        </form>
    </div>

    <div class="content-layout">
        <div class="node-container update-container">
            <h3>Update Action</h3>
            <?php echo $update_msg; ?>
            <form action="" method="POST">
                <label>Appointment ID</label>
                <input type="text" name="app_id" placeholder="Enter ID" required>
                
                <label>Status</label>
                <select name="new_status">
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Cancelled">Cancelled</option>
                </select>

                <label>Reason / Notes</label>
                <textarea name="reason" rows="2"></textarea>

                <label>Reschedule Date</label>
                <input type="date" name="new_date">

                <label>Reschedule Time</label>
                <input type="time" name="new_time">

                <button type="submit" name="btn_update" style="width:100%; background:#27ae60; color:white; border:none; padding:12px; border-radius:6px; margin-top:15px; cursor:pointer; font-weight:bold;">Commit Changes</button>
            </form>
        </div>

        <div class="node-container table-container">
            <div style="padding: 20px 25px 0 25px;"><h3>Record Registry</h3></div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Doctor</th>
                            <th>Patient</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['appointment_id']; ?></strong></td>
                                    <td>Dr. <?php echo $row['Doctor_Name']; ?></td>
                                    <td><?php echo $row['Patient_Name']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo date("g:i A", strtotime($row['time'])); ?></td>
                                    <td><span class="status-badge <?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" style="text-align:center;">No records match your criteria.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>