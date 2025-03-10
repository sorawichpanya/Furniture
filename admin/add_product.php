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

    // อัปโหลดไฟล์ก่อนแล้วเปลี่ยนชื่อให้ตรงกับ p_id หลังจากเพิ่มข้อมูลสินค้า
    if (move_uploaded_file($_FILES['p_image']['tmp_name'], $target_file)) {
        echo "✅ อัปโหลดรูปสำเร็จ: $p_image_name<br>";

        // เพิ่มข้อมูลสินค้าเข้าฐานข้อมูล
        $query = "INSERT INTO $table_name (p_name, p_detail, p_color, p_size, p_price, p_ext) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            die("❌ Query prepare failed: " . $conn->error);
        }
        
        // ผูกตัวแปร
        $stmt->bind_param("ssssss", $p_name, $p_detail, $p_color, $p_size, $p_price, $p_ext);

        if ($stmt->execute()) {
            // ✅ 2. ดึงค่า p_id ล่าสุด
            $p_id = $conn->insert_id;

            // ตั้งชื่อไฟล์ใหม่ให้ตรงกับ p_id
            $new_filename = $p_id . '.' . $p_ext;
            $new_upload_path = "../img/$table_name/" . $new_filename;

            // ย้ายไฟล์ที่อัปโหลดไปยังชื่อใหม่
            if (rename($target_file, $new_upload_path)) {
                echo "✅ เปลี่ยนชื่อไฟล์เป็น $new_filename";

                // ✅ 3. อัปเดตชื่อไฟล์ในฐานข้อมูล
                $update_stmt = $conn->prepare("UPDATE $table_name SET p_ext = ? WHERE p_id = ?");
                $update_stmt->bind_param( $p_id);
                $update_stmt->execute();
                $update_stmt->close();

                echo "✅ อัปเดตข้อมูลไฟล์ในฐานข้อมูลเรียบร้อย!";
            } else {
                echo "❌ เกิดข้อผิดพลาดในการย้ายไฟล์ใหม่";
            }

            echo "✅ เพิ่มสินค้าเรียบร้อย!";
        } else {
            echo "❌ เกิดข้อผิดพลาด: " . $conn->error;
        }
        
        $stmt->close();
    } else {
        echo "❌ เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ";
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
