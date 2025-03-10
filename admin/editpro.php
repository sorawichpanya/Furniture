<?php
// editpro.php
session_start();
include_once("connectdb.php");
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit;
}

// รับค่าพารามิเตอร์จาก URL
$table_name = $_GET['table'] ?? null;
$p_id = $_GET['p_id'] ?? null;

// ตรวจสอบค่าพารามิเตอร์
if (!$table_name || !$p_id) {
    die("❌ พารามิเตอร์ไม่ครบถ้วน");
}

// ตรวจสอบชื่อตารางที่อนุญาต
$allowed_tables = ['bathroom', 'kitchen_room', 'living_room', 'trendy', 'Just_arrived', 'bedroom', 'garden', 'workroom'];
if (!in_array(strtolower($table_name), array_map('strtolower', $allowed_tables))) {
    die("❌ ตารางไม่ถูกต้อง");
}

// ดึงข้อมูลสินค้าเดิม
$sql = "SELECT * FROM `$table_name` WHERE p_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $p_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    die("❌ ไม่พบสินค้า");
}

// กระบวนการอัปเดตข้อมูล
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $p_name = $_POST['p_name'];
    $p_detail = $_POST['p_detail'];
    $p_color = $_POST['p_color'];
    $p_size = $_POST['p_size'];
    $p_price = $_POST['p_price'];

    // ตรวจสอบข้อมูลที่จำเป็น
    if (empty($p_name) || empty($p_detail) || empty($p_color) || empty($p_size) || empty($p_price)) {
        die("❌ กรุณากรอกข้อมูลให้ครบถ้วน");
    }

    // ตรวจสอบการอัปโหลดรูปภาพใหม่
    if (!empty($_FILES['p_image']['name'])) {
        $p_ext = pathinfo($_FILES['p_image']['name'], PATHINFO_EXTENSION);
        $target_dir = "../img/$table_name/";
        $new_filename = $p_id . '.' . $p_ext;
        $target_file = $target_dir . $new_filename;

        // ลบไฟล์เก่า (ถ้ามี)
        if (file_exists($target_dir . $product['p_id'] . '.' . $product['p_ext'])) {
            unlink($target_dir . $product['p_id'] . '.' . $product['p_ext']);
        }

        // อัปโหลดไฟล์ใหม่
        if (move_uploaded_file($_FILES['p_image']['tmp_name'], $target_file)) {
            // อัปเดตข้อมูลรวมถึงนามสกุลไฟล์
            $sql = "UPDATE `$table_name` SET 
                    p_name = ?, 
                    p_detail = ?, 
                    p_color = ?, 
                    p_size = ?, 
                    p_price = ?, 
                    p_ext = ? 
                    WHERE p_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssdsi", $p_name, $p_detail, $p_color, $p_size, $p_price, $p_ext, $p_id);
        } else {
            die("❌ เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ");
        }
    } else {
        // อัปเดตข้อมูลโดยไม่เปลี่ยนรูปภาพ
        $sql = "UPDATE `$table_name` SET 
                p_name = ?, 
                p_detail = ?, 
                p_color = ?, 
                p_size = ?, 
                p_price = ? 
                WHERE p_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdi", $p_name, $p_detail, $p_color, $p_size, $p_price, $p_id);
    }

    // ประมวลผลคำสั่ง SQL
    if ($stmt->execute()) {
        $_SESSION['success'] = "✅ อัปเดตข้อมูลสำเร็จ!";
    } else {
        $_SESSION['error'] = "❌ เกิดข้อผิดพลาด: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    
    // Redirect กลับไปหน้าเดิม
    header("Location: products.php?table_name=" . urlencode($table_name));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>แก้ไขสินค้า</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="table" value="<?= htmlspecialchars($table_name) ?>">
                            <input type="hidden" name="p_id" value="<?= htmlspecialchars($p_id) ?>">

                            <div class="mb-3">
                                <label class="form-label">ชื่อสินค้า</label>
                                <input type="text" name="p_name" class="form-control" 
                                    value="<?= htmlspecialchars($product['p_name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รายละเอียด</label>
                                <textarea name="p_detail" class="form-control" rows="3" required><?= 
                                    htmlspecialchars($product['p_detail']) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">สี</label>
                                    <input type="text" name="p_color" class="form-control" 
                                        value="<?= htmlspecialchars($product['p_color']) ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ขนาด</label>
                                    <input type="text" name="p_size" class="form-control" 
                                        value="<?= htmlspecialchars($product['p_size']) ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">ราคา</label>
                                <input type="number" step="0.01" name="p_price" class="form-control" 
                                    value="<?= htmlspecialchars($product['p_price']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รูปภาพ</label>
                                <input type="file" name="p_image" class="form-control" accept="image/*">
                                <small class="text-muted">หากไม่ต้องการเปลี่ยนรูปภาพ ไม่ต้องเลือกไฟล์</small>
                                <?php if($product['p_ext']): ?>
                                    <div class="mt-2">
                                        <img src="../img/<?= $table_name ?>/<?= $p_id ?>.<?= $product['p_ext'] ?>" 
                                            alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                                <a href="products.php?table_name=<?= urlencode($table_name) ?>" 
                                    class="btn btn-secondary">ยกเลิก</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
