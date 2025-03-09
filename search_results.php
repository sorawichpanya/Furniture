<?php
include_once("connectdb.php");

// รับค่าคำค้นหาจากฟอร์ม
$search_query = isset($_POST['search_query']) ? $_POST['search_query'] : '';
$search_query = mysqli_real_escape_string($conn, $search_query); // ป้องกัน SQL injection

// จำนวนสินค้าต่อหน้า และตัวเลข offset สำหรับแบ่งหน้า (pagination)
$items_per_page = 10; // จำนวนสินค้าต่อหน้า
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // หน้าที่ผู้ใช้เลือก
$offset = ($page - 1) * $items_per_page;

// สร้าง SQL query สำหรับการค้นหา
$sql = "
    SELECT p_id, p_name, p_price, p_ext, 'bedroom' AS category FROM `bedroom`
    WHERE p_name LIKE '%$search_query%' OR p_detail LIKE '%$search_query%'
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'living_room' AS category FROM `living_room`
    WHERE p_name LIKE '%$search_query%' OR p_detail LIKE '%$search_query%'
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'bathroom' AS category FROM `bathroom`
    WHERE p_name LIKE '%$search_query%' OR p_detail LIKE '%$search_query%'
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'kitchen_room' AS category FROM `kitchen_room`
    WHERE p_name LIKE '%$search_query%' OR p_detail LIKE '%$search_query%'
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'garden' AS category FROM `garden`
    WHERE p_name LIKE '%$search_query%' OR p_detail LIKE '%$search_query%'
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'workroom' AS category FROM `workroom`
    WHERE p_name LIKE '%$search_query%' OR p_detail LIKE '%$search_query%'
    ORDER BY p_id ASC -- กำหนดการเรียงลำดับตาม p_id
    LIMIT $items_per_page OFFSET $offset"; // ใช้ LIMIT และ OFFSET เพื่อแบ่งหน้า

// Execute query
$rs = mysqli_query($conn , $sql);

// ตรวจสอบผลลัพธ์จาก query
if (mysqli_num_rows($rs) > 0) {
    while ($data = mysqli_fetch_assoc($rs)) {
        // จัดรูปแบบราคาด้วย number_format
        $formatted_price = number_format($data['p_price'], 2); // ใส่ 2 ทศนิยม
        
        // แสดงข้อมูลสินค้าแต่ละรายการ
        echo "<div class='product-item'>";
        echo "<h3>" . htmlspecialchars($data['p_name']) . "</h3>";
        echo "<p>Price: " . $formatted_price . "</p>";
        echo "<p>Category: " . htmlspecialchars($data['category']) . "</p>";
        echo "</div>";
    }
} else {
    echo "No products found matching your search.";
}

// Close connection
mysqli_close($conn);
?>
