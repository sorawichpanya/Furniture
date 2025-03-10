<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php';
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password']

    $sql = "SELECT * FROM Register WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // 's' คือชนิดข้อมูล string
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    // ตรวจสอบว่าเจอข้อมูลผู้ใช้หรือไม่
    if ($row) {
        // ตรวจสอบรหัสผ่านโดยใช้ password_verify
        if (password_verify($password, $row['password'])) {
            // เก็บข้อมูลที่จำเป็นใน session
            $_SESSION["username"] = $row['username'];
            $_SESSION["name"] = $row['name'];
            $_SESSION["phone"] = $row['phone'];

            // รีไดเร็กต์ไปยังหน้า index.php
            header("Location: index.php");

        } else {
            $_SESSION["Error"] = "<p>Your username or password is invalid</p>";
            header("Location: Login.php");
            exit();
        }
    } else {
        // ถ้าไม่พบ username
        $_SESSION["Error"] = "<p>Your username or password is invalid</p>";
        header("Location: Login.php");
        exit();
    }
} else {
    // ถ้าไม่มีข้อมูลจากฟอร์ม
    $_SESSION["Error"] = "<p>Please fill in both fields</p>";
    header("Location: Login.php");
    exit();
}
?>
