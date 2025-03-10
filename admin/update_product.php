<?php
session_start();

include_once("connectdb.php");

// ตรวจสอบว่ามีการส่ง `product_id` และ `table_name` มาหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id']) && isset($_POST['table_name'])) {
        $product_id = $_POST['product_id'];
        $table_name = $_POST['table_name'];

        // รับค่าจากฟอร์ม
        $product_name = $_POST['name'];
        $description = $_POST['description'];
        $color = $_POST['color'];
        $size = $_POST['size'];
        $price = $_POST['price'];

        // สร้างคำสั่ง SQL สำหรับอัปเดตข้อมูล
        $sql_update = "UPDATE `$table_name` SET 
                            p_name = ?, 
                            p_detail = ?, 
                            p_color = ?, 
                            p_size = ?, 
                            p_price = ? 
                        WHERE p_id = ?";

        // เตรียมคำสั่ง SQL
        if ($stmt = $conn->prepare($sql_update)) {
            // ผูกค่าพารามิเตอร์กับคำสั่ง SQL
            $stmt->bind_param("ssssdi", $product_name, $description, $color, $size, $price, $product_id);

            // เรียกใช้คำสั่ง SQL
            if ($stmt->execute()) {
                echo "ข้อมูลอัปเดตสำเร็จ!";
                header("Location: product_list.php"); // เปลี่ยนไปหน้ารายการสินค้า
                exit;
            } else {
                echo "❌ ไม่สามารถอัปเดตข้อมูลได้: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "❌ ไม่สามารถเตรียมคำสั่ง SQL ได้";
        }
    } else {
        echo "❌ ข้อมูลไม่ครบถ้วน!";
    }
} else {
    echo "❌ ไม่พบข้อมูล POST";
}

$conn->close();
?>
