<?php
session_start();
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "กรุณากรอก Username และ Password";
    } else {
        // ค้นหาผู้ใช้ในฐานข้อมูล
        $sql = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            // ตรวจสอบรหัสผ่าน
            if (password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username']; // เก็บ session
                $_SESSION['user_id'] = $row['id'];
                header("Location: dashboard.php"); // เปลี่ยนเส้นทางไปหน้า dashboard
                exit();
            } else {
                $error = "รหัสผ่านไม่ถูกต้อง";
            }
        } else {
            $error = "ไม่พบผู้ใช้งานนี้";
        }
    }
}
?>