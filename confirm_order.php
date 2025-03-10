<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่าอัปโหลดไฟล์สลิปการชำระเงินแล้ว
    if (!isset($_SESSION['payment_uploaded']) || empty($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปการชำระเงินก่อนยืนยันคำสั่งซื้อ!";
        header("Location: checkout.php");
        exit;
    }

    // รับค่าจากฟอร์ม
    $order_status = trim($_POST['order_status']); // สถานะคำสั่งซื้อ
    $allowed_statuses = ['paid', 'not_paid'];

    // ตรวจสอบว่าสถานะคำสั่งซื้อถูกต้อง
    if (!in_array($order_status, $allowed_statuses)) {
        $_SESSION['error_message'] = "สถานะคำสั่งซื้อไม่ถูกต้อง!";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบข้อมูล SESSION ทีละค่า
    $required_session_keys = [
        'user_full_name', 
        'user_phone', 
        'user_address', 
        'user_province', 
        'user_zip_code', 
        'paid_amount', 
        'payment_slip'
    ];

    foreach ($required_session_keys as $key) {
        if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) {
            $_SESSION['error_message'] = "ข้อมูลไม่ครบถ้วนสำหรับการยืนยันคำสั่งซื้อ! [$key]";
            header("Location: checkout.php");
            exit;
        }
    }

    // ดึงข้อมูลจาก SESSION
    $full_name = htmlspecialchars($_SESSION['user_full_name']);
    $phone = htmlspecialchars($_SESSION['user_phone']);
    $address = htmlspecialchars($_SESSION['user_address']);
    $province = htmlspecialchars($_SESSION['user_province']);
    $zip_code = htmlspecialchars($_SESSION['user_zip_code']);
    $paid_amount = htmlspecialchars($_SESSION['paid_amount']);
    $payment_proof = $_SESSION['payment_slip']; // ไฟล์สลิปการชำระเงิน

    // บันทึกคำสั่งซื้อในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "ssssssss",
        $full_name,
        $phone,
        $address,
        $province,
        $zip_code,
        $paid_amount,
        $payment_proof,
        $order_status
    );

    if ($stmt->execute()) {
        // ลบ SESSION หลังบันทึกสำเร็จ
        unset($_SESSION['cart'], $_SESSION['payment_uploaded'], $_SESSION['payment_slip']);
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว!";
        header("Location: success.php"); // หน้าสำหรับแสดงข้อความสำเร็จ
        exit;
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ: " . $stmt->error;
        error_log("SQL Error: " . $stmt->error); // บันทึกใน Log
        header("Location: checkout.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "คำขอไม่ถูกต้อง!";
    header("Location: checkout.php");
    exit;
}
?>
