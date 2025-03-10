<?php
include 'connectdb.php';

// รับค่าจากฟอร์ม
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);
$username = trim($_POST['username']);
$password = trim($_POST['password']);

// เข้ารหัสรหัสผ่าน
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// เพิ่มข้อมูลลงฐานข้อมูล (ใช้ prepared statements เพื่อป้องกัน SQL Injection)
$stmt = mysqli_prepare($conn, "INSERT INTO Register (name, phone, username, password) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $username, $password_hash);

if (mysqli_stmt_execute($stmt)) {
    header("Location: Login.php");
    exit(); // หยุดการทำงานของ PHP
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
    echo "<script>alert('บันทึกข้อมูลไม่ได้');</script>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>