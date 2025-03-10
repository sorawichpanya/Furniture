<?php
// เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// คำสั่ง SQL ดึงสินค้าหมวดหมู่ living_room
$query = "SELECT id, name, price, image FROM products WHERE category = ?";
$stmt = mysqli_prepare($conn, $query);
$category = 'living_room';
mysqli_stmt_bind_param($stmt, "s", $category);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// ตรวจสอบว่าสินค้ามีอยู่หรือไม่
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        // แปลง price เป็นตัวเลขเต็ม (ตัดทศนิยม)
        $price = (int)$row['price']; // หรือใช้ floor($row['price']) หากต้องการปัดลง
        echo '<div class="col-lg-4 col-md-6 col-sm-12 pb-1 product-item" data-price="' . $price . '">';
        echo '<div class="card product-item border-0 mb-4 shadow-sm">';
        echo '<div class="card-header product-img position-relative overflow-hidden bg-transparent border-0 p-0">';
        echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" class="img-fluid w-100" style="max-height: 300px; object-fit: cover; border-radius: 5px;">';
        echo '</div>';
        echo '<div class="card-body border-left border-right text-center p-0 pt-4 pb-3">';
        echo '<h6 class="text-truncate mb-3">' . htmlspecialchars($row['name']) . '</h6>';
        echo '<div class="d-flex justify-content-center">';
        echo '<h6>฿' . number_format($row['price'], 2) . '</h6>'; // แสดงราคาแบบสวยงามใน UI
        echo '</div>';
        echo '</div>';
        echo '<div class="card-footer d-flex justify-content-between bg-light border">';
        echo '<a href="detail.php?p_id=' . htmlspecialchars($row['id']) . '&category=living_room" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>';
        echo '<a href="#" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p class="text-center w-100">No products available in this category.</p>';
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
