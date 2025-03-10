<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // ตรวจสอบว่าผู้ใช้ได้อัปโหลดสลิปหรือยัง
    if (!isset($_FILES['payment_slip']) || $_FILES['payment_slip']['error'] !== UPLOAD_ERR_OK) {
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
    $total_price = trim($_POST['total_price']);

    // ตรวจสอบว่าฟิลด์ทั้งหมดถูกกรอก
    if (empty($full_name) || empty($phone) || empty($address) || empty($province) || empty($zip_code) || empty($total_price)) {
        $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบประเภทไฟล์ที่อัปโหลด
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];
    $file_type = mime_content_type($_FILES['payment_slip']['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        $_SESSION['error_message'] = "รูปแบบไฟล์ไม่ถูกต้อง (รองรับเฉพาะ JPG, PNG, และ PDF)";
        header("Location: checkout.php");
        exit;
    }

    // อัปโหลดไฟล์สลิปโอนเงิน
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    $file_name = time() . "_" . basename($_FILES['payment_slip']['name']);
    $target_path = $upload_dir . $file_name;

    if (!move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_path)) {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        header("Location: checkout.php");
        exit;
    }

    // บันทึกข้อมูลลงในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $target_path);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการบันทึกเรียบร้อย!";
        unset($_SESSION['cart']);
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
