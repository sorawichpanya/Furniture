<?php
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

var_dump($_FILES);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = $_POST['p_name'] ?? '';
    $p_detail = $_POST['p_detail'] ?? '';
    $p_color = $_POST['p_color'] ?? '';
    $p_size = $_POST['p_size'] ?? '';
    $p_price = $_POST['p_price'] ?? 0;
    $table_name = $_POST['table_name'] ?? '';

    // ตรวจสอบค่าที่จำเป็น
    if (empty($p_name) || empty($p_detail) || empty($p_color) || empty($p_size) || empty($p_price) || empty($table_name)) {
        die("❌ ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง");
    }

    // อัพโหลดรูปภาพ
    $target_dir = "../img/" . $table_name . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $image_ext = pathinfo($_FILES["p_image"]["name"], PATHINFO_EXTENSION);
    $image_name = uniqid();
    $target_file = $target_dir . $image_name . '.' . $image_ext;
    $table_name = $_GET['table_name'] ?? $_POST['table_name'] ?? '';
        if (empty($table_name)) {
            die("❌ ไม่พบข้อมูลตารางสินค้า กรุณาเลือกหมวดหมู่สินค้าให้ถูกต้อง");
        }
    if (move_uploaded_file($_FILES["p_image"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO `$table_name` (p_name, p_detail, p_color, p_size, p_price, p_id, p_ext) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $p_name, $p_detail, $p_color, $p_size, $p_price, $image_name, $image_ext);
        
        if ($stmt->execute()) {
            echo "🎉 เพิ่มสินค้าสำเร็จ!";
        } else {
            echo "❌ เกิดข้อผิดพลาด: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "❌ ไม่สามารถอัพโหลดรูปภาพได้";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h2>Add New Product</h2>
    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="p_name" required>
        <br>
        <label>Detail:</label>
        <textarea name="p_detail" required></textarea>
        <br>
        <label>Color:</label>
        <input type="text" name="p_color" required>
        <br>
        <label>Size:</label>
        <input type="text" name="p_size" required>
        <br>
        <label>Price:</label>
        <input type="number" name="p_price" step="0.01" required>
        <br>
        <label>Image:</label>
        <input type="file" name="p_image" accept="image/*" required>
        <br>
        <button type="submit">Add Product</button>
    </form>
</body>
</html>
