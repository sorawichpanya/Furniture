<?php
include_once("connectdb.php");

// รับค่าคำค้นหา
$search_query = $_POST['search_query']; // ใช้ $_POST เพราะ method ใน form คือ POST

var_dump($search_query); // เพิ่มบรรทัดนี้เพื่อดูค่า

// ป้องกัน SQL injection โดยใช้ mysqli_real_escape_string
$search_query = mysqli_real_escape_string($conn, $search_query);

// สร้าง SQL query
$sql = "SELECT * FROM `shop` WHERE `p_name` LIKE '%$search_query%' OR `p_detail` LIKE '%$search_query%'";

// ลองพิมพ์ SQL query ออกมาดู
echo "SQL Query: " . $sql . "<br>"; // เพิ่มบรรทัดนี้เพื่อตรวจสอบ query

// Execute query
$result = mysqli_query($conn, $sql);

// Check if any results were found
if (mysqli_num_rows($result) > 0) {
    // Display results
    while ($row = mysqli_fetch_assoc($result)) {
        // แสดงข้อมูลสินค้าแต่ละรายการ
        echo "Product Name: " . $row["p_name"] . "<br>";
        echo "Description: " . $row["p_detail"] . "<br>";
        // แสดงข้อมูลอื่นๆ ตามต้องการ
    }
} else {
    echo "No products found matching your search.";
}

// Close connection
mysqli_close($conn);
?>
