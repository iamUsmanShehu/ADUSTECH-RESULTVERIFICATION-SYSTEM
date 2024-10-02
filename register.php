<?php
require_once 'includes/db.php';   

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['Fullname'];
    $username = $_POST['user'];
    $password = $_POST['password'];
    $email = $_POST['user'];  // Assuming email and username are interchangeable
    $image = $_FILES['image'];

    // Check if the user already exists
    $checkUserSql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $connection->prepare($checkUserSql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User already exists
        $message = "User already exists with this username or email.";
    } else {
        // Handle file upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a valid image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            $message = "File is not an image.";
        } elseif (file_exists($target_file)) {
            $message = "Sorry, file already exists.";
        } elseif ($image["size"] > 500000) {
            $message = "Sorry, your file is too large.";
        } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } else {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Hash the password before storing it
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert user data into the database
                $sql = "INSERT INTO users (fullname, username, password, email, passport_image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param("sssss", $fullname, $username, $hashed_password, $email, $target_file);

                if ($stmt->execute()) {
                    $message = "User registered successfully.";
                    header("refresh:2; url='login.php'");
                } else {
                    $message = "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        }
    }
}
 
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>ADUSTECH-Result Verification System</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link  rel="icon">
  <link rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header fixed-top">

    <div class="branding d-flex align-items-cente">

      <div class="container position-relative d-flex align-items-center justify-content-between">
        <a href="index.html" class="logo d-flex align-items-center">
          <h1 class="sitename">ADUSTECH-RVS</h1>
          <span>.</span>
        </a>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="#hero" class="active">Home<br></a></li>
            <li><a href="login.php">Signin</a></li>
            <!-- <li><a href="#contact">Contact</a></li> -->
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>

    </div>

  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section accent-background">

      <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-5 justify-content-between">
          <div class="col-lg-5 order-2 order-lg-1">
            <img src="assets/img/liz-gross-signup.gif" class="img-fluid" alt="" style="width: 100%; border-radius: 20px;">
          </div>
          <div class="col-lg-6 order-1 order-lg-2 d-flex flex-column justify-content-center">
            <p>
          <form method="POST" enctype="multipart/form-data">
              <?php if (isset($message)) { echo $message . "<br>"; } ?>

              <label for="Fullname">Fullname</label><br>
              <input type="text" id="Fullname" name="Fullname" required style="padding: 16px 32px; width: 80%; border-radius: 10px; margin-bottom: 10px;"><br>

              <label for="image">Passport</label><br>
              <input type="file" id="image" name="image" required class="form-control" style="padding: 16px 32px; width: 80%; border-radius: 10px; margin-bottom: 10px;"><br>

              <label for="user">Email or Username</label><br>
              <input type="text" id="user" name="user" required style="padding: 16px 32px; width: 80%; border-radius: 10px; margin-bottom: 10px;"><br>

              <label for="password">Password</label><br>
              <input type="password" id="password" name="password" required style="padding: 16px 32px; width: 80%; border-radius: 10px; margin-bottom: 10px;"><br>

              <button type="submit" class="btn-get-started">Register</button>
          </form>

            </p>
          </div>
        </div>
      </div>

      <div class="icon-boxes position-relative" data-aos="fade-up" data-aos-delay="200">
        <div class="container position-relative">
          <div class="row gy-4 mt-5">

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-book"></i></div>
                <h4 class="title"><a href="" class="stretched-link">I C T</a></h4>
              </div>
            </div><!--End Icon Box -->

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-book"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Computer Sci.</a></h4>
              </div>
            </div><!--End Icon Box -->

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-book"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Satatistics</a></h4>
              </div>
            </div><!--End Icon Box -->

            <div class="col-xl-3 col-md-6">
              <div class="icon-box">
                <div class="icon"><i class="bi bi-book"></i></div>
                <h4 class="title"><a href="" class="stretched-link">Mathematics</a></h4>
              </div>
            </div><!--End Icon Box -->

          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->


  </main>

  <footer id="footer" class="footer accent-background">

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2024</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="#">Musa Ahmad</a>
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>