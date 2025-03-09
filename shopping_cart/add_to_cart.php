<?php
session_start();
include 'connectdb.php';

if (!isset($_GET['id'])) {
    die("❌ ไม่พบสินค้า!");
}

$id = $_GET['id']; // รับค่า id ของสินค้า

// ดึงข้อมูลสินค้า
$sql = "SELECT * FROM products WHERE id = '$id'";
$result = mysqli_query($conn, $sql);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("❌ ไม่พบสินค้าในฐานข้อมูล!");
}

// ถ้าตะกร้ายังไม่มี ให้สร้างใหม่
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ถ้าสินค้ามีอยู่แล้ว ให้เพิ่มจำนวน
if (isset($_SESSION['cart'][$id])) {
    $_SESSION['cart'][$id]['quantity']++;
} else {
    $_SESSION['cart'][$id] = [
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => 1
    ];
}

// Debug เช็คค่าตะกร้า
echo "<pre>";
print_r($_SESSION['cart']);
echo "</pre>";

header("Location: basket.php"); // ไปหน้าตะกร้า
exit();
?>
