<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปโอนเงินก่อนยืนยันคำสั่งซื้อ";
        header("Location: checkout.php");
        exit;
    }

    // ดึงค่าจาก Session
    $full_name = $_SESSION['user_full_name'];
    $phone = $_SESSION['user_phone'];
    $address = $_SESSION['user_address'];
    $province = $_SESSION['user_province'];
    $zip_code = $_SESSION['user_zip_code'];
    $total_price = $_SESSION['cart_total'] + 50;
    $payment_proof = $_SESSION['payment_slip'];

    // บันทึกลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $payment_proof);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการบันทึกเรียบร้อย!";
        unset($_SESSION['cart']); // ล้างตะกร้า
        unset($_SESSION['payment_uploaded']); // รีเซ็ตการอัปโหลดสลิป
        header("Location: order_success.php");
        exit;
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่";
        header("Location: checkout.php");
        exit;
    }
}
?>
