<?php
// เชื่อมต่อกับฐานข้อมูล
include('db_connection.php');

// ตรวจสอบการเลือกช่วงราคา
if (isset($_GET['price'])) {
    $priceRange = $_GET['price'];
    $priceFilter = explode('-', $priceRange); // แบ่งค่าเป็นช่วงต่ำสุดและสูงสุด
    $minPrice = $priceFilter[0];
    $maxPrice = $priceFilter[1];

    // สร้าง SQL Query เพื่อกรองสินค้า
    $sql = "SELECT * FROM products WHERE price BETWEEN $minPrice AND $maxPrice";
} else {
    // หากไม่ได้เลือกกรองราคา จะแสดงสินค้าทุกชิ้น
    $sql = "SELECT * FROM products";
}

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
?>

