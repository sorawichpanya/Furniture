<?php
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 🟢 รับค่า table_name จาก URL (GET parameter)
$allowed_tables = ['bathroom', 'living_room', 'kitchen', 'bedroom']; // ตัวอย่าง table ที่อนุญาต
$table_name = isset($_GET['table']) ? $_GET['table'] : '';

if (!in_array($table_name, $allowed_tables)) {
    die("❌ Table ไม่ถูกต้อง");}

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 🟢 รับค่าจากฟอร์ม
    $p_name = $_POST['p_name'] ?? '';
    $p_detail = $_POST['p_detail'] ?? '';
    $p_color = $_POST['p_color'] ?? '';
    $p_size = $_POST['p_size'] ?? '';
    $p_price = $_POST['p_price'] ?? '';

    // ✅ ตรวจสอบข้อมูลที่จำเป็น
    if (empty($p_name) || empty($p_detail) || empty($p_color) || empty($p_size) || empty($p_price)) {
        die("❌ ข้อมูลไม่ครบถ้วน กรุณาตรวจสอบข้อมูลให้ถูกต้อง");
    }

    // ✅ บันทึกข้อมูลสินค้า (ยังไม่รวมรูปภาพ)
    $stmt = $conn->prepare("INSERT INTO $table_name (p_name, p_detail, p_color, p_size, p_price) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("❌ Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssssi", $p_name, $p_detail, $p_color, $p_size, $p_price);

    if ($stmt->execute()) {
        $p_id = $stmt->insert_id;  // ✅ ดึง `p_id` ล่าสุด
        $stmt->close();
        
        // ✅ ตรวจสอบการอัปโหลดรูปภาพ
        if (!empty($_FILES["p_image"]["name"])) {
            $file_ext = pathinfo($_FILES["p_image"]["name"], PATHINFO_EXTENSION); // ดึงนามสกุลไฟล์
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($file_ext), $allowed_exts)) {
                $new_filename = $p_id . "." . $file_ext; // ✅ ใช้ `p_id` เป็นชื่อไฟล์
                $upload_dir = "../img/".$table_name."/"; // โฟลเดอร์ที่เก็บไฟล์
                $upload_path = $upload_dir . $new_filename;

                // ✅ ย้ายไฟล์ไปยังโฟลเดอร์
                if (move_uploaded_file($_FILES["p_image"]["tmp_name"], $upload_path)) {
                    // ✅ อัปเดต `p_ext` ในฐานข้อมูล
                    $stmt_update = $conn->prepare("UPDATE $table_name SET p_ext = ? WHERE p_id = ?");
                    $stmt_update->bind_param("si", $file_ext, $p_id);
                    $stmt_update->execute();
                    $stmt_update->close();

                    echo "✅ เพิ่มสินค้าเรียบร้อย!";
                } else {
                    echo "❌ ไม่สามารถอัปโหลดรูปภาพได้";
                }
            } else {
                echo "❌ ประเภทไฟล์ไม่รองรับ (อนุญาตเฉพาะ jpg, png, gif)";
            }
        } else {
            echo "✅ เพิ่มสินค้าเรียบร้อย (ไม่มีรูปภาพ)";
        }
    } else {
        echo "❌ เกิดข้อผิดพลาด: " . $conn->error;
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
