<?php
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่ามีการส่งค่า `order_id` หรือไม่
    if (isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $province = $_POST['province'];
        $zip_code = $_POST['zip_code'];
        $total_price = $_POST['total_price'];
        $status = $_POST['status'];  // รับค่าจากฟอร์ม

        // ตรวจสอบว่า `status` เป็นค่าสถานะที่ถูกต้องตามที่กำหนดใน ENUM
        $valid_status = ['pending', 'shipped', 'completed', 'cancelled'];
        if (!in_array($status, $valid_status)) {
            echo "❌ สถานะไม่ถูกต้อง!";
            exit;
        }

        // สร้างคำสั่ง SQL สำหรับอัพเดตข้อมูล
        $sql = "UPDATE orders SET 
                    full_name = ?, 
                    phone = ?, 
                    address = ?, 
                    province = ?, 
                    zip_code = ?, 
                    total_price = ?, 
                    status = ? 
                WHERE order_id = ?";

        // เตรียมคำสั่ง SQL
        if ($stmt = $conn->prepare($sql)) {
            // ผูกค่าพารามิเตอร์กับคำสั่ง SQL
            $stmt->bind_param("sssssssi", $full_name, $phone, $address, $province, $zip_code, $total_price, $status, $order_id);

            // เรียกใช้คำสั่ง SQL
            if ($stmt->execute()) {
                echo "ข้อมูลอัพเดตสำเร็จ!";
                header("Location: orders_list.php"); // กลับไปที่หน้ารายการคำสั่งซื้อ
                exit;
            } else {
                echo "❌ ไม่สามารถอัพเดตข้อมูลได้: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "❌ ไม่สามารถเตรียมคำสั่ง SQL ได้";
        }
    } else {
        echo "❌ ไม่พบ order_id";
    }
    $conn->close();
}
?>
