<?php
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // ตรวจสอบความยาว username และ password
    if (strlen($username) < 5 || strlen($password) < 8) {
        echo "<script>alert('Username must be at least 5 characters and Password at least 8 characters.');</script>";
    } elseif ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // แฮชรหัสผ่าน
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // ตรวจสอบว่ามี username ซ้ำหรือไม่
        $sql_check = "SELECT * FROM admin WHERE username = '$username'";
        $result = mysqli_query($conn, $sql_check);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<script>alert('Username already exists. Please choose another one.');</script>";
        } else {
            // บันทึกข้อมูลผู้ใช้
            $sql_insert = "INSERT INTO admin (username, password) VALUES ('$username', '$hashedPassword')";
            if (mysqli_query($conn, $sql_insert)) {
                echo "<script>alert('Registration successful!');</script>";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('Registration failed. Please try again.');</script>";
            }
        }
    }
}
?>

<div class="container tm-mt-big tm-mb-big">
    <div class="row">
        <div class="col-12 mx-auto tm-login-col">
            <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2 class="tm-block-title mb-4">Create an Account</h2>
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
                            <div class="form-group mt-3">
                                <label for="confirm_password">Confirm Password</label>
                                <input
                                    name="confirm_password"
                                    type="password"
                                    class="form-control validate"
                                    id="confirm_password"
                                    value=""
                                    required
                                />
                            </div>
                            <div class="form-group mt-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block text-uppercase"
                                >
                                    Register
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
