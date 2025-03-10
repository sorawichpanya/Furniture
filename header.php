<?php
session_start();
?>
<nav>
    <?php if (isset($_SESSION['username'])): ?>
        <span>สวัสดี, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">ออกจากระบบ</a>
    <?php else: ?>
        <a href="Login.php">เข้าสู่ระบบ</a>
        <a href="Register.php">สมัครสมาชิก</a>
    <?php endif; ?>
</nav>
