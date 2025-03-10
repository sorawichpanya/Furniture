<?php
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ✅ รับค่าจากฟอร์ม
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $province = $_POST['province'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $order_status = $_POST['order_status'] ?? 'pending';
    $total_price = $_POST['total_price'] ?? 0;
    
    $cart = json_decode($_POST['cart'], true);
    // 🔴 ถ้าตะกร้าว่าง ให้แจ้งเตือน
    if (empty($cart)) {
        die("❌ ไม่มีสินค้าในตะกร้า กรุณาเลือกสินค้าใหม่!");
    }

    // ✅ ตรวจสอบค่าที่จำเป็น
    if (empty($full_name) || empty($phone) || empty($address) || empty($province) || empty($zip_code) || empty($total_price)) {
        die("❌ ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง");
    }

    // ✅ เชื่อมต่อฐานข้อมูล
    $conn = new mysqli("localhost", "root", "12345678P", "FurnitureFunny");
    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error);
    }

    // ✅ เริ่ม Transaction
    $conn->begin_transaction();

    try {
        // ✅ บันทึกข้อมูลใน `orders`
        $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("❌ Error preparing statement for orders: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $order_status);
        if (!$stmt->execute()) {
            throw new Exception("❌ Error executing statement for orders: " . $stmt->error);
        }

        // ✅ ดึง `order_id`
        $order_id = $stmt->insert_id;

        // ✅ บันทึกข้อมูล `order_items`
        foreach ($cart as $item) {
            $product_name = $item['p_name'];
            $quantity = $item['quantity'];
            $item_total_price = $item['total_price'];

            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_name, quantity, total_price) VALUES (?, ?, ?, ?)");
            if (!$stmt_item) {
                throw new Exception("❌ Error preparing statement for orders_item: " . $conn->error);
            }

            $stmt_item->bind_param("isii", $order_id, $product_name, $quantity, $item_total_price);
            if (!$stmt_item->execute()) {
                throw new Exception("❌ Error executing statement for orders_item: " . $stmt_item->error);
            }

            $stmt_item->close();
        }

        // ✅ ยืนยัน Transaction
        $conn->commit();

        // ✅ แสดงข้อความสำเร็จ
        echo "🎉 สั่งซื้อสำเร็จ!";

    } catch (Exception $e) {
        $conn->rollback();
        echo "❌ เกิดข้อผิดพลาด: " . $e->getMessage();
    }

    // ✅ ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>
