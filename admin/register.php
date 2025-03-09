<?php
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // ตรวจสอบความยาวของ Username และ Password
    if (strlen($username) < 5) {
        echo "<div class='alert alert-danger'>Username must be at least 5 characters.</div>";
    } elseif (strlen($password) < 8) {
        echo "<div class='alert alert-danger'>Password must be at least 8 characters.</div>";
    } else {
        // รหัสผ่านสามารถใช้ password_hash() เพื่อเพิ่มความปลอดภัย
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // เพิ่มผู้ใช้ลงในฐานข้อมูล
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            echo "<div class='alert alert-success'>Registration successful!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
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
