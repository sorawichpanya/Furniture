<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // ตรวจสอบว่าชื่อผู้ใช้ซ้ำหรือไม่
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "ชื่อผู้ใช้นี้ถูกใช้แล้ว กรุณาเลือกชื่ออื่น!";
    } else {
        // บันทึกข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (name, phone, username, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $phone, $username, $password);
        
        if ($stmt->execute()) {
            header("Location: Login.php");
            exit();
        } else {
            echo "เกิดข้อผิดพลาด: " . $stmt->error;
        }
    }
}
?>
