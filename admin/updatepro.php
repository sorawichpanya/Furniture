<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit;
}

include_once("connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $table_name = $_POST['table_name'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $size = $_POST['size'];
    $price = $_POST['price'];

    // SQL query เพื่ออัปเดตข้อมูล
    $sql = "UPDATE `$table_name` SET 
            p_name = '$name',
            p_detail = '$description',
            p_color = '$color',
            p_size = '$size',
            p_price = '$price'
            WHERE p_id = '$product_id'";

    if (mysqli_query($conn, $sql)) {
        // อัปเดตข้อมูลสำเร็จ
        header("Location: products.php?table_name=" . urlencode($table_name) . "&update=success"); // กลับไปยังหน้า products.php
        exit;
    } else {
        // หากมีข้อผิดพลาดในการอัปเดต
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    // หากไม่ได้ส่งข้อมูลผ่าน POST method
    echo "ไม่ได้ส่งข้อมูล";
}
?>
