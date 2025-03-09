<?php
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $new_password = trim($_POST['new_password']);

    // ตรวจสอบว่าความยาว username และ password ถูกต้อง
    if (strlen($username) < 5) {
        echo "<div class='alert alert-danger'>Username must be at least 5 characters.</div>";
    } elseif (strlen($new_password) < 8) {
        echo "<div class='alert alert-danger'>Password must be at least 8 characters.</div>";
    } else {
        // ตรวจสอบว่ามี username อยู่ในระบบหรือไม่
        $sql_check = "SELECT * FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // หาก username มีอยู่ในระบบ ให้รีเซ็ตรหัสผ่าน
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update = "UPDATE users SET password = ? WHERE username = ?";
            $stmt_update = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt_update, 'ss', $hashed_password, $username);

            if (mysqli_stmt_execute($stmt_update)) {
                echo "<div class='alert alert-success'>Password has been reset successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Failed to reset password. Please try again later.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Username not found!</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="path_to_your_styles.css">
</head>
<body>
<div class="container tm-mt-big tm-mb-big">
    <div class="row">
        <div class="col-12 mx-auto tm-login-col">
            <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="tm-block-title mb-4">Reset Your Password</h2>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="reset_password.php" method="post" class="tm-login-form">
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
                                <label for="new_password">New Password</label>
                                <input
                                    name="new_password"
                                    type="password"
                                    class="form-control validate"
                                    id="new_password"
                                    value=""
                                    required
                                />
                            </div>
                            <div class="form-group mt-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block text-uppercase"
                                >
                                    Reset Password
                                </button>
                            </div>
                            <a href="login.php" class="btn btn-secondary btn-block text-uppercase mt-3">
                                Back to Login
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
