<?php
session_start();
?>
<style>
    .header-right {
    position: absolute;
    top: 15px;
    right: 20px;
    display: flex;
    gap: 15px; /* ระยะห่างระหว่างปุ่ม */
}

.btn-auth {
    color: #d48c8c;  /* เปลี่ยนสีปุ่ม */
    text-decoration: none;
    font-weight: bold;
    padding: 5px 10px;
    border-radius: 5px;
    transition: 0.3s;
}

.btn-auth:hover {
    color: #fff;
    background-color: #d48c8c;
}

</style>

<nav>
    <?php if (isset($_SESSION['username'])): ?>
        <span>Welcome, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="Login.php">Login</a>
        <a href="Register.php">Register</a>
    <?php endif; ?>
</nav>