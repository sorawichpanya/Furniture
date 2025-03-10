<?php
session_start();
?>
<?php
// ตรวจสอบหน้าปัจจุบัน
$currentPage = basename($_SERVER['PHP_SELF']); // ได้ชื่อไฟล์ เช่น 'index.php' หรือ 'products.php'

include_once("connectdb.php");

// รับคำค้นจาก URL ถ้ามี
$search = isset($_GET['search']) ? $_GET['search'] : '';

// สร้างคำสั่ง SQL สำหรับค้นหาผลิตภัณฑ์
$sql = "SELECT * FROM `Just_arrived`"; // สามารถเปลี่ยนชื่อ table ตามต้องการ

// ถ้ามีการค้นหา
if (!empty($search)) {
    $sql .= " WHERE p_name LIKE '%$search%' OR p_detail LIKE '%$search%'"; // กรองสินค้าตามชื่อหรือรายละเอียด
}

$rs = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Product Admin - Dashboard HTML Template</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
</head>

<body>
    <nav class="navbar navbar-expand-xl">
        <div class="container h-100">
            <a class="navbar-brand" href="index.php">
                <h1 class="tm-site-title mb-0">Product Admin</h1>
            </a>
            <button class="navbar-toggler ml-auto mr-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars tm-nav-icon"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto h-100">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $currentPage == 'products.php' ? 'active' : ''; ?>" href="products.php">
                            <i class="fas fa-shopping-cart"></i> Products
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                            </ul>
                        <?php else : ?>
                            <a class="nav-link" href="login.php">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>    
<form action="products.php" method="get">
    <input type="text" name="search" placeholder="Search products" class="form-control mb-3">
    <button type="submit" class="btn btn-primary">Search</button>
</form>
<div class="container mt-5">
      <div class="row tm-content-row">
        <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 tm-block-col">
        <div class="tm-bg-primary-dark tm-block tm-block-products">
<?php
include_once("connectdb.php");

// รับ table_name จาก URL ถ้าไม่มีให้ใช้ 'Just_arrived' เป็นค่าเริ่มต้น
$table_name = isset($_GET['table_name']) ? $_GET['table_name'] : 'Just_arrived';

// ถ้า table_name เป็น `user` หรือไม่มีค่าให้ป้องกันการดึงข้อมูลจากตารางนั้น
if (empty($table_name) || $table_name == 'user') {
    die('Invalid category.');
}
$search = isset($_GET['search']) ? $_GET['search'] : '';
// ดึงข้อมูลสินค้าจากตารางที่เลือก
$sql = "SELECT * FROM `$table_name` WHERE p_name LIKE ? OR p_detail LIKE ?";
$rs = mysqli_query($conn, $sql);
if ($stmt = mysqli_prepare($conn, $sql)) {
    // กำหนดค่าคำค้นหา (ใช้เครื่องหมาย % เพื่อการค้นหาที่ยืดหยุ่น)
    $search_term = "%" . $search . "%";
    mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
    
    // เรียกใช้คำสั่ง SQL
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
?>
          <form action="delete_products.php" method="POST">
            <input type="hidden" name="table_name" value="<?php echo htmlspecialchars($table_name); ?>">  <!-- ส่งค่าตารางไปในฟอร์ม -->
            <div class="tm-product-table-container">
              <table class="table table-hover tm-table-small tm-product-table">
                <thead>
                  <tr>
                    <th scope="col"><input type="checkbox" id="select_all"></th>
                    <th scope="col">PRODUCT NAME</th>
                    <th scope="col">DETAIL</th>
                    <th scope="col">COLOR</th>
                    <th scope="col">SIZE</th>
                    <th scope="col">PRICE</th>
                    <th scope="col">IMG</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                // แสดงข้อมูลสินค้า
                if (mysqli_num_rows($result) > 0) {
                    while ($data = mysqli_fetch_array($result)) {
                        $product_id = $data['p_id'];
                        $product_name = $data['p_name'];
                        $product_detail = $data['p_detail'];
                        $product_color = $data['p_color'];
                        $product_size = $data['p_size'];
                        $product_price = $data['p_price'];
                        $product_image = $data['p_id'];  // ใช้ p_id เป็นชื่อไฟล์
                        $product_ext = $data['p_ext'];   // ใช้ p_ext เป็นนามสกุลไฟล์
                        $image_folder = "../img/" . $table_name . "/";  
                
                        $image_path = $image_folder . $product_image . "." . $product_ext;
                
                        // ตรวจสอบว่าไฟล์รูปภาพมีอยู่ในโฟลเดอร์หรือไม่
                        if (!file_exists($image_path)) {
                            $product_image = "default";  // ถ้าไม่มีรูปให้ใช้รูป default
                            $product_ext = "png";        // ใช้ .png เป็นนามสกุล
                        }
                
                        // แสดงข้อมูลสินค้า
                        echo "<tr>
                                <td><input type='checkbox' name='product_ids[]' value='$product_id'></td>
                                <td>$product_name</td>
                                <td>$product_detail</td>
                                <td>$product_color</td>
                                <td>$product_size</td>
                                <td>$product_price</td>
                                <td><img src='../img/" . $table_name . "/$product_image.$product_ext' alt='$product_name' style='max-width: 100px;'></td>
                                <td>
                                    <a href='editpro.php?table=" . urlencode($table_name) . "&p_id=" . urlencode($product_id) . "' class='btn btn-warning btn-sm'>Edit</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>ไม่พบสินค้าที่ค้นหา</td></tr>";
                }
                
                ?>         
                </tbody>
            </table>
            </div>
            <button type="submit" class="btn btn-danger btn-block text-uppercase">Delete selected products</button>
          </form>
            <script>
                // Select/Deselect all checkboxes
                document.getElementById('select_all').addEventListener('click', function() {
                    var checkboxes = document.getElementsByName('product_ids[]');
                    for (var checkbox of checkboxes) {
                        checkbox.checked = this.checked;
                    }
                });
            </script>
            <!-- table container -->
            <a href="add_product.php?table=<?php echo urlencode($table_name); ?>" 
            class="btn btn-primary btn-block text-uppercase mb-3">Add new product</a>
            </div>
        </div>
        
<?php
include_once("connectdb.php");

// ดึงข้อมูลจากทุกตารางในฐานข้อมูล ยกเว้นตาราง `user`
$sql = "SHOW TABLES";
$rs = mysqli_query($conn, $sql);
?>

<!-- HTML Code for the Categories Section -->
<div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
    <div class="tm-bg-primary-dark tm-block tm-block-product-categories">
        <h2 class="tm-block-title text-white mb-4">Product Categories</h2>
        <div class="tm-product-table-container">
            <table class="table table-striped table-bordered table-hover tm-table-small tm-product-table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Loop through all tables and show them as categories
                while ($data = mysqli_fetch_array($rs)) {
                    $table_name = $data[0]; // รับชื่อของตาราง

                    // กรองตารางที่ไม่ใช่ประเภทสินค้าหรือ `user`
                    if ($table_name != 'user' && $table_name != 'Register' && $table_name != 'member' && $table_name != 'orders'&& $table_name != 'order_items') {
                        echo "<tr><td class='tm-product-name'>
                                  <a href='?table_name=" . urlencode($table_name) . "'>" . ucfirst(str_replace("_", " ", $table_name)) . "</a>
                                </td>";
                        echo "<td class='text-center'>
                                  <a href='#' class='tm-product-delete-link' onclick='confirmDelete(\"$table_name\")'>
                                      <i class='far fa-trash-alt tm-product-delete-icon'></i>
                                  </a>
                                </td>
                              </tr>";
                    }
                }
                ?>
            <script>
            document.querySelector("form").addEventListener("submit", function(event) {
                var checkboxes = document.querySelectorAll('input[name="product_ids[]"]:checked');
                if (checkboxes.length === 0) {
                    alert("Please select at least one product to delete.");
                    event.preventDefault(); // หยุดการส่งฟอร์ม
                } else {
                    var confirmation = confirm("Are you sure you want to delete the selected products?");
                    if (!confirmation) {
                        event.preventDefault(); // หยุดการส่งฟอร์ม
                    }
                }
            });
            </script>                
            </tbody>
            </table>
        </div>
    </div>
</div>

      </div>
    </div>
    <footer class="tm-footer row tm-mt-small">
      <div class="col-12 font-weight-light">
        <p class="text-center text-white mb-0 px-4 small">
          Copyright &copy; <b>2018</b> All rights reserved. 
          
          Design: <a rel="nofollow noopener" href="https://templatemo.com" class="tm-footer-link">Template Mo</a>
        </p>
      </div>
    </footer>
  </body>
</html>