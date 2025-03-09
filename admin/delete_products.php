<?php
include_once("connectdb.php");

if (isset($_POST['product_ids']) && !empty($_POST['product_ids'])) {
    // รับค่าจากฟอร์ม
    $product_ids = $_POST['product_ids'];

    // ลบสินค้าที่เลือกจากฐานข้อมูล
    foreach ($product_ids as $product_id) {
        $sql = "DELETE FROM products WHERE p_id = $product_id";

        if (mysqli_query($conn, $sql)) {
            echo "Product with ID $product_id has been deleted successfully.<br>";
        } else {
            echo "Error deleting product with ID $product_id: " . mysqli_error($conn) . "<br>";
        }
    }
} else {
    echo "No products selected to delete.";
}

header("Location: products.php");  // หลังจากลบเสร็จให้กลับไปที่หน้าหลัก
exit;
?>
