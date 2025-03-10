<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // เข้ารหัส password ด้วย sha512
    $password = hash('sha512', $password);

    $sql = "SELECT * FROM Register WHERE Username='$username' AND Password='$password'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // กำหนดค่าลงใน session
        $_SESSION["username"] = $row['Username'];
        $_SESSION["password"] = $row['Password'];
        $_SESSION["name"] = $row['Name'];
        $_SESSION["phone"] = $row['Phone'];

        // เปลี่ยนเส้นทางไปยังหน้า index.php
        header("Location: index.php");
        exit();
    } else {
        $_SESSION["Error"] = "<p>Your username or password is invalid</p>";
        header("Location: login.php");
        exit();
    }
} else {
    // ถ้าผู้ใช้พยายามเข้าไฟล์โดยตรง
    $_SESSION["Error"] = "<p>Invalid request method.</p>";
    header("Location: login.php");
    exit();
}
?>
