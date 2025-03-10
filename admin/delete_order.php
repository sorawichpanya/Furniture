<?php
include_once("connectdb.php");

$order_id = $_POST['order_id'] ?? null;
echo "Order ID: $order_id"; // พิมพ์เพื่อเช็คค่า

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'] ?? null;

    if ($order_id) {
        // คำสั่ง SQL สำหรับการลบข้อมูลจากตาราง orders
        $sql = "DELETE FROM orders WHERE order_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("❌ Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("i", $order_id);

        if ($stmt->execute()) {
            echo "✅ ลบข้อมูลคำสั่งซื้อเรียบร้อย";
            header("Location: index.php"); // กลับไปที่หน้า orders_list
        } else {
            echo "❌ เกิดข้อผิดพลาดในการลบข้อมูล: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "❌ ไม่พบข้อมูลคำสั่งซื้อ";
    }

    $conn->close();
}
?>
