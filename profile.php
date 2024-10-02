
<?php
session_start();
require_once 'includes/db.php';

// Fetch users from the database
$sql = "SELECT id, fullname, username, email, passport_image, created_at FROM users WHERE 1";
$result = $connection->query($sql);

// Check if the form has been submitted for editing
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    // Update query
    $update_sql = "UPDATE users SET fullname=?, username=?, email=? WHERE id=?";
    $stmt = $connection->prepare($update_sql);
    $stmt->bind_param('sssi', $fullname, $username, $email, $id);
    if ($stmt->execute()) {
        echo "Record updated successfully!";
        header("Location: ".$_SERVER['PHP_SELF']); // Refresh to show updated data
    } else {
        echo "Error updating record: " . $connection->error;
    }
}
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th, td {
            padding: 10px;
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
                  <h1>Admin - Profile</h1>
                  <!-- <p>Manage Students Result</p> -->
                </div>
                <i class="fa fa-user" aria-hidden="true"></i>
              </div>
              
              <table>
    <tr>
        <th>ID</th>
        <th>Fullname</th>
        <th>Username</th>
        <th>Email</th>
        <th>Passport Image</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['fullname']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><img src="<?php echo $row['passport_image']; ?>" alt="Passport Image" width="50" height="50"></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="7">No users found.</td>
        </tr>
    <?php endif; ?>
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


  </body>
</html>
