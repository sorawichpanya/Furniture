<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="bootstrap/css/bootstrap.rtl.min.css">

</head>
<body>
<div class="container">
    สมัครสมาชิก
    <br>

    <form method="POST" action="">
        ชื่อ <input type="text" name="firstname" class="form-control" required><br>
        นามสกุล <input type="text" name="lastname" class="form-control" required><br>
        เบอร์โทรศัพท์ <input type="number" name="phone" class="form-control" required><br>
        Username <input type="text" name="username" class="form-control" required><br>
        Password <input type="password" name="password" class="form-control" required><br>

        <input type="submit" name="submit" value="บันทึก">
        <input type="reset" name="cancel" value="ยกเลิก"> <br>

        <a href="Login.php">Login</a>
    </form>
</div>
</body>
</html>