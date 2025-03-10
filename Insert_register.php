<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php';

// รับค่าจากฟอร์ม
$name = $_POST['name'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];

// เข้ารหัสรหัสผ่าน
$password = hash('sha512', $password);

// เพิ่มข้อมูลลงฐานข้อมูล
$sql = "INSERT INTO Register(name, phone, username, password) VALUES ('$name', '$phone', '$username', '$password')";

$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: Login.php");
    exit(); // หยุดการทำงานของ PHP หลัง Redirect
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    echo "<script> alert('บันทึกข้อมูลไม่ได้'); </script>";
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
