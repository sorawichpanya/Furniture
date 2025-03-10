<?php
include('db_connection.php');

$priceCondition = ""; // กรองราคาถ้ามีเงื่อนไข
if (isset($_GET['price']) && $_GET['price'] !== "all") {
    $priceRanges = explode(",", $_GET['price']);
    $conditions = [];

    foreach ($priceRanges as $range) {
        $priceFilter = explode("-", $range);
        $minPrice = (int)$priceFilter[0];
        $maxPrice = isset($priceFilter[1]) ? (int)$priceFilter[1] : PHP_INT_MAX;
        $conditions[] = "(price BETWEEN $minPrice AND $maxPrice)";
    }
    $priceCondition = "WHERE " . implode(" OR ", $conditions);
}

// ดึงสินค้าทุกหมวดที่ตรงกับเงื่อนไข
$sql = "
    SELECT * FROM (
        SELECT p_id, name, price, image FROM bedroom
        UNION ALL
        SELECT p_id, name, price, image FROM living_room
        UNION ALL
        SELECT p_id, name, price, image FROM kitchen_room
        UNION ALL
        SELECT p_id, name, price, image FROM bathroom
        UNION ALL
        SELECT p_id, name, price, image FROM garden
        UNION ALL
        SELECT p_id, name, price, image FROM workroom
    ) AS combined_table
    $priceCondition";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
        echo "<p>" . $row['name'] . "</p>";
        echo "<p>ราคา: " . $row['price'] . " บาท</p>";
        echo "</div>";
    }
} else {
    echo "ไม่พบสินค้าที่ตรงกับเงื่อนไข";
}
?>
