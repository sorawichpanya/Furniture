<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('connectdb.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Username = trim($_POST['Username']);
    $password = trim($_POST['password']);

    // ป้องกัน SQL Injection
    $Username = mysqli_real_escape_string($conn, $Username);

    // ค้นหาผู้ใช้ในฐานข้อมูล
    $query = "SELECT * FROM Register WHERE Username = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $Username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT); {
                // เก็บข้อมูลผู้ใช้ใน Session
                $_SESSION['Username'] = $Username;

                // **Redirect ไปหน้า index.php**
                header("Location: index.php");
                exit(); // ต้องมี exit เพื่อหยุดการทำงานของ script
            } else {
                $_SESSION["Error"] = "Invalid Username or password.";
            }
        } else {
            $_SESSION["Error"] = "Invalid Username or password.";
        }

        mysqli_stmt_close($stmt); // ปิด statement
    } else {
        $_SESSION["Error"] = "Database query error.";
    }

    mysqli_close($conn); // ปิดการเชื่อมต่อฐานข้อมูล

    // ถ้า Login ไม่สำเร็จ ให้ Redirect กลับไปหน้า Login.php
    header("Location: Login.php");
    exit();
}
?>
