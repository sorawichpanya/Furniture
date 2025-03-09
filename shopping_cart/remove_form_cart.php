<?php
session_start();

$p_id = $_GET['p_id'];

if (isset($_SESSION['cart'][$p_id])) {
    unset($_SESSION['cart'][$p_id]);
}

header("Location: basket.php");
exit();
