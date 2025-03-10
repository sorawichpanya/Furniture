<?php
include_once("../connectdb.php");
$sql = "SELECT * FROM `products` WHERE `p_id` = '{$_GET['id']}' ";
$rs = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($rs);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>แก้ไขข้อมูลสินค้า</title>
</head>
<body>
<h1>ฟอร์มแก้ไขข้อมูลสินค้า</h1>
<form method="post" action="" enctype="multipart/form-data">
    ชื่อสินค้า: <input type="text" name="pname" value="<?php echo $data['p_name'];?>" required autofocus> <br>
    ราคา: <input type="text" name="pprice" value="<?php echo $data['p_price'];?>" required> <br>
    รายละเอียดสินค้า: <textarea name="pdetail" rows="5" cols="40" required><?php echo $data['p_detail'];?></textarea> <br>
    รูปภาพปัจจุบัน: <img src="../images/<?php echo $data['p_id'] . '.' . $data['p_ext'];?>" width="100" alt="Product Image"> <br>
    รูปภาพใหม่ (ถ้ามี): <input type="file" name="pimage" accept="image/*"> <br>
    <button type="submit">บันทึก</button>
</form>
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
        echo "alert('แก้ไขสำเร็จ!'); window.location='update.php?id={$_GET['id']}';";
        echo "</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการแก้ไขข้อมูล');</script>";
    }
}
?>
</body>
</html>