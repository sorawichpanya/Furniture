<?php
session_start();
include 'connectdb.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ตะกร้าสินค้า</title>
<link href="http://212.80.215.178/Furniture/index.php" rel="stylesheet" type="text/css">
</head>

<body>
<h2>ตะกร้าสินค้า</h2>
<a href="index.php" class="btn btn-info">🔙 กลับไปช้อปต่อ</a>
<br><br>

<?php if (empty($_SESSION['cart'])) { ?>
    <p>🛒 ไม่มีสินค้าในตะกร้า</p>
<?php } else { ?>
    <table class="table table-bordered">
        <tr>
            <th>สินค้า</th>
            <th>ราคา</th>
            <th>จำนวน</th>
            <th>ราคารวม</th>
            <th>ลบ</th>
        </tr>
        <?php 
        $total = 0;
        foreach ($_SESSION['cart'] as $p_id => $item) {
            $sum = $item['price'] * $item['quantity'];
            $total += $sum;
        ?>
        <tr>
            <td><?=$item['name'];?></td>
            <td>฿<?=number_format($item['price'],0);?></td>
            <td><?=$item['quantity'];?></td>
            <td>฿<?=number_format($sum,0);?></td>
            <td><a href="remove_from_cart.php?p_id=<?=$p_id;?>" class="btn btn-danger">❌</a></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3" align="right">💰 ยอดรวมทั้งหมด</td>
            <td>฿<?=number_format($total,0);?></td>
            <td></td>
        </tr>
    </table>
    <a href="checkout.php" class="btn btn-success">✅ สั่งซื้อ</a>
<?php } ?>
</body>
</html>
