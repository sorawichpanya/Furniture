<?php
include_once("connectdb.php");

$sql = "SELECT * FROM `trendy` ORDER BY `p_id` ASC";
$rs = mysqli_query($conn , $sql);

?>

<!-- Products Start -->
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Trendy Products</span></h2>
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
                        <img src="img/trendy/<?php echo $data['p_id']; ?>.<?php echo $data['p_ext']; ?>" >
                    </div>
                    <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                        <!-- แสดงชื่อสินค้า -->
                        <h6 class="text-truncate mb-3"><?php echo $data['p_name']; ?></h6>
                        <div class="d-flex justify-content-center">
                            <!-- แสดงราคา -->
                            <h6>฿<?php echo $data['p_price']; ?></h6>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between bg-light border">
                        <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-eye text-primary mr-1"></i>View Detail</a>
                        <a href="" class="btn btn-sm text-dark p-0"><i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>