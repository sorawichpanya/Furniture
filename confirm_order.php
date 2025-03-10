<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'connectdb.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "<h3>🔍 Debug: ข้อมูลที่ได้รับจาก POST</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    exit; // หยุดโปรแกรมไว้ก่อนเพื่อดูค่าที่ได้รับ
}

    // ตรวจสอบว่ามีการอัปโหลดสลิปหรือไม่
    if (!isset($_SESSION['payment_uploaded']) || empty($_SESSION['payment_uploaded'])) {
        echo "<h3>🔍 Debug: ค่าสลิปโอนเงิน</h3>";
        var_dump($_SESSION['payment_uploaded']);
        exit;        

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
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("sssssis", 
$_POST['full_name'], 
$_POST['phone'], 
$_POST['address'], 
$_POST['province'], 
$_POST['zip_code'], 
$_POST['paid_amount'], 
$_POST['order_status']
);

// 🔍 Debug SQL Error
if ($stmt->execute()) {
echo "<h3>✅ Insert สำเร็จ</h3>";
} else {
echo "<h3>⛔ SQL Error:</h3> " . $stmt->error;
}
exit;    
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: checkout.php");
    exit;
}
?>
