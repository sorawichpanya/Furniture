<?php
session_start();

// ตรวจสอบว่ามีการส่งข้อมูลผ่าน POST หรือไม่
if (isset($_POST['product_id'], $_POST['category'])) {
    $p_id = (int)$_POST['product_id'];
    $category = $_POST['category'];

    // ตรวจสอบว่ามีตะกร้าในเซสชั่นหรือไม่
    if (isset($_SESSION['cart'])) {
        // ลบสินค้าจากตะกร้า
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['p_id'] == $p_id && $item['category'] == $category) {
                // ลบสินค้าออกจากตะกร้า
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']); // รีดัฟที่ของ array หลังจากลบ
                $_SESSION['success_message'] = "Product removed from cart!";
                break;
            }
        }
    }

    // ถ้าไม่พบสินค้าในตะกร้า
    if (!isset($_SESSION['success_message'])) {
        $_SESSION['error_message'] = "Product not found in cart!";
    }

    // เปลี่ยนเส้นทางไปยังหน้าตะกร้า
    header("Location: cart.php");
    exit;
} else {
    // หากไม่มีข้อมูลที่จำเป็น
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: cart.php");
    exit;
}
?>
