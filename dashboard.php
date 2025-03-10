<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: Login.php");
    exit();
}

echo "ยินดีต้อนรับ, " . $_SESSION["name"];
?>

<a href="logout.php">ออกจากระบบ</a>
