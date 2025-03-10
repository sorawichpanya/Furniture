<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['user'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION["user_id"] = $id;
        $_SESSION["username"] = $username;
        header("Location: dashboard.php"); // ไปหน้าหลักของผู้ใช้
        exit();
    } else {
        echo "อีเมลหรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>
