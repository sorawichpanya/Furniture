<?php
include 'connectdb.php';
session_start();


$username = $_POST['username'];
$password = $_POST['password'];



$password = hash('Sha512', $password);


$sql = "SELECT * FROM Register WHERE Username='$username' AND password='$password'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);


if ($row) {
    $_SESSION["username"] = $row['username'];
    $_SESSION["pw"] = $row['password'];
    $_SESSION["name"] = $row['name'];
    $_SESSION["phone"] = $row['phone'];
    header("Location: index.php");
   
} else {
    $_SESSION["Error"] = "<p> Your username or password is invalid</p>";
    header("Location: Login.php");
    exit();
}
echo $show;
?>
