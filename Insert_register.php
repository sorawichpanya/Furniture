<?php
include 'connectdb.php';
//รับค่าตัวแปรมาจากไฟล์ register
$name = $_POST['name'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];

//ค่าสั่งเพิ่มข้อมูลลงดาราง Register
$sql = "INSERT INTO Register (name, phone, username, password)
Values('$name', '$phone', '$username', '$password')";
$result=mysqli_query($conn, $sql);
if($result){
    echo "<script> alert('บันทึกข้อมูลเรียบร้อย'); </script>";
    echo "<script> window.location='Register.php'; </script>";
}else{
    echo "Error:".$sql."<br>".mysqli_error($conn);
    echo "<script> alert('บันทึกข้อมูลไม่ได้'); </script>";
}
mysqli_close($conn);
?>