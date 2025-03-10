<?php
// เชื่อมต่อกับฐานข้อมูล
include('db_connection.php');

// กำหนดจำนวนสินค้าที่จะแสดงต่อหน้า
$items_per_page = 12; 

// คำนวณหน้าปัจจุบันจาก URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;  // ตรวจสอบหากค่าหน้าเป็นค่าต่ำกว่าหนึ่ง

// ตรวจสอบการเลือกช่วงราคา
if (isset($_GET['price'])) {
    $priceRange = $_GET['price'];
    $priceFilter = explode('-', $priceRange); // แบ่งค่าเป็นช่วงต่ำสุดและสูงสุด
    $minPrice = $priceFilter[0];
    $maxPrice = $priceFilter[1];
    
    echo "Price filter: $priceRange<br>";  // ตรวจสอบค่าของ price
    echo "Min Price: $minPrice, Max Price: $maxPrice<br>";  // ตรวจสอบค่าของ minPrice และ maxPrice

    // สร้าง SQL Query เพื่อกรองสินค้า
    $sql = "SELECT * FROM products WHERE price BETWEEN $minPrice AND $maxPrice LIMIT " . (($page - 1) * $items_per_page) . ", $items_per_page";
} else {
    // หากไม่ได้เลือกกรองราคา จะแสดงสินค้าทุกชิ้น
    $sql = "SELECT * FROM products LIMIT " . (($page - 1) * $items_per_page) . ", $items_per_page";
}

// ตรวจสอบว่า SQL Query ถูกต้องหรือไม่
echo "SQL Query: $sql<br>";  // พิมพ์ SQL Query เพื่อตรวจสอบ

// รัน SQL Query
$result = mysqli_query($conn, $sql);

// ตรวจสอบผลลัพธ์
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<p>" . $row['name'] . "</p>";
        echo "<p>ราคา: " . $row['price'] . " บาท</p>";
        echo "</div>";
    }
} else {
    echo "ไม่พบสินค้าที่ตรงกับเงื่อนไขที่เลือก";
}

// คำนวณจำนวนสินค้าทั้งหมดสำหรับ pagination
if (isset($minPrice) && isset($maxPrice)) {
    $count_sql = "SELECT COUNT(*) AS total_items FROM products WHERE price BETWEEN $minPrice AND $maxPrice";
} else {
    $count_sql = "SELECT COUNT(*) AS total_items FROM products";
}

$count_result = mysqli_query($conn, $count_sql);
$row = mysqli_fetch_assoc($count_result);
$total_items = $row['total_items'];
$total_pages = ceil($total_items / $items_per_page);

// แสดงปุ่ม pagination
echo "<div class='pagination'>";
for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i&price=$priceRange'>$i</a> ";
}
echo "</div>";

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

