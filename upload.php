<?php
require_once 'includes/db.php';
require_once 'includes/phpqrcode/qrlib.php';  // Include the PHP QR Code library

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['result_file']) && $_FILES['result_file']['error'] == 0) {
        $filename = $_FILES['result_file']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if ($ext !== 'csv') {
            die("Error: Please upload a valid CSV file.");
        }

        if (($handle = fopen($_FILES['result_file']['tmp_name'], 'r')) !== FALSE) {
            fgetcsv($handle);

            $stmt = $connection->prepare("INSERT INTO results (student_reg, name, department, final_result, issue_date, verification_status, qr_code) VALUES (?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param('sssssss', $student_reg, $name, $department, $final_result, $issue_date, $verification_status, $qr_code_path);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $student_reg = $data[0];
                $name = $data[1];
                $department = $data[2];
                $final_result = $data[3];
                $issue_date = $data[4];
                $verification_status = $data[5];

                // Generate QR code
                $qr_code_data = "Student Reg: $student_reg\nName: $name\nDepartment: $department\nFinal Result: $final_result\nIssue Date: $issue_date";
                $qr_code_filename = 'qrcodes/' . $student_reg . '.png';
                QRcode::png($qr_code_data, $qr_code_filename);

                // Store the QR code path in the database
                $qr_code_path = $qr_code_filename;

                $stmt->execute();
            }

            $stmt->close();
            fclose($handle);

            echo "Results and QR codes uploaded successfully!";
        } else {
            die("Error: Unable to open the uploaded file.");
        }
    } else {
        echo "Error: " . $_FILES['result_file']['error'];
    }
} else {
    echo "Invalid request.";
}
?>
