<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title></title>
</head>

<body>

<?php
$servername = "212.80.215.178";
$host = "localhost";
$user = "root"; 
$pass = "12345678P";
$db = "FurnitureFunny";

$conn = mysqli_connect($servername,$host,$user,$pass) or die ("เชื่อมต่อบ่ได้");

mysqli_select_db($conn,$db) or die ("เลือกฐานข้อมูลบ่ได้");
mysqli_query($conn, "set names utf8");


?>
</body>
</html>
