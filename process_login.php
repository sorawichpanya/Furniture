<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM Register WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION["name"] = $name;
        $_SESSION["username"] = $username;
        header("Location: dashboard.php"); // ไปหน้าหลักของผู้ใช้
        
    } else {
        echo "อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
    }
    exit();
}
?>
