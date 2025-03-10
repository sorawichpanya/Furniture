
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .register-container {
            max-width: 450px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-orange {
            background-color:rgb(133, 127, 122);
            color: white;
            font-weight: bold;
        }
        .btn-orange:hover {
            background-color:rgba(102, 100, 99, 0.8);
        }
        .form-control {
            background-color: #f5f5f5;
            border: none;
        }
        .form-check-label a {
            color:rgb(59, 57, 57);
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-container">
        <h2 class="text-center mb-4">REGISTER</h2>
        <form method="POST" action="Insert_register.php">
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control" placeholder="John Doe" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control" placeholder="012-3456789" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Momo" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Your Password" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Re-type Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Re-type Your Password" required>
    </div>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="terms" required>
        <label class="form-check-label" for="terms">
            Agree our <a href="#">Terms and Conditions</a>
        </label>
    </div>
    <button type="submit" class="btn btn-orange w-100">Register</button>
</form>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    var password = document.querySelector("input[name='password']").value;
    var confirmPassword = document.querySelector("input[name='confirm_password']").value;
    
    if (password !== confirmPassword) {
        alert("รหัสผ่านไม่ตรงกัน กรุณาลองใหม่");
        event.preventDefault(); // ยกเลิกการส่งฟอร์ม
    }
});


        <div class="text-center mt-3">
            <small>Have an account? <a href="Login.php">Login</a></small>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
