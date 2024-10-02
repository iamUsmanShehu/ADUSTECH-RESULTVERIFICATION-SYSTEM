
<?php
require_once '../includes/db.php';
?>
<style type="text/css">
    .result_container{
        border-radius: 50px;
        background: #ffffff;
        box-shadow:  20px -20px 60px #bebebe,
                     -20px 20px 60px #ffffff;
        margin: auto;
        margin-top: 200px;
        width: 70%;
        padding: 40px;
    }
</style>
<div class="result_container">
    <?php
        if (isset($_GET['student_reg'])) {
            $student_reg = mysqli_real_escape_string($connection, $_GET['student_reg']);
            
            $query = "SELECT * FROM results WHERE student_reg = '$student_reg'";
            $result = mysqli_query($connection, $query);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                echo "<h1>Result for " . $row['name'] . "</h1>";
                echo "<p>Department: " . $row['department'] . "</p>";
                echo "<p>Final Result: " . $row['final_result'] . "</p>";
                echo "<p>Issue Date: " . $row['issue_date'] . "</p>";
                echo "<p>Status: " . $row['verification_status'] . "</p>";

                // Display QR code
                if (!empty($row['qr_code'])) {
                    echo "<img src='../" . $row['qr_code'] . "' alt='QR Code' />";
                }
            } else {
                echo "Result not found.";
            }
        } else {
            echo "Invalid request.";
        }
    ?>
</div>
