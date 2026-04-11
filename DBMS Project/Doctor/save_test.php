<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $patient_name = $_POST['patient_name'];
    $test_name = $_POST['test_name'];

    $sql = "INSERT INTO test_orders (patient_name, test_name)
            VALUES ('$patient_name', '$test_name')";

    if ($conn->query($sql) === TRUE) {
        echo "✅ Data inserted successfully";
        echo "<br><a href='OrderMedicalTest.php'>Go Back</a>";
    } else {
        echo "❌ Error: " . $conn->error;
    }
}
?>