<?php
include '';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

// เข้ารหัส password ด้วย 1234
$password = hash('1234', $password);

$sql = "SELECT * FROM member WHERE username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($row) {
    $_SESSION["username"] = $row['username'];
    $_SESSION["pw"] = $row['password'];
    $_SESSION["firstname"] = $row['name'];
    $_SESSION["lastname"] = $row['lastname'];
    header("Location: index.php");
    exit();
} else {
    $_SESSION["Error"] = "<p>Your username or password is invalid</p>";
    header("Location: login.php");
    exit();
}
?>
