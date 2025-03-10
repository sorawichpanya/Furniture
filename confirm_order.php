<?php
include_once("connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $province = $_POST['province'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $order_status = $_POST['order_status'] ?? 'pending';
    $total_price = $_POST['total_price'] ?? 0;

    // ตรวจสอบค่าที่ส่งมาว่าครบถ้วนหรือไม่
    if (empty($full_name) || empty($phone) || empty($address) || empty($province) || empty($zip_code) || empty($total_price)) {
        die("ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง");
    }

    // เชื่อมต่อฐานข้อมูลและบันทึกคำสั่งซื้อ
    $conn = new mysqli("localhost", "root", "", "shopping_cart");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $order_status);

    if ($stmt->execute()) {
        echo "สั่งซื้อสำเร็จ!";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
