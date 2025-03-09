<?php
include_once("connectdb.php");

// ตรวจสอบการส่งค่าผ่านฟอร์ม
if (isset($_POST['product_ids']) && !empty($_POST['product_ids']) && isset($_POST['table_name'])) {
    // รับค่าจากฟอร์ม
    $table_name = $_POST['table_name'];
    $product_ids = $_POST['product_ids'];

    // ตรวจสอบให้แน่ใจว่า table_name เป็นชื่อที่ถูกต้อง
    $table_name = mysqli_real_escape_string($conn, $table_name);  // เพื่อป้องกัน SQL injection

    // ลบสินค้าที่เลือกจากฐานข้อมูล
    foreach ($product_ids as $product_id) {
        $product_id = (int)$product_id;  // เพื่อให้มั่นใจว่าเป็นตัวเลข
        $sql = "DELETE FROM `$table_name` WHERE p_id = $product_id";

        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Product with ID $product_id has been deleted successfully.";
        } else {
            $_SESSION['message'] = "Error deleting product with ID $product_id: " . mysqli_error($conn);
        }
    }

    // รีไดเร็กไปยังหน้าหลักหลังจากการลบ
    header("Location: index.php?table_name=" . urlencode($table_name));  // กลับไปยังหน้าตารางที่ถูกลบ
    exit;
}
?>

<!-- แสดงข้อความแจ้งเตือน -->
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-info">
        <?php echo $_SESSION['message']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>