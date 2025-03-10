<?php
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$table_name = $_POST['table'] ?? $_GET['table'] ?? null;

if (!$table_name) {
    die("❌ Table name is missing. Please specify the table.");
}

$allowed_tables = ['bathroom', 'kitchen_room', 'living_room', 'trendy', 'Just_arrived', 'bedroom', 'garden', 'workroom'];
if (!in_array(strtolower($table_name), array_map('strtolower', $allowed_tables))) {
    die("❌ Table ไม่ถูกต้อง");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = $_POST['p_name'] ?? '';
    $p_detail = $_POST['p_detail'] ?? '';
    $p_color = $_POST['p_color'] ?? '';
    $p_size = $_POST['p_size'] ?? '';
    $p_price = $_POST['p_price'] ?? '';

    if (empty($p_name) || empty($p_detail) || empty($p_color) || empty($p_size) || empty($p_price) || empty($_FILES['p_image']['name'])) {
        die("❌ ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูล");
    }

    // ดึงนามสกุลไฟล์
    $p_ext = pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION);

    // กำหนดโฟลเดอร์เก็บรูปภาพ
    $target_dir = "../img/$table_name/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // ตั้งชื่อไฟล์ไม่ให้ซ้ำ
    $p_image_name = time() . "_" . basename($_FILES["p_image"]["name"]);
    $target_file = $target_dir . $p_image_name;

    // เพิ่มข้อมูลสินค้าเข้า Database
    $query = "INSERT INTO $table_name (p_name, p_detail, p_color, p_size, p_price, p_ext) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt->bind_param("ssssss", $p_name, $p_detail, $p_color, $p_size, $p_price, $p_ext);
    $stmt = $conn->prepare($query);
    if ($stmt->execute()) {
        // ✅ 2. ดึงค่า p_id ล่าสุด
        $p_id = $conn->insert_id;

        // ✅ 3. อัปโหลดรูปภาพโดยเปลี่ยนชื่อเป็น p_id
        if (!empty($_FILES['p_image']['name'])) {
            $p_ext = pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION); // ดึงนามสกุลไฟล์
            $new_filename = $p_id . '.' . $p_ext; // เปลี่ยนชื่อไฟล์เป็น "p_id.นามสกุล"
            $upload_path = "../img/$table_name/" . $new_filename; // กำหนด Path ปลายทาง
        }
    }
    if (move_uploaded_file($_FILES['p_image']['tmp_name'], $upload_path)) {
        echo "✅ อัปโหลดรูปสำเร็จ: $new_filename";
        
        // ✅ 4. อัปเดต p_ext ในฐานข้อมูลให้ตรงกับชื่อไฟล์ใหม่
        $update_stmt = $conn->prepare("UPDATE $table_name SET p_ext = ? WHERE p_id = ?");
        $update_stmt->bind_param("si", $new_filename, $p_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        echo "❌ เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
    }
    if (!$stmt) {
        die("❌ Query prepare failed: " . $conn->error);
    }

    if ($stmt->execute()) {
        echo "✅ เพิ่มสินค้าและรูปภาพเรียบร้อย!";
    } else {
        echo "❌ เกิดข้อผิดพลาด: " . $conn->error;
    }
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
