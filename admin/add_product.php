<?php
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ตรวจสอบค่าจาก URL หรือฟอร์ม
$table_name = $_GET['table_name'] ?? ($_POST['table'] ?? null);

if (!$table_name) {
    die("❌ Table name is missing. Please specify the table.");
}

// รายการ Table ที่อนุญาต
$allowed_tables = ['bathroom', 'kitchen', 'livingroom'];

if (!in_array(strtolower($table_name), array_map('strtolower', $allowed_tables))) {
    die("❌ Table ไม่ถูกต้อง");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = $_POST['p_name'] ?? '';
    $p_detail = $_POST['p_detail'] ?? '';
    $p_color = $_POST['p_color'] ?? '';
    $p_size = $_POST['p_size'] ?? '';
    $p_price = $_POST['p_price'] ?? '';

    if (empty($p_name) || empty($p_detail) || empty($p_color) || empty($p_size) || empty($p_price)) {
        die("❌ ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูล");
    }

    // เพิ่มข้อมูลสินค้า
    $stmt = $conn->prepare("INSERT INTO $table_name (p_name, p_detail, p_color, p_size, p_price) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $p_name, $p_detail, $p_color, $p_size, $p_price);

    if ($stmt->execute()) {
        echo "✅ เพิ่มสินค้าเรียบร้อย!";
    } else {
        echo "❌ เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
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
    <!-- Debug: แสดงค่า table_name -->
    <p>Table: <?php echo htmlspecialchars($table_name); ?></p>

    <!-- Hidden Input -->
    <input type="hidden" name="table" value="<?php echo htmlspecialchars($table_name); ?>">

    <!-- ฟิลด์ต่าง ๆ -->
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
