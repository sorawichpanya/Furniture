<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบ SESSION payment_slip
    if (!isset($_SESSION['payment_slip']) || empty($_SESSION['payment_slip'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปการชำระเงินก่อนยืนยันคำสั่งซื้อ";
        header("Location: checkout.php");
        exit;
    }

    // รับค่าจาก SESSION และ POST
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $province = trim($_POST['province']);
    $zip_code = trim($_POST['zip_code']);
    $total_price = trim($_POST['paid_amount']);
    $payment_proof = $_SESSION['payment_slip'];

    // ตรวจสอบค่า status
    $allowed_statuses = ['pending', 'confirmed', 'shipped', 'delivered', 'canceled'];
    $order_status = 'pending'; // ค่าเริ่มต้น

    if (isset($_POST['order_status']) && in_array($_POST['order_status'], $allowed_statuses)) {
        $order_status = $_POST['order_status'];
    }

    // ตรวจสอบข้อมูลที่จำเป็น
    $required_fields = ['full_name', 'phone', 'address', 'province', 'zip_code', 'paid_amount'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
            header("Location: checkout.php");
            exit;
        }
    }

    // บันทึกข้อมูลลงในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", 
        $full_name, 
        $phone, 
        $address, 
        $province, 
        $zip_code, 
        $total_price, 
        $payment_proof, 
        $order_status
    );

    if ($stmt->execute()) {
        // ล้าง SESSION ที่ไม่จำเป็น
        unset($_SESSION['cart'], $_SESSION['payment_slip']);
        $_SESSION['success_message'] = "การสั่งซื้อสำเร็จ";
        header("Location: confirm_order.php");
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
