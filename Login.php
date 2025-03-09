<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .login-container {
            width: 900px;
            display: flex;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        .login-image {
            width: 50%;
            background: url('https://source.unsplash.com/600x600/?workspace,desk') no-repeat center;
            background-size: cover;
        }
        .login-form {
            width: 50%;
            padding: 50px;
        }
        .login-form h2 {
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-control {
            background-color: #f5f5f5;
            border: none;
            height: 45px;
        }
        .btn-login {
            background-color: #6c63ff;
            color: white;
            font-weight: bold;
            height: 45px;
            border-radius: 5px;
        }
        .btn-login:hover {
            background-color: #5a54e8;
        }
    </style>
</head>
<body>

<div class="login-container">
    <!-- ด้านซ้ายเป็นรูปภาพ -->
    <div class="login-image"></div>

    <!-- ด้านขวาเป็นฟอร์มล็อกอิน -->
    <div class="login-form">
        <h2>Login to continue</h2>
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        <form method="POST" action="process_login.php">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login w-100">LOGIN</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
