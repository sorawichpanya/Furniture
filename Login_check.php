<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // รับค่าจากฟอร์มและลบช่องว่างที่ไม่จำเป็น
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) { 
        die("กรุณากรอกชื่อผู้ใช้และรหัสผ่านให้ครบถ้วน!");
    } 
    $sql = "SELECT username, password FROM Register WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("เกิดข้อผิดพลาดในการเตรียมคำสั่ง SQL: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (hash('sha512', $password) === $row['password']) {
            session_regenerate_id(true);
            $_SESSION['username'] = $username;
            
            header("Location: index.php");
            exit();
        } else {
            echo "รหัสผ่านไม่ถูกต้อง!";
        }
    } else {
        echo "ไม่พบบัญชีนี้!";
    }

    $stmt->close();
    $conn->close();
} else 
{
    echo "ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง!";
} 
?>
