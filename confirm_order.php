<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['payment_slip'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปการชำระเงินก่อนยืนยันคำสั่งซื้อ!";
        header("Location: checkout.php");
        exit;
    }

    $full_name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $province = trim($_POST['province']);
    $zip_code = trim($_POST['zip_code']);
    $total_price = trim($_POST['paid_amount']);
    $payment_proof = $_SESSION['payment_slip'];
    $order_status = 'pending'; // ค่าเริ่มต้น

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
        unset($_SESSION['cart'], $_SESSION['payment_slip']);
        $_SESSION['success_message'] = "การสั่งซื้อสำเร็จ!";
        header("Location: success.php");
        exit;
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ: " . $stmt->error;
        header("Location: checkout.php");
        exit;
    }
} else {
    header("Location: checkout.php");
    exit;
}
