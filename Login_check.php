<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM Register WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username; // ✅ บันทึก session
            header("Location: index.php"); // ✅ รีไดเรกไปหน้าแรก
            exit();
        } else {
            echo "รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        echo "ไม่พบบัญชีนี้!";
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo "ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง!";
}
?>
