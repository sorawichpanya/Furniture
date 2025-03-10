<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่าได้รับข้อมูลจาก SESSION
    if (!isset($_SESSION['user_full_name'], $_SESSION['user_phone'], $_SESSION['user_address'], $_SESSION['user_province'], $_SESSION['user_zip_code'], $_SESSION['cart'])) {
        $_SESSION['error_message'] = "ข้อมูลไม่ครบถ้วนสำหรับการยืนยันคำสั่งซื้อ!";
        header("Location: checkout.php");
        exit;
    }

    // ดึงข้อมูลจาก SESSION
    $full_name = $_SESSION['user_full_name'];
    $phone = $_SESSION['user_phone'];
    $address = $_SESSION['user_address'];
    $province = $_SESSION['user_province'];
    $zip_code = $_SESSION['user_zip_code'];
    $cart = $_SESSION['cart'];

    // คำนวณราคาสินค้าทั้งหมด
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['total_price'];
    }
    $shipping = 50;
    $total_price = $subtotal + $shipping;

    // บันทึกข้อมูลคำสั่งซื้อลงในฐานข้อมูล
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $status);

    // กำหนดสถานะเป็น "paid"
    $status = 'paid';

    if ($stmt->execute()) {
        // ดึง ID ของคำสั่งซื้อที่เพิ่งเพิ่ม
        $order_id = $stmt->insert_id;

        // บันทึกรายการสินค้าลงในตาราง order_items
        foreach ($cart as $item) {
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, total_price) VALUES (?, ?, ?, ?)");
            $stmt_item->bind_param("isid", $order_id, $item['p_name'], $item['quantity'], $item['total_price']);
            $stmt_item->execute();
        }

        // ลบข้อมูลใน SESSION หลังบันทึกสำเร็จ
        unset($_SESSION['cart'], $_SESSION['user_full_name'], $_SESSION['user_phone'], $_SESSION['user_address'], $_SESSION['user_province'], $_SESSION['user_zip_code']);
        
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว!";
        header("Location: success.php"); // หน้าสำหรับแสดงข้อความสำเร็จ
        exit;
    } else {
        $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการบันทึกคำสั่งซื้อ: " . $stmt->error;
        header("Location: checkout.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "คำขอไม่ถูกต้อง!";
    header("Location: checkout.php");
    exit;
}
?>
