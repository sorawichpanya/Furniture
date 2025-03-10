<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
include_once("../connectdb.php");

// ดึงข้อมูลลูกค้าจากตาราง Register
$sql = "SELECT * FROM `Register` WHERE `id` = '{$_GET['id']}'";
$rs = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($rs);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EShopper - แก้ไขข้อมูลลูกค้า</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet"> 

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <!-- Topbar Content (เหมือนเดิม) -->
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid mb-5">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <!-- Category Menu (เหมือนเดิม) -->
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.php" class="nav-item nav-link">Home</a>
                            <a href="shop.php" class="nav-item nav-link">Shop</a>
                            <a href="contact.php" class="nav-item nav-link active">Contact</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- แก้ไขข้อมูลลูกค้า Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">แก้ไขข้อมูลลูกค้า</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col-lg-7 mb-5">
                <div class="contact-form">
                    <form method="post" action="">
                        <div class="control-group">
                            <label for="name">ชื่อ-สกุล:</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo htmlspecialchars($data['name']); ?>" required>
                        </div>

                        <div class="control-group mt-3">
                            <label for="phone">เบอร์โทรศัพท์:</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="<?php echo htmlspecialchars($data['phone']); ?>" required>
                        </div>

                        <div class="control-group mt-3">
                            <label for="username">ชื่อผู้ใช้:</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo htmlspecialchars($data['username']); ?>" required>
                        </div>

                        <div class="control-group mt-3">
                            <label for="password">รหัสผ่าน:</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   value="<?php echo htmlspecialchars($data['password']); ?>" required>
                        </div>

                        <div class="mt-4">
                            <button class="btn btn-primary py-2 px-4" type="submit">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- แก้ไขข้อมูลลูกค้า End -->

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // อัปเดตข้อมูลในฐานข้อมูล
        $sql = "UPDATE Register SET 
            name = '$name',
            phone = '$phone',
            username = '$username',
            password = '$password'
            WHERE id = '{$_GET['id']}'";
            
        echo "SQL: " . $sql . "<br>";  // แสดง SQL ที่ใช้

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('อัปเดตข้อมูลสำเร็จ!');
                window.location.href = 'contact.php?id={$_GET['id']}';
            </script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "');</script>";
        }
    }
    ?>

    <!-- ส่วนอื่นๆ ที่เหลือเหมือนเดิม -->
</body>
</html>
