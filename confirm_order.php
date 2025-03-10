<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $province = $_POST['province'] ?? '';
    $zip_code = $_POST['zip_code'] ?? '';
    $order_status = $_POST['order_status'] ?? 'pending';
    $cart = json_decode($_POST['cart'], true) ?? [];
    $total_price = $_POST['total_price'] ?? 0;

    // เช็คว่าข้อมูลครบหรือไม่
    if (!$full_name || !$phone || !$address || !$province || !$zip_code || empty($cart)) {
        echo "ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง";
        exit;
    }

    // เชื่อมต่อฐานข้อมูลและเพิ่มคำสั่ง INSERT
    $conn = new mysqli("localhost", "root", "", "your_database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // บันทึกข้อมูลการสั่งซื้อ
    $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, order_status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $full_name, $phone, $address, $province, $zip_code, $total_price, $order_status);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // บันทึกสินค้าที่สั่งซื้อ
    foreach ($cart as $item) {
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, p_id, p_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisid", $order_id, $item['p_id'], $item['p_name'], $item['quantity'], $item['total_price']);
        $stmt->execute();
        $stmt->close();
    }

    echo "Order confirmed!";
    $conn->close();
}
?>
