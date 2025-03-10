<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: Login.php");
    exit();
}

$name = $_SESSION["name"]; // ดึงชื่อจาก session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2>Welcome, <?php echo htmlspecialchars($name); ?>!</h2>
        </div>
        <div class="card-body">
            <p>คุณสามารถเลือกซื้อสินค้า หรือจัดการบัญชีของคุณได้จากที่นี่</p>
            <a href="shop.php" class="btn btn-success">🛒 ไปที่ร้านค้า</a>
            <a href="logout.php" class="btn btn-danger">🚪 ออกจากระบบ</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
