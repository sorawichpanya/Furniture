<?php
session_start();
include 'connectdb.php';

if (empty($_SESSION['cart'])) {
    echo "ไม่มีสินค้าในตะกร้า!";
    exit();
}

// ข้อมูลลูกค้า (เอาค่าจากฟอร์ม)
$customer_name = $_POST['customer_name'];
$customer_address = $_POST['customer_address'];
$total_price = 0;

// คำนวณราคารวม
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

// บันทึกคำสั่งซื้อในตาราง orders
$sql = "INSERT INTO orders (customer_name, customer_address, total_price) 
        VALUES ('$customer_name', '$customer_address', '$total_price')";
mysqli_query($conn, $sql);

// ดึง ID คำสั่งซื้อที่เพิ่งเพิ่ม
$order_id = mysqli_insert_id($conn);

// บันทึกสินค้าแต่ละชิ้นลง order_details
foreach ($_SESSION['cart'] as $p_id => $item) {
    $sql = "INSERT INTO order_details (order_id, product_id, quantity, price) 
            VALUES ('$order_id', '$p_id', '{$item['quantity']}', '{$item['price']}')";
    mysqli_query($conn, $sql);
}

// ล้างตะกร้าหลังจากสั่งซื้อ
unset($_SESSION['cart']);

echo "<h2>🎉 คำสั่งซื้อของคุณถูกบันทึกแล้ว!</h2>";
echo "<a href='index.php'>🔙 กลับไปหน้าหลัก</a>";
?>
