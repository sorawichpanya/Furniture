<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>
    <link href="http://212.80.215.178/Furniture/css/bootstrap.css" rel="stylesheet" type="text/css">
</head>
<body>
    <h2>กรอกข้อมูลการสั่งซื้อ</h2>
    <form action="checkout.php" method="post">
        <label>ชื่อ-นามสกุล:</label>
        <input type="text" name="customer_name" required class="form-control">
        
        <label>ที่อยู่:</label>
        <textarea name="customer_address" required class="form-control"></textarea>
        
        <button type="submit" class="btn btn-success">✅ ยืนยันคำสั่งซื้อ</button>
    </form>
</body>
</html>
