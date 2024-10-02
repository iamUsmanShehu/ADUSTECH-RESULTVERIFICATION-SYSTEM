<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_reg = mysqli_real_escape_string($connection, $_POST['student_reg']);
    
    $query = "SELECT * FROM results WHERE student_reg = '$student_reg' AND verification_status = 'Verified'";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        header("Location: result.php?student_reg=" . $row['student_reg']);
        exit;
    } else {
        echo "No verified result found for this registration number.";
    }
}
?>
