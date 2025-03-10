<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'connectdb.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// เข้ารหัส password ด้วย sha512
$password = hash('sha512', $password);

// ใช้ prepared statement ป้องกัน SQL Injection
$sql = "SELECT * FROM Register WHERE username = ? AND password = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $username, $password);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_array($result);

// ตรวจสอบข้อมูลที่ดึงมาได้
if ($row) {
    $_SESSION["username"] = $row['username'];
    $_SESSION["password"] = $row['password'];
    $_SESSION["name"] = $row['name'];
    $_SESSION["phone"] = $row['phone'];
    
    header("location:index.php");
    exit();
} else {
    $_SESSION["Error"] = "<p> Your username or password is invalid</p>";
    header("location:login.php");
    exit();
}
?>
