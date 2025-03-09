<?php
session_start();
include 'connectdb.php';
?>
<!doctype html>
<html>
<a href="http://212.80.215.178/Furniture/index.php">คลิกที่นี่เพื่อไปยังเว็บไซต์</a>

<head>
<meta charset="utf-8">
<title>รายการสินค้า</title>
</head>

<body>
<h2>รายการสินค้าทั้งหมด</h2>
<p><a href="basket.php" class="btn btn-success">🛒 ตะกร้าสินค้า</a></p>

<?php
$sql = "SELECT * FROM product";
$rs = mysqli_query($conn, $sql);
while ($data = mysqli_fetch_array($rs, MYSQLI_BOTH)) {
?>
  <div class="col-md-4">
    <div class="thumbnail">
      <img src="images/<?=$data['p_picture'];?>" width="200">
      <div class="caption">
        <h4><?=$data['p_name'];?></h4>
        <h4>฿<?=number_format($data['p_price'],0);?></h4>
        <p>
          <a href="detail.php?p_id=<?=$data['p_id'];?>&category=<?=$data['p_type'];?>" class="btn btn-secondary">👁 View Detail</a>
          <a href="add_to_cart.php?p_id=<?=$data['p_id'];?>" class="btn btn-primary">🛒 Add To Cart</a>
        </p>
      </div>
    </div>
  </div>
<?php } ?>
</body>
</html>
