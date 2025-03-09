<?php

session_start();

include('db_connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database to verify user credentials (you should hash the password in the real app)
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // If user is found, start a session
        $_SESSION['username'] = $username;
        header("Location: dashboard.php"); // Redirect to dashboard or home page
    } else {
        // If credentials are incorrect
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login </title>
    <style>
        /* Your styles here (same as in the original HTML) */
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-form">
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <h2>Sign In</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="login-button">Sign in</button>
            </form>
            <?php
            // Display error message if credentials are wrong
            if (isset($error_message)) {
                echo '<p style="color: red;">' . $error_message . '</p>';
            }
            ?>
            <div class="remember-forgot">
                <label><input type="checkbox"> Remember Me</label>
                <a href="#">Forgot Password</a>
            </div>
        </div>
        <div class="login-welcome">
            <h2>Welcome to login</h2>
            <p>Don't have an account?</p>
            <a href="Register.php" class="signup-button">Sign Up</a>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/your-font-awesome-kit.js" crossorigin="anonymous"></script>
</body>
</html>
