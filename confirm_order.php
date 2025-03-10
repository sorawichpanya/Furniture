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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // ตรวจสอบว่ามีการกรอกข้อมูลครบทุกฟิลด์
        if (empty($_POST['full_name']) || empty($_POST['phone']) || empty($_POST['address']) || empty($_POST['province']) || empty($_POST['zip_code'])) {
            $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
            header("Location: checkout.php");
            exit;
        }
    
        // เก็บข้อมูลลงใน $_SESSION
        $_SESSION['user_full_name'] = $_POST['full_name'];
        $_SESSION['user_phone'] = $_POST['phone'];
        $_SESSION['user_address'] = $_POST['address'];
        $_SESSION['user_province'] = $_POST['province'];
        $_SESSION['user_zip_code'] = $_POST['zip_code'];
    
        // ตัวอย่างการเช็คข้อมูลใน $_SESSION หลังจากการกรอกข้อมูล
        var_dump($_SESSION['user_full_name'], $_SESSION['user_phone'], $_SESSION['user_address'], $_SESSION['user_province'], $_SESSION['user_zip_code']);
        
        // ดำเนินการต่อกับการบันทึกข้อมูลหรือการส่งข้อมูลอื่นๆ
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
