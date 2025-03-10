<?php
include_once("connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $province = $_POST['province'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $order_status = $_POST['order_status'] ?? 'pending';
    $total_price = $_POST['total_price'] ?? 0;

    // ตรวจสอบค่าที่ส่งมาว่าครบถ้วนหรือไม่
    if (empty($full_name) || empty($phone) || empty($address) || empty($province) || empty($zip_code) || empty($total_price)) {
        die("ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง");
    }

    // เชื่อมต่อฐานข้อมูลและบันทึกคำสั่งซื้อ
    $conn = new mysqli("localhost", "root", "12345678P", "FurnitureFunny");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // เริ่มต้น transaction เพื่อให้มั่นใจว่าเพิ่มข้อมูลทั้งใน orders และ orders_item
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $order_status);
        $stmt->execute();
        $order_id = $stmt->insert_id;  // Get the ID of the inserted order
        $stmt->close();

        // ตรวจสอบว่า cart มีสินค้า
        if (!empty($_SESSION['cart'])) {
            // เพิ่มข้อมูลใน orders_item
            foreach ($_SESSION['cart'] as $item) {
                $stmt = $conn->prepare("INSERT INTO order_item (order_id, product_name, quantity, total_price) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isid", $order_id, $item['p_name'], $item['quantity'], $item['total_price']);
                $stmt->execute();
                $stmt->close();
            }
        }

        // Commit transaction
        $conn->commit();
        echo "สั่งซื้อสำเร็จ!";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    $conn->close();
}
?>
