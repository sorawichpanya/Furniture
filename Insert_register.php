<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);


    if (empty($name) || empty($phone) || empty($username) || empty($password)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน'); window.location='Register.php';</script>";
        exit();
    }


    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $sql = "INSERT INTO Register (name, phone, username, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $phone, $username, $hashed_password);

    if ($stmt->execute()) {
        header("Location: Login.php");
        exit(); 
    } else {
        echo "<script>alert('บันทึกข้อมูลไม่ได้: " . $stmt->error . "'); window.location='Register.php';</script>";
    }


    $stmt->close();
    $conn->close();
} else {
    echo "<script>alert('ไม่อนุญาตให้เข้าถึงหน้านี้โดยตรง'); window.location='Register.php';</script>";
}
?>
