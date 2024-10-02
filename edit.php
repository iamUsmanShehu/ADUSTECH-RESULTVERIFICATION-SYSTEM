<?php
session_start();

// Database connection
require_once 'includes/db.php';

// Check if the user ID is provided via GET request
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the user details for the given ID
    $sql = "SELECT id, fullname, username, email, passport_image FROM users WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $message = "User not found.";
        exit();
    }

    // Check if the form is submitted to update the data
    if (isset($_POST['update'])) {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];

        // Handle file upload
        $passport_image = $user['passport_image']; // Default to current image

        if ($_FILES['passport_image']['name']) {
            $target_dir = "uploads/"; // Directory where the image will be stored
            $target_file = $target_dir . basename($_FILES["passport_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if the image file is a valid image
            $check = getimagesize($_FILES["passport_image"]["tmp_name"]);
            if ($check === false) {
                $message = "File is not an image.";
                $uploadOk = 0;
            }

            // Check file size (limit to 2MB)
            if ($_FILES["passport_image"]["size"] > 2000000) {
                $message = "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
                $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $message = "Sorry, your file was not uploaded.";
            } else {
                // If everything is ok, try to upload the file
                if (move_uploaded_file($_FILES["passport_image"]["tmp_name"], $target_file)) {
                    $passport_image = $target_file; // Update the image path
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            }
        }

        // Update query
        $update_sql = "UPDATE users SET fullname=?, username=?, email=?, passport_image=? WHERE id=?";
        $update_stmt = $connection->prepare($update_sql);
        $update_stmt->bind_param('ssssi', $fullname, $username, $email, $passport_image, $id);

        // Execute and check if the update was successful
        if ($update_stmt->execute()) {
            $message = "User details updated successfully!";
            header("Location: profile.php"); // Redirect back to the user list after update
            exit();
        } else {
            $message = "Error updating user: " . $connection->error;
        }
    }
} else {
    $message = "Invalid request.";
    exit();
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
                  <h1>Admin - Edit</h1>
                  <!-- <p>Manage Students Result</p> -->
                </div>
                <i class="fa fa-edit" aria-hidden="true"></i>
              </div>
              
                <form method="post" enctype="multipart/form-data">
                    <?php if (isset($message)) {echo $message; }?>
                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>" class='form-control'>
                    <div>
                        <label for="fullname">Fullname:</label>
                        <input type="text" id="fullname" name="fullname" value="<?php echo $user['fullname']; ?>" required class='form-control'>
                    </div>
                    <div>
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required class='form-control'>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required class='form-control'>
                    </div>
                    <div>
                        <img src="<?php echo $user['passport_image']; ?>" alt="Passport Image" width="100"><br>
                        <input type="file" name="passport_image" id="passport_image" accept="image/*" class='form-control'>
                    </div>
                    <div>
                        <button type="submit" name="update" class="btn-get-started">Update</button>
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