<?php
session_start();
include 'connectdb.php';

$p_id = $_GET['p_id'];

// ดึงข้อมูลสินค้า
$sql = "SELECT * FROM products WHERE p_id = '$p_id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "ไม่พบสินค้า!";
    exit();
}

// ถ้าตะกร้ายังไม่มี ให้สร้างใหม่
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ถ้าสินค้ามีอยู่แล้ว ให้เพิ่มจำนวน
if (isset($_SESSION['cart'][$p_id])) {
    $_SESSION['cart'][$p_id]['quantity']++;
} else {
    $_SESSION['cart'][$p_id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

header("Location: basket.php"); // ไปหน้าตะกร้า
exit();