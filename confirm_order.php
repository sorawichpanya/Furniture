<?php
session_start();
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Debug ดูค่าที่ถูกส่งมา
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit;

    if (!isset($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปโอนเงินก่อนยืนยันคำสั่งซื้อ";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบว่ามีการกรอกข้อมูลครบทุกฟิลด์
    $required_fields = ['full_name', 'phone', 'address', 'province', 'zip_code'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
            header("Location: checkout.php");
            exit;
        }
    }

    // บันทึกลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssss", $_POST['full_name'], $_POST['phone'], $_POST['address'], $_POST['province'], $_POST['zip_code'], $total_price, $payment_proof);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการบันทึกเรียบร้อย!";
        unset($_SESSION['cart'], $_SESSION['payment_uploaded']);
        header("Location: order_success.php");
        exit;
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการสั่งซื้อ: " . $stmt->error;
        header("Location: checkout.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: checkout.php");
    exit;
}
?>