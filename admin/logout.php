<?php
session_start();
session_destroy(); // ลบ Session ทั้งหมด
header("Location: login.php"); // เปลี่ยนเส้นทางไปยังหน้า login
exit;
?>
