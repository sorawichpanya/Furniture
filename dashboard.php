<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

echo "ยินดีต้อนรับ, " . $_SESSION["username"];
?>

<a href="logout.php">ออกจากระบบ</a>
