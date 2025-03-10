<?php
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $product_name = $_POST['product_name'] ?? '';
    $product_detail = $_POST['product_detail'] ?? '';
    $product_color = $_POST['product_color'] ?? '';
    $product_size = $_POST['product_size'] ?? '';
    $product_price = $_POST['product_price'] ?? 0;
    $table_name = $_POST['table_name'] ?? 'products'; // ชื่อตารางสินค้า

    // ตรวจสอบค่าที่จำเป็น
    if (empty($product_name) || empty($product_price)) {
        die("❌ กรุณากรอกชื่อสินค้าและราคาสินค้า");
    }

    // อัพโหลดรูปภาพ
    $target_dir = "../img/" . $table_name . "/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_ext = pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION);
    $image_name = time(); // ใช้ timestamp เป็นชื่อไฟล์
    $target_file = $target_dir . $image_name . "." . $image_ext;

    if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
        // เพิ่มข้อมูลสินค้าในฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO $table_name (p_name, p_detail, p_color, p_size, p_price, p_id, p_ext) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            die("❌ Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssssiss", $product_name, $product_detail, $product_color, $product_size, $product_price, $image_name, $image_ext);
        if ($stmt->execute()) {
            echo "🎉 เพิ่มสินค้าสำเร็จ!";
        } else {
            echo "❌ เพิ่มสินค้าไม่สำเร็จ: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "❌ อัปโหลดรูปภาพไม่สำเร็จ";
    }

    $conn->close();
}
?>
