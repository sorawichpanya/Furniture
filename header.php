<?php
session_start();
?>
<style>
    nav {
        width: 100%;
        background: #f8f9fa;
        padding: 10px;
    }
    nav span, nav a {
        float: right;
        margin-left: 10px;
        text-decoration: none;
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