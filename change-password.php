<?php
// Database connection
require_once 'includes/db.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}

// Check if the form is submitted
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current user's data from the database
    $username = $_SESSION['username'];
    $sql = "SELECT password FROM users WHERE username=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify current password
    if (password_verify($current_password, $user['password'])) {
        // Check if new password and confirm password match
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password in the database
            $update_sql = "UPDATE users SET password=? WHERE username=?";
            $update_stmt = $connection->prepare($update_sql);
            $update_stmt->bind_param('ss', $hashed_password, $username);

            if ($update_stmt->execute()) {
                $message = "<div style='color:green;'>Password changed successfully!</div>";
                header("refresh:2; url='profile.php'"); // Redirect to profile page after change
                exit();
            } else {
                $message = "<div style='color:red;'>Error changing password: " . $connection->error . "</div>";
            }
        } else {
            $message = "<div style='color:red;'>New password and confirm password do not match.</div>";
        }
    } else {
        $message = "<div style='color:red;'>Current password is incorrect.</div>";
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
    <title>Admin - Edit</title>
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
    .form-control{
        padding: 16px 32px;
        width: 80%;
        border-radius: 10px;
        margin-bottom: 10px;
        border: 1px solid #eee;
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
                  <h1>Admin - Change Password</h1>
                  <!-- <p>Manage Students Result</p> -->
                </div>
                <i class="fa fa-edit" aria-hidden="true"></i>
              </div>
                <!-- Change password form -->
                <form method="post">
                    <?php if (isset($message)) {echo $message; }?>

                    <div>
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required class="form-control">
                    </div>
                    <div>
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required class="form-control">
                    </div>
                    <div>
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="form-control">
                    </div>
                    <div>
                        <button type="submit" name="change_password" class="btn-get-started">Change Password</button>
                    </div>
                </form>

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