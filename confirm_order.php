<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit;

var_dump($_SESSION);
exit;
// เปิดโหมดแสดงข้อผิดพลาด
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 🔍 ตรวจสอบค่า SESSION
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['error_message'] = "ไม่มีสินค้าในตะกร้า!";
    header("Location: checkout.php");
    exit;
}

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบการส่งข้อมูล POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่าจาก SESSION และตรวจสอบว่าไม่เป็นค่าว่าง
    $full_name = $_SESSION['user_full_name'] ?? '';
    $phone = $_SESSION['user_phone'] ?? '';
    $address = $_SESSION['user_address'] ?? '';
    $province = $_SESSION['user_province'] ?? '';
    $zip_code = $_SESSION['user_zip_code'] ?? '';
    $cart = $_SESSION['cart'];

    // ตรวจสอบข้อมูลที่ต้องใช้ว่ามีค่าหรือไม่
    if (empty($full_name) || empty($phone) || empty($address) || empty($province) || empty($zip_code)) {
        $_SESSION['error_message'] = "ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง";
        header("Location: checkout.php");
        exit;
    }

    // คำนวณราคาสินค้าทั้งหมด
    $subtotal = 0;
    foreach ($cart as $item) {
        if (!isset($item['total_price'])) {
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการคำนวณราคารวม!";
            header("Location: checkout.php");
            exit;
        }
        $subtotal += $item['total_price'];
    }
    $shipping = 50; // ค่าจัดส่ง
    $total_price = $subtotal + $shipping; // ราคารวม

    // กำหนดสถานะเป็น "paid"
    $status = 'paid';

    // 🔍 Debug: แสดงค่าก่อนบันทึก
    echo "<h3>🔍 Debug: ข้อมูลคำสั่งซื้อ</h3>";
    echo "<pre>";
    print_r([
        'full_name' => $full_name,
        'phone' => $phone,
        'address' => $address,
        'province' => $province,
        'zip_code' => $zip_code,
        'total_price' => $total_price,
        'status' => $status,
    ]);
    echo "</pre>";

    // เตรียมคำสั่ง SQL สำหรับการบันทึกคำสั่งซื้อในตาราง orders
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    if (!$stmt) {
        die("⛔ SQL Prepare Failed: " . $conn->error);
    }

    $stmt->bind_param("sssssis", $full_name, $phone, $address, $province, $zip_code, $total_price, $status);

    // 🔍 Debug: ตรวจสอบผลลัพธ์ SQL
    if ($stmt->execute()) {
        echo "<h3>✅ Insert สำเร็จ</h3>";
        $order_id = $stmt->insert_id;

        // บันทึกรายการสินค้าลงในตาราง order_items
        foreach ($cart as $item) {
            if (!isset($item['p_name'], $item['quantity'], $item['total_price'])) {
                $_SESSION['error_message'] = "เกิดข้อผิดพลาดในข้อมูลสินค้า!";
                header("Location: checkout.php");
                exit;
            }

            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, total_price) VALUES (?, ?, ?, ?)");

            if (!$stmt_item) {
                die("⛔ SQL Prepare Failed (order_items): " . $conn->error);
            }

            $stmt_item->bind_param("isid", $order_id, $item['p_name'], $item['quantity'], $item['total_price']);
            $stmt_item->execute();
        }

        // ลบข้อมูลใน SESSION หลังบันทึกสำเร็จ
        unset($_SESSION['cart'], $_SESSION['user_full_name'], $_SESSION['user_phone'], $_SESSION['user_address'], $_SESSION['user_province'], $_SESSION['user_zip_code']);
        
        $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการยืนยันเรียบร้อยแล้ว!";
        header("Location: success.php");
        exit;
    } else {
        die("<h3>⛔ SQL Error:</h3> " . $stmt->error);
    }
} else {
    $_SESSION['error_message'] = "คำขอไม่ถูกต้อง!";
    header("Location: checkout.php");
    exit;
}
?>
