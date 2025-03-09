<?php
include_once("connectdb.php");

// รับค่าคำค้นหา
$search_query = $_GET['search_query']; // หรือ $_POST['search_query'] ถ้าใช้ POST method

// ป้องกัน SQL injection โดยใช้ mysqli_real_escape_string
$search_query = mysqli_real_escape_string($conn, $search_query);

// สร้าง SQL query
$sql = "SELECT * FROM `products` WHERE `p_name` LIKE '%$search_query%' OR `p_description` LIKE '%$search_query%'";

// Execute query
$result = mysqli_query($conn, $sql);

// Check if any results were found
if (mysqli_num_rows($result) > 0) {
    // Display results
    while ($row = mysqli_fetch_assoc($result)) {
        // แสดงข้อมูลสินค้าแต่ละรายการ
        echo "Product Name: " . $row["p_name"] . "<br>";
        echo "Description: " . $row["p_description"] . "<br>";
        // แสดงข้อมูลอื่นๆ ตามต้องการ
    }
} else {
    echo "No products found matching your search.";
}

// Close connection
mysqli_close($conn);
?>
