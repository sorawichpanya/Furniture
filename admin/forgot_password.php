<?php
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // ตรวจสอบว่าอีเมลมีอยู่ในฐานข้อมูลหรือไม่
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // ถ้าเจออีเมลในฐานข้อมูล
        // สร้างลิงก์รีเซ็ตรหัสผ่าน (สามารถใช้โทเค็นเพิ่มเติมได้)
        $reset_link = "reset_password.php?email=" . urlencode($email);
        
        // การส่งอีเมล
        // **เพิ่มการตั้งค่า mail() หรือส่งอีเมลจริง**
        echo "<div class='alert alert-success'>A password reset link has been sent to your email.</div>";
    } else {
        echo "<div class='alert alert-danger'>Email not found in our system.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-12 mx-auto tm-login-col">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h2 class="tm-block-title mb-4">Forgot Your Password?</h2>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="forgot_password.php" method="post" class="tm-login-form">
                                <div class="form-group">
                                    <label for="email">Enter your email</label>
                                    <input
                                        name="email"
                                        type="email"
                                        class="form-control validate"
                                        id="email"
                                        required
                                    />
                                </div>
                                <div class="form-group mt-4">
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-block text-uppercase"
                                    >
                                        Submit
                                    </button>
                                </div>
                                <a href="login.php" class="btn btn-secondary btn-block text-uppercase mt-3">Back to Login</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
