<?php
include_once("../connectdb.php");
$sql = "SELECT * FROM `products` WHERE `p_id` = '{$_GET['id']}' ";
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
                    <!-- Navbar Content (เหมือนเดิม) -->
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

    <!-- แก้ไขข้อมูลสินค้า Start -->
    <div class="container-fluid pt-5">
        <div class="text-center mb-4">
            <h2 class="section-title px-5"><span class="px-2">แก้ไขข้อมูลลูกค้า</span></h2>
        </div>
        <div class="row px-xl-5">
            <div class="col-lg-7 mb-5">
                <div class="contact-form">
                    <form method="post" action="" enctype="multipart/form-data">
                        <div class="control-group">
                            <label for="pname">ชื่อ:</label>
                            <input type="text" class="form-control" id="pname" name="pname" value="<?php echo $data['name'];?>" required autofocus>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <label for="pphone">เบอร์โทรศัพท์:</label>
                            <input type="text" class="form-control" id="pphone" name="pphone" value="<?php echo $data['phone'];?>" required>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <label for="pdetail">รายละเอียดสินค้า:</label>
                            <textarea class="form-control" rows="6" id="pdetail" name="pdetail" required><?php echo $data['username'];?></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <label for="pdetail">รายละเอียดสินค้า:</label>
                            <textarea class="form-control" rows="6" id="pdetail" name="pdetail" required><?php echo $data['password'];?></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <label>รูปภาพปัจจุบัน:</label><br>
                            <img src="../images/<?php echo $data['p_id'] . '.' . $data['p_ext'];?>" width="100" alt="Product Image">
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="control-group">
                            <label for="pimage">รูปภาพใหม่ (ถ้ามี):</label>
                            <input type="file" class="form-control" id="pimage" name="pimage" accept="image/*">
                            <p class="help-block text-danger"></p>
                        </div>
                        <div>
                            <button class="btn btn-primary py-2 px-4" type="submit">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- แก้ไขข้อมูลสินค้า End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <!-- Footer Content (เหมือนเดิม) -->
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pdetail = $_POST['pdetail'];
    $pimageUpdated = false;

    // ตรวจสอบว่ามีการอัปโหลดรูปภาพใหม่หรือไม่
    if (isset($_FILES['pimage']) && $_FILES['pimage']['error'] == 0) {
        $fileTmpPath = $_FILES['pimage']['tmp_name'];
        $fileName = $_FILES['pimage']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // ตรวจสอบชนิดไฟล์ที่อนุญาต
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = $data['p_id'] . '.' . $fileExtension; // ตั้งชื่อไฟล์ใหม่ตามรหัสสินค้า
            $uploadPath = "../images/" . $newFileName;

            // ลบไฟล์เก่าก่อน (ถ้ามี)
            $oldFilePath = "../images/" . $data['p_id'] . '.' . $data['p_ext'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }

            // ย้ายไฟล์ใหม่ไปยังโฟลเดอร์
            if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                $pimageUpdated = true;
            } else {
                echo "<script>alert('ไม่สามารถอัปโหลดรูปภาพได้');</script>";
            }
        } else {
            echo "<script>alert('ชนิดไฟล์ไม่ถูกต้อง');</script>";
        }
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    if ($pimageUpdated) {
        // หากอัปเดตรูปภาพใหม่ ให้แก้ไข `p_ext`
        $sql = "UPDATE products 
                SET p_name='{$pname}', p_price='{$pprice}', p_detail='{$pdetail}', p_ext='{$fileExtension}' 
                WHERE p_id='{$_GET['id']}'";
    } else {
        // หากไม่ได้อัปเดตรูปภาพใหม่ ไม่ต้องแก้ไข `p_ext`
        $sql = "UPDATE products 
                SET p_name='{$pname}', p_price='{$pprice}', p_detail='{$pdetail}' 
                WHERE p_id='{$_GET['id']}'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>";
        echo "alert('แก้ไขสำเร็จ!'); window.location='contact.php?id={$_GET['id']}';";
        echo "</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล');</script>";
    }
}
?>
