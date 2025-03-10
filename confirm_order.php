<?php
session_start();
require 'db_connect.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "Please upload a payment slip before confirming your order.";
        header("Location: checkout.php");
        exit;
    }

    // ดึงข้อมูลจากฟอร์ม
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $province = $_POST['province'];
    $zip_code = $_POST['zip_code'];
    $total_price = $_SESSION['cart_total']; // ราคารวมจากตะกร้า
    $payment_proof = $_SESSION['payment_slip']; // Path ของสลิป

    // บันทึกลงตาราง orders
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $payment_proof);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Your order has been placed successfully!";
        unset($_SESSION['cart']); // ล้างตะกร้าสินค้า
        unset($_SESSION['payment_uploaded']); // รีเซ็ตการอัปโหลดสลิป

        header("Location: order_success.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Error placing order. Please try again.";
        header("Location: checkout.php");
        exit;
    }
}
?>
