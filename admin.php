
<?php
session_start();
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
}


  $results_id = "SELECT COUNT(id) AS 'Total_results' FROM `results`";
      $result_stmt = $connection->prepare($results_id);
      $result_stmt->execute();
      $result_result = $result_stmt->get_result();
      
      if ($result_result->num_rows > 0) {
          $total = $result_result->fetch_assoc();
          $total_results = $total['Total_results'];
      }


// Handle delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM results WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();

    // Redirect after delete
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Fetch results from the database
$sql = "SELECT id, student_reg, name, department, final_result, issue_date, verification_status, qr_code FROM results";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
        
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <link rel="stylesheet" href="styles.css" />
    <title>Admin - Upload Results</title>
    <style type="text/css">
    .btn-get-started {
    color: white;
    background: #008374;
    font-family: tahoma;
    font-weight: 500;
    font-size: 14px;
    letter-spacing: 1px;
    display: inline-block;
    padding: 14px 40px;
    border-radius: 50px;
    transition: 0.3s;
    border: 2px solid 
/*color-mix(in srgb, #008374a6, transparent 90%);*/
	margin-top: 5px;
	border: none;
	}
	.btn-get-started:hover {
		background: #008374b3
	}
    </style>
  </head>
  <body id="body">
    <div class="container">
      <nav class="navbar">
        <div class="nav_icon" onclick="toggleSidebar()">
          <i class="fa fa-bars" aria-hidden="true"></i>
        </div>
        <div class="navbar__left">
          <!-- <a href="#">Management</a> -->
          <!-- <a class="active_link" href="#">Admin</a> -->
        </div>
        <div class="navbar__right">
          
          <a href="#">
            <img width="30" src="<?=$_SESSION['image']?>" alt="" style='border-radius: 5px;'/>
            <!-- <i class="fa fa-user-circle-o" aria-hidden="true"></i> -->
          </a>
        </div>
      </nav>

      <main>
        <div class="main__container">
          <!-- MAIN TITLE STARTS HERE -->

          <div class="main__title">
            <img src="assets/hello.svg" alt="" />
            <div class="main__greeting">
              <h1>Hello <?=$_SESSION['fullname']?></h1>
              <p>Welcome to your admin dashboard</p>
            </div>
          </div>

          <!-- MAIN TITLE ENDS HERE -->

          <!-- MAIN CARDS STARTS HERE -->
          <div class="main__cards">

            
          </div>
          <!-- MAIN CARDS ENDS HERE -->

          <!-- CHARTS STARTS HERE -->
          <div class="charts">

            <div class="charts__right">
              <div class="charts__right__title">
                <div>
                  <h1>Admin - Upload Results</h1>
                  <!-- <p>Manage Students Result</p> -->
                </div>
                <i class="fa fa-upload" aria-hidden="true"></i>
              </div>
              <center>
	              <form method="POST" enctype="multipart/form-data"><br>
				       <div> <label for="result_file">Upload Results (CSV):</label>
				        <input type="file" id="result_file" name="result_file" required style="width: 50%; padding: 16px 32px;border-radius: 20px; border: 1px solid #eee;margin:10px;"></div><br><p>
				        <button type="submit" class="btn-get-started"><i class="fa fa-upload" aria-hidden="true"></i> Upload</button></p>
				    </form>
				</center>


            </div>
          </div>
          <!-- CHARTS ENDS HERE -->
        </div>
      </main>

<?php include 'sidebar.php';?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>


  </body>
</html>
