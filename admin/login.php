<?php
session_start();
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM user WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            // เก็บข้อมูลใน Session
            $_SESSION['username'] = $row['username'];
            header("Location: index.php"); // เปลี่ยนเส้นทางไปยังหน้า index.php
            exit;
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login Page - Product Admin Template</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700"
    />
    <!-- https://fonts.google.com/specimen/Open+Sans -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
  </head>

  <body id="reportsPage">
    <div class="" id="home">
        <nav class="navbar navbar-expand-xl">
            <div class="container h-100">
                <a class="navbar-brand" href="index.php">
                    <h1 class="tm-site-title mb-0">Product Admin</h1>
                </a>
                <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars tm-nav-icon"></i>
                </button>

            </div>
        </nav>
    </div>
</body>

<?php
// เชื่อมต่อฐานข้อมูล
include_once("connectdb.php");

// ตรวจสอบการส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; // ไม่ต้อง escape เพราะไม่ได้ใช้ใน SQL โดยตรง

    // ตรวจสอบ username ในฐานข้อมูล
    $sql = "SELECT * FROM user WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $user['password'])) {
            // เข้าสู่ระบบสำเร็จ
            echo "<script>alert('Login successful! Welcome, $username.');</script>";
            // Redirect ไปหน้า dashboard
            header("Location: index.php");
            exit();
        } else {
            // รหัสผ่านไม่ถูกต้อง
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        // ไม่พบ username ในฐานข้อมูล
        echo "<script>alert('Username not found. Please try again.');</script>";
    }
}
?>

<div class="container tm-mt-big tm-mb-big">
    <div class="row">
        <div class="col-12 mx-auto tm-login-col">
            <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="tm-block-title mb-4">Welcome to Dashboard, Login</h2>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="" method="post" class="tm-login-form">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input
                                    name="username"
                                    type="text"
                                    class="form-control validate"
                                    id="username"
                                    value=""
                                    required
                                />
                            </div>
                            <div class="form-group mt-3">
                                <label for="password">Password</label>
                                <input
                                    name="password"
                                    type="password"
                                    class="form-control validate"
                                    id="password"
                                    value=""
                                    required
                                />
                            </div>
                            <div class="form-group mt-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block text-uppercase"
                                >
                                    Login
                                </button>
                            </div>
                            <a href="forgot_password.php" class="btn btn-secondary btn-block text-uppercase mt-3">
                                Forgot Your Password?
                            </a>
                            <a href="register.php" class="btn btn-secondary btn-block text-uppercase mt-3">
                                Register
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <footer class="tm-footer row tm-mt-small">
      <div class="col-12 font-weight-light">
        <p class="text-center text-white mb-0 px-4 small">
          Copyright &copy; <b>2018</b> All rights reserved. 
          
          Design: <a rel="nofollow noopener" href="https://templatemo.com" class="tm-footer-link">Template Mo</a>
        </p>
      </div>
    </footer>
    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
  </body>
</html>
