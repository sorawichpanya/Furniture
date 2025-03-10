<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "Please upload a payment slip before confirming your order.";
        header("Location: checkout.php");
        exit;
    }

    // เพิ่มโค้ดบันทึกคำสั่งซื้อในฐานข้อมูล
    // ตัวอย่าง: INSERT INTO orders (user_id, total_amount, payment_proof, status) VALUES (...)

    $_SESSION['success_message'] = "Your order has been placed successfully!";
    unset($_SESSION['cart']); // ล้างตะกร้าสินค้า

    header("Location: order_success.php");
    exit;
}
?>
