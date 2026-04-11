<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
//$_SESSION['Doctor_ID'] = '2001'; // Simulated Login

include "../db.php"; 

if (!isset($_SESSION['Doctor_ID'])) {
    die("<h2 style='text-align:center; margin-top:50px;'>Please log in to continue.</h2>");
}

$doctor_id = $_SESSION['Doctor_ID'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_prescription'])) {
    
    // Capturing Report ID from the renamed input
    $report_id   = !empty($_POST['report_id']) ? $_POST['report_id'] : null;
    $patient_id  = $_POST['patient_id'];
    $diagnosis   = $_POST['diagnosis'];
    $treatment   = $_POST['treatment'];
    $notes       = $_POST['additional_Notes'];

    try {
        // record_id is omitted so the DB handles auto-increment automatically
        $sql = "INSERT INTO medical_record (report_ID, patient_id, doctor_id, diagnosis, treatment, additional_Notes) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        
        // "iiisss" -> report_id (i), patient_id (i), doctor_id (i), diagnosis (s), treatment (s), notes (s)
        $stmt->bind_param("iiisss", $report_id, $patient_id, $doctor_id, $diagnosis, $treatment, $notes);

        if ($stmt->execute()) {
            $message = "<div style='color:green; font-weight:bold; margin-bottom:15px; border:1px solid green; padding:10px; background:#f0fff0;'>Prescription Saved Successfully! (Report ID: $report_id)</div>";
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
    <title>Write Prescription</title>
    <style>
        body{ font-family: 'Segoe UI', Arial, sans-serif; background-color:#f2f6f8; margin:0; padding:0; text-align:center; }
        header { background: linear-gradient(135deg, #1e3c72, #2a5298); color: white; padding: 40px 20px; }
        header h1 { margin: 0; font-size: 26px; }
        .form-container { width: 450px; margin: -30px auto 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); text-align: left; }
        label { font-weight: bold; display: block; margin-top: 15px; color: #333; }
        input, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #cbd5e0; border-radius: 6px; box-sizing: border-box; }
        
        .id-section { background: #f9f9f9; padding: 15px; border-radius: 8px; border: 1px solid #e2e8f0; }
        
        button { width: 100%; padding: 14px; border: none; background-color: #4da6ff; color: white; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 25px; transition: 0.2s; }
        button:hover { background-color: #3399ff; }
        footer { background: #e8eef2; padding: 20px; font-size: 14px; color: #666; margin-top: 40px; }
        .optional { font-weight: normal; color: #888; font-size: 12px; }
    </style>
</head>
<body>

<header>
    <h1>Smart Healthcare Management System</h1>
    <h3>Write Prescription Record</h3>
</header>

<div class="form-container">
    <?php echo $message; ?>
    
    <form action="" method="POST">

        <div class="id-section">
            <label style="margin-top:0;">Report ID</label>
            <input type="number" name="report_id" placeholder="Enter Linked Report ID" required>

            <label>Patient ID</label>
            <input type="number" name="patient_id" placeholder="Enter Patient ID" required>
        </div>

        <label>Diagnosis</label>
        <textarea name="diagnosis" rows="3" placeholder="Clinical diagnosis..."></textarea>

        <label>Treatment / Medications</label>
        <textarea name="treatment" rows="4" placeholder="Prescribed medications and dosage..."></textarea>

        <label>Additional Notes <span class="optional">(Optional)</span></label>
        <textarea name="additional_Notes" rows="3" placeholder="Special instructions for the patient..."></textarea>

        <button type="submit" name="submit_prescription">Submit Prescription</button>

    </form>
</div>

<footer>Smart Healthcare Management System © 2026</footer>

</body>
</html>