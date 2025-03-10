<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // เตรียม SQL Query เพื่อค้นหาผู้ใช้
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $name, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["name"] = $name;
            header("Location: dashboard.php"); // ไปที่หน้าหลักหลังล็อกอินสำเร็จ
            exit();
        } else {
            $_SESSION["Error"] = "รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        $_SESSION["Error"] = "ไม่พบชื่อผู้ใช้นี้!";
    }
    
    $stmt->close();
    header("Location: Login.php"); // กลับไปหน้า Login พร้อมแจ้งข้อผิดพลาด
    exit();
}
?>
