<?php
session_start();
require 'connectdb.php';

// เปิด error reporting เพื่อดีบัก
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่ามีค่าใน $_POST
    if (empty($_POST)) {
        $_SESSION['error_message'] = "ไม่มีข้อมูลที่ถูกส่งมา";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบว่ามีการอัปโหลดสลิปหรือไม่
    if (!isset($_SESSION['payment_uploaded']) || empty($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปโอนเงินก่อนยืนยันคำสั่งซื้อ";
        header("Location: checkout.php");
        exit;
    }

    // รับค่าจากฟอร์ม
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $province = trim($_POST['province']);
    $zip_code = trim($_POST['zip_code']);
    $total_price = trim($_POST['paid_amount']); // ใช้ค่าที่รับมา
    $payment_proof = $_SESSION['payment_uploaded']; // ใช้ค่าจาก SESSION

    // ตรวจสอบว่ากรอกข้อมูลครบ
    $required_fields = ['full_name', 'phone', 'address', 'province', 'zip_code', 'paid_amount'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
            header("Location: checkout.php");
            exit;
        }
    }

    // บันทึกลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'paid')");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $payment_proof);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "สั่งซื้อสำเร็จ!";
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
