<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

var_dump($_SESSION);
exit;
// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการส่งข้อมูล POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ดึงข้อมูลจาก SESSION
    $full_name = $_SESSION['user_full_name'];
    $phone = $_SESSION['user_phone'];
    $address = $_SESSION['user_address'];
    $province = $_SESSION['user_province'];
    $zip_code = $_SESSION['user_zip_code'];
    $cart = $_SESSION['cart'];

    // ตรวจสอบว่า session มีข้อมูลครบถ้วนหรือไม่
    if (empty($full_name) || empty($phone) || empty($address) || empty($province) || empty($zip_code) || empty($cart)) {
        $_SESSION['error_message'] = "ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง";
        header("Location: checkout.php");
        exit;
    }

    // คำนวณราคาสินค้าทั้งหมด
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += $item['total_price']; // คำนวณราคารวมของสินค้าทั้งหมด
    }
    $shipping = 50; // ค่าจัดส่ง
    $total_price = $subtotal + $shipping; // ราคารวม

    // เตรียมคำสั่ง SQL สำหรับการบันทึกคำสั่งซื้อในตาราง orders
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $status);

    // กำหนดสถานะเป็น "paid"
    $status = 'paid';

    // ถ้าคำสั่ง SQL สำเร็จ
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
