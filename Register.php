<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2 class="text-center mb-4">สมัครสมาชิก</h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="firstname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">นามสกุล</label>
                <input type="text" name="lastname" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">เบอร์โทรศัพท์</label>
                <input type="number" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary btn-custom">บันทึก</button>
            <button type="reset" class="btn btn-secondary btn-custom mt-2">ยกเลิก</button>
        </form>
        <hr>
        <div class="text-center">
            <p>มีบัญชีแล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
