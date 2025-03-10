<?php
include 'connectdb.php';

// รับค่าจากฟอร์ม
$name = $_POST['name'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];

$password=hash('Sha512',$password);
// เพิ่มข้อมูลลงฐานข้อมูล
$sql = "INSERT INTO Register (name, phone, username, password) 
VALUES ('$name', '$phone', '$username', '$password')";

$result = mysqli_query($conn, $sql);

if ($result) {
    header("Location: Login.php");
    exit();
}
    </script>";
    exit(); // หยุดการทำงานของ PHP
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    echo "<script> alert('บันทึกข้อมูลไม่ได้'); </script>";
}

mysqli_close($conn);
?>
