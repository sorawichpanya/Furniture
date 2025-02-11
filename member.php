<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>EShopper - Bootstrap Shop Template</title>
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



<?php
include_once("connectdb.php");

$sql = "SELECT * FROM `member` ORDER BY `m_id` ASC";
$rs = mysqli_query($conn , $sql);

?>

<!-- Products Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Members Group</span></h2>
    </div>
    <div class="row px-xl-5">
        <?php
        while ($data = mysqli_fetch_array($rs)){

        ?>
            <!-- เรียงสินค้าในแต่ละคอลัมน์ -->
            <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
                <div class="card product-item border-0 mb-4">
                    <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                        <!-- แสดงรูปภาพจากฐานข้อมูล -->
                        <img src="img/Member/<?php echo $data['m_id']; ?>.<?php echo $data['m_ext']; ?>" >
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <!-- แสดงชื่อสินค้า -->
                        <h6 class="text-truncate mb-3"><?php echo $data['m_name']; ?></h6>
                        <div class="d-flex justify-content-center">
                            <!-- แสดงราคา -->
                            <h6><?php echo $data['m_no']; ?></h6>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>