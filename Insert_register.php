<?php
include 'connectdb.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// รับค่าจากฟอร์ม
$name = mysqli_real_escape_string($conn, $_POST['name']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

// เข้ารหัส password
$hashed_password = hash('sha512', $password);

// เพิ่มข้อมูลลงฐานข้อมูล
$sql = "INSERT INTO Register (name, phone, username, password) 
        VALUES ('$name', '$phone', '$username', '$hashed_password')";

$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: Login.php");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    echo "<script> alert('บันทึกข้อมูลไม่ได้'); </script>";
}

mysqli_close($conn);
?>
