<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่าได้อัปโหลดสลิปหรือยัง
    if (!isset($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปโอนเงินก่อนยืนยันคำสั่งซื้อ";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบประเภทไฟล์
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];
    $payment_slip = $_SESSION['payment_slip'] ?? null; // ตรวจสอบว่ามีไฟล์อยู่จริงหรือไม่
    $payment_slip_type = $payment_slip ? mime_content_type($payment_slip) : '';

    if (!$payment_slip || !in_array($payment_slip_type, $allowed_types)) {
        $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or PDF files are allowed.";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบว่ามีค่าข้อมูลทั้งหมดใน Session
    $full_name = $_SESSION['user_full_name'] ?? null;
    $phone = $_SESSION['user_phone'] ?? null;
    $address = $_SESSION['user_address'] ?? null;
    $province = $_SESSION['user_province'] ?? null;
    $zip_code = $_SESSION['user_zip_code'] ?? null;
    $total_price = $_SESSION['cart_total'] ? ($_SESSION['cart_total'] + 50) : null;
    $payment_proof = $payment_slip;

    // ตรวจสอบว่าค่าที่จำเป็นต้องมีเป็น NULL หรือไม่
    if (!$full_name || !$phone || !$address || !$province || !$zip_code || !$total_price || !$payment_proof) {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาด: มีข้อมูลบางส่วนหายไป กรุณากรอกข้อมูลให้ครบ";
        header("Location: checkout.php");
        exit;
    }

    // บันทึกลงฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $payment_proof);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการบันทึกเรียบร้อย!";
        unset($_SESSION['cart']); 
        unset($_SESSION['payment_uploaded']);
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
