<?php
include 'condb.php';
//รับค่าตัวแปรมาจากไฟล์ register
$name = $_POST['firstname'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = $_POST['password'];

//ค่าสั่งเพิ่มข้อมูลลงดาราง member
$sql = "INSERT INTO Regis (name, email, username, password)
Values('$name', '$email',  '$username', '$password')";
$result=mysqli_query($conn, $sql);
if($result){
    echo "<script> alert('บันทึกข้อมูลเรียบร้อย'); </script>";
    echo "<script> window.location='register.php'; </script>";
}else{
    echo "Error:".$sql."<br>".mysqli_error($conn);
    echo "<script> alert('บันทึกข้อมูลไม่ได้'); </script>";
}
mysqli_close($conn);
?>