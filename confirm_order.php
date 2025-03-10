<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 🔍 Debug: ดูค่าที่ได้รับจาก POST
    echo "<h3>🔍 Debug: ข้อมูลที่ได้รับจาก POST</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // 🔍 Debug: ตรวจสอบค่าสลิปโอนเงิน
    if (!isset($_SESSION['payment_uploaded']) || empty($_SESSION['payment_uploaded'])) {
        echo "<h3>⛔ SESSION payment_uploaded ไม่มีค่าหรือไม่ได้ตั้งค่า!</h3>";
        exit;
    }

    // ✅ รับค่าจากฟอร์ม
    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $province = trim($_POST['province']);
    $zip_code = trim($_POST['zip_code']);
    $total_price = trim($_POST['paid_amount']); // ใช้ค่าที่รับมา
    $payment_proof = $_SESSION['payment_uploaded']; // ใช้ค่าจาก SESSION
    $order_status = !empty($_POST['order_status']) ? $_POST['order_status'] : 'pending'; // ใช้ค่า default เป็น 'pending' ถ้าไม่มีค่า

    // ✅ ตรวจสอบว่ากรอกข้อมูลครบ
    $required_fields = ['full_name', 'phone', 'address', 'province', 'zip_code', 'paid_amount'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $_SESSION['error_message'] = "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
            header("Location: checkout.php");
            exit;
        }
    }

    // ✅ บันทึกลงฐานข้อมูล
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

    // 🔍 Debug SQL Error
    if ($stmt->execute()) {
        echo "<h3>✅ Insert สำเร็จ</h3>";
        unset($_SESSION['cart'], $_SESSION['payment_uploaded']);
        header("Location: order_success.php");
        exit;
    } else {
        echo "<h3>⛔ SQL Error:</h3> " . $stmt->error;
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: checkout.php");
    exit;
}
?>
