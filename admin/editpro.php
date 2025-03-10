<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: Login.php");
    exit;
}

include_once("connectdb.php");

// ตรวจสอบว่ามี product_id ส่งมาหรือไม่
if (isset($_GET['product_id']) && isset($_GET['table_name'])) {
    $product_id = $_GET['product_id'];
    $table_name = $_GET['table_name'];

    // ดึงข้อมูลสินค้าจากฐานข้อมูล
    $sql = "SELECT * FROM `$table_name` WHERE p_id = '$product_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        // สินค้าไม่พบ หรือมีข้อผิดพลาด
        echo "ไม่พบสินค้า หรือมีข้อผิดพลาดในการดึงข้อมูล";
        exit;
    }
} else {
    // ไม่มีการส่ง product_id มา
    echo "ไม่ได้ระบุ product_id";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/templatemo-style.css">
</head>
<body>
    <nav class="navbar navbar-expand-xl">
        <!-- Navbar content (เหมือนในไฟล์ template) -->
    </nav>

    <div class="container tm-mt-big tm-mb-big">
        <div class="row">
            <div class="col-xl-9 col-lg-10 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Edit Product</h2>
                        </div>
                    </div>
                    <div class="row tm-edit-product-row">
                        <div class="col-xl-6 col-lg-6 col-md-12">
                            <form action="update_product.php" method="post" class="tm-edit-product-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['p_id']; ?>">
                                <input type="hidden" name="table_name" value="<?php echo $table_name; ?>">
                                <div class="form-group mb-3">
                                    <label for="name">Product Name</label>
                                    <input
                                        id="name"
                                        name="name"
                                        type="text"
                                        value="<?php echo htmlspecialchars($product['p_name']); ?>"
                                        class="form-control validate"
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="description">Description</label>
                                    <textarea
                                        class="form-control validate tm-small"
                                        rows="5"
                                        name="description"
                                        required
                                    ><?php echo htmlspecialchars($product['p_detail']); ?></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="color">Color</label>
                                    <input
                                        id="color"
                                        name="color"
                                        type="text"
                                        value="<?php echo htmlspecialchars($product['p_color']); ?>"
                                        class="form-control validate"
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="size">Size</label>
                                    <input
                                        id="size"
                                        name="size"
                                        type="text"
                                        value="<?php echo htmlspecialchars($product['p_size']); ?>"
                                        class="form-control validate"
                                    />
                                </div>
                                <div class="form-group mb-3">
                                    <label for="price">Price</label>
                                    <input
                                        id="price"
                                        name="price"
                                        type="text"
                                        value="<?php echo htmlspecialchars($product['p_price']); ?>"
                                        class="form-control validate"
                                    />
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-block text-uppercase">Update Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="tm-footer row tm-mt-small">
        <!-- Footer content (เหมือนในไฟล์ template) -->
    </footer>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="jquery-ui-datepicker/jquery-ui.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $(function() {
        $("#expire_date").datepicker({
          defaultDate: "10/22/2020"
        });
      });
    </script>
</body>
</html>
