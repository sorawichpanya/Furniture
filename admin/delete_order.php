<?php
include_once("connectdb.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'] ?? null;

    if ($order_id) {
        // ลบข้อมูลที่เกี่ยวข้องใน order_items ก่อน
        $delete_items_sql = "DELETE FROM order_items WHERE order_id = ?";
        $stmt_items = $conn->prepare($delete_items_sql);

        if ($stmt_items === false) {
            die("❌ Error preparing statement for order_items: " . $conn->error);
        }

        $stmt_items->bind_param("i", $order_id);

        if ($stmt_items->execute()) {
            // ลบข้อมูลจากตาราง orders หลังจากลบใน order_items
            $delete_order_sql = "DELETE FROM orders WHERE order_id = ?";
            $stmt_order = $conn->prepare($delete_order_sql);

            if ($stmt_order === false) {
                die("❌ Error preparing statement for orders: " . $conn->error);
            }

            $stmt_order->bind_param("i", $order_id);

            if ($stmt_order->execute()) {
                echo "✅ ลบข้อมูลคำสั่งซื้อเรียบร้อย";
                header("Location: index.php"); // กลับไปที่หน้า orders_list
            } else {
                echo "❌ เกิดข้อผิดพลาดในการลบข้อมูลจาก orders: " . $stmt_order->error;
            }

            $stmt_order->close();
        } else {
            echo "❌ เกิดข้อผิดพลาดในการลบข้อมูลจาก order_items: " . $stmt_items->error;
        }

        $stmt_items->close();
    } else {
        echo "❌ ไม่พบข้อมูลคำสั่งซื้อ";
    }

    $conn->close();
}
?>
