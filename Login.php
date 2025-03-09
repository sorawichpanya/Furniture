<?php
// Start session
session_start();

// Include your database connection file (assumed to be included here)
include('db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $Username = mysqli_real_escape_string($conn, $_POST['Username']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  

    // Query the database to verify user credentials (you should hash the password in a real app)
    $query = "SELECT * FROM users WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $Username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['Username'] = $Username;
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid Username or password.";
        }
    } else {
        $error_message = "Invalid Username or password.";
    }
    
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Add necessary CSS/JS files for MDB -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<section class="vh-100 gradient-custom">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-dark text-white" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">

            <div class="mb-md-5 mt-md-4 pb-5">

              <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
              <p class="text-white-50 mb-5">Please enter your login and password!</p>

              <form method="POST" action="">
                <div class="form-outline form-white mb-4">
                  <input type="text" name="Username" class="form-control form-control-lg" required />
                  <label class="form-label">Username</label>
                </div>

                <div class="form-outline form-white mb-4">
                  <input type="password" name="password" class="form-control form-control-lg" required />
                  <label class="form-label">Password</label>
                </div>

                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#">Forgot password?</a></p>

                <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>
              </form>

              <?php
              // Display error message if credentials are wrong
              if (isset($error_message)) {
                  echo '<p style="color: red;">' . $error_message . '</p>';
              }
              ?>
            </div>

            <div>
              <p class="mb-0">Don't have an account? <a href="Register.php" class="text-white-50 fw-bold">Register</a></p>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
