<?php
session_start();

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (isset($_SESSION['cart']) && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // ตรวจสอบว่า id ของสินค้าคืออะไร และลบมันออกจากตะกร้า
    if (isset($_SESSION['cart'][$product_id])) {
        // ลบสินค้าจากตะกร้า
        unset($_SESSION['cart'][$product_id]);

        // แสดงผลลัพธ์หลังจากลบสินค้า
        header("Location: cart.php"); // กลับไปยังหน้า cart.php
        exit;
    }
}

// ถ้าหากไม่มีการส่งข้อมูลหรือไม่พบสินค้าในตะกร้า
header("Location: cart.php");
exit;
?>
