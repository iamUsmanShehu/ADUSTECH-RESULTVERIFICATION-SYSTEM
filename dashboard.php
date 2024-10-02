<?php
session_start();
require_once 'includes/db.php';


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
    <title>ADUSTECH - Result Verification System</title>
    <link rel="stylesheet" href="styles.css" />

    <link rel="stylesheet" href="styles.css" />
    <title>ADUSTECH-Result Verification System</title>
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
          <marquee>ADUSTECH RESULT VERIFICATION SYSTEM</marquee>
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
            <div class="card">
              <i
                class="fa fa-file-o fa-2x text-lightblue"
                aria-hidden="true"
              ></i>
              <div class="card_inner">
                <p class="text-primary-p">Number of Results</p>
                <span class="font-bold text-title"><?=$total_results?></span>
              </div>
            </div>

            
          </div>
          <!-- MAIN CARDS ENDS HERE -->

          <!-- CHARTS STARTS HERE -->
          <div class="charts">

            <div class="charts__right">
              <div class="charts__right__title">
                <div>
                  <h1>Results Table</h1>
                  <p>Manage Students Result</p>
                </div>
                <i class="fa fa-building-o" aria-hidden="true"></i>
              </div>
                <!-- Display Table -->
                <table class="table" border="1" id="resultsTable" style="width: 100%;">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student Reg</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Final Result</th>
                        <th>Issue Date</th>
                        <th>Verification Status</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['student_reg']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['final_result']; ?></td>
                                <td><?php echo $row['issue_date']; ?></td>
                                <td><?php echo $row['verification_status']; ?></td>
                                <td><img src="<?php echo $row['qr_code']; ?>" alt="QR Code" width="50" height="50"></td>
                                <td>
                                    <!-- <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> | -->
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this result?');"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">No results found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
          </div>
          <!-- CHARTS ENDS HERE -->
        </div>
      </main>

<?php include 'sidebar.php';?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="script.js"></script>

    <!-- Include jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#resultsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5',
                    'print'
                ]
            });
        });
    </script>

  </body>
</html>
