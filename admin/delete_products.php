<?php
include_once("connectdb.php");

// ตรวจสอบว่าเรามีค่า product_ids[] ที่ถูกเลือกหรือไม่
if (isset($_POST['product_ids']) && !empty($_POST['product_ids']) && isset($_POST['table_name'])) {
    // รับค่า table_name และ product_ids ที่ส่งมาจากฟอร์ม
    $table_name = $_POST['table_name'];
    $product_ids = $_POST['product_ids'];

    // ตรวจสอบให้แน่ใจว่า table_name เป็นชื่อที่ถูกต้อง
    $table_name = mysqli_real_escape_string($conn, $table_name);  // เพื่อป้องกัน SQL injection
    
    if (isset($_POST['table_name']) && isset($_POST['product_ids'])) {
        var_dump($_POST['table_name']);  // ตรวจสอบค่า table_name
        var_dump($_POST['product_ids']);  // ตรวจสอบค่าที่เลือก
    }

    // ลบสินค้าที่เลือกจากฐานข้อมูล
    foreach ($product_ids as $product_id) {
        $product_id = (int)$product_id;  // เพื่อให้มั่นใจว่าเป็นตัวเลข
        $sql = "DELETE FROM `$table_name` WHERE p_id = $product_id";

        if (mysqli_query($conn, $sql)) {
            echo "Product with ID $product_id has been deleted successfully.<br>";
        } else {
            echo "Error deleting product with ID $product_id: " . mysqli_error($conn) . "<br>";
        }
    }

    // รีไดเร็กไปยังหน้าหลักหลังจากการลบ
    header("Location: products.php?table_name=" . urlencode($table_name));  // กลับไปยังหน้าตารางที่ถูกลบ
    exit;
} else {
    echo "No products selected to delete or invalid table.";
}
?>

