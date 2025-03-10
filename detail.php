<?php
session_start(); // ต้องประกาศที่จุดเริ่มต้นของไฟล์
include_once("connectdb.php");
?>


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

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row bg-secondary py-2 px-xl-5">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark" href="">FAQs</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Help</a>
                    <span class="text-muted px-2">|</span>
                    <a class="text-dark" href="">Support</a>
                </div>
            </div>
            <div class="col-lg-6 text-center text-lg-right">
                <div class="d-inline-flex align-items-center">
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="text-dark px-2" href="http://212.80.215.178/Furniture/member.php">
                        <i class="fas fa-users"></i>
                    </a>
                    <a class="text-dark px-2" href="">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a class="text-dark pl-2" href="">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="row align-items-center py-3 px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a href="index.php" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="search_results.php" method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for products">
                        <div class="input-group-append">
                            <span class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-6 text-right">
                <a href="" class="btn border">
                    <i class="fas fa-heart text-primary"></i>
                    <span class="badge">0</span>
                </a>
                <a href="" class="btn border">
                    <i class="fas fa-shopping-cart text-primary"></i>
                    <span class="badge">0</span>
                </a>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid">
        <div class="row border-top px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn shadow-none d-flex align-items-center justify-content-between bg-primary text-white w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; margin-top: -1px; padding: 0 30px;">
                    <h6 class="m-0">Categories</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 border border-top-0 border-bottom-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 1;">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">
                    <div class="navbar-nav w-100 overflow-hidden" style="height: 410px">

                        <a href="livingroom.php" class="nav-item nav-link">living room</a>
                        <a href="bathroom.php" class="nav-item nav-link">bathroom</a>
                        <a href="bedroom.php" class="nav-item nav-link">bedroom</a>
                        <a href="kitchen.php" class="nav-item nav-link">kitchen</a>
                        <a href="garden.php" class="nav-item nav-link">garden</a>
                        <a href="workroom.php" class="nav-item nav-link">work room</a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="index.php" class="text-decoration-none d-block d-lg-none">
                        <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <div class="navbar-nav mr-auto py-0">
                            <a href="index.php" class="nav-item nav-link">Home</a>
                            <a href="shop.php" class="nav-item nav-link">Shop</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Pages</a>
                                <div class="dropdown-menu rounded-0 m-0">
                                    <a href="cart.php" class="dropdown-item">Shopping Cart</a>
                                    <a href="checkout.php" class="dropdown-item">Checkout</a>
                                </div>
                            </div>
                            <a href="contact.php" class="nav-item nav-link">Contact</a>
                        </div>
                        <div class="navbar-nav ml-auto py-0">
                            <a href="" class="nav-item nav-link">Login</a>
                            <a href="" class="nav-item nav-link">Register</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-secondary mb-5">
        <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shop Detail</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shop Detail</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่ง p_id และ category หรือไม่
if (isset($_GET['p_id']) && isset($_GET['category'])) {
    $p_id = $_GET['p_id'];
    $category = $_GET['category']; // รับค่า category จาก URL

    // กำหนดชื่อฐานข้อมูลตาม category
    if ($category == 'trendy') {
        $table = "trendy";
    } elseif ($category == 'Just_arrived') {
        $table = "Just_arrived";
    } elseif ($category == 'bathroom') {
        $table = "bathroom";
    } elseif ($category == 'living_room') {
        $table = "living_room";
    } elseif ($category == 'bedroom') {
        $table = "bedroom";
    } elseif ($category == 'kitchen_room') {
        $table = "kitchen_room";
    } elseif ($category == 'garden') {
        $table = "garden";
    } elseif ($category == 'workroom') {
        $table = "workroom";
    } else {
        echo "Invalid category!";
        exit;
    }

    // ดึงข้อมูลจากฐานข้อมูลตาม table ที่เลือก
    $sql = "SELECT * FROM $table WHERE p_id = $p_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_array($result);

    // ตรวจสอบว่าเจอข้อมูลหรือไม่
    if ($product) {
        $image_pattern = "img/$category/{$p_id}*.*"; // ค้นหารูปภาพในโฟลเดอร์ที่ตรงกับ category
        $product_images = glob($image_pattern); // ดึงรายการไฟล์ที่ตรงกับ pattern
    } else {
        echo "Product not found!";
        exit;
    }
} else {
    echo "Invalid product ID or category!";
    exit;
}
?>


<!-- Shop Detail Start -->
<div class="container-fluid py-5">
    <div class="row px-xl-5">
        <div class="col-lg-5 pb-5">
            <div id="product-carousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner border">
                <?php
                    if (!empty($product_images)) {
                        foreach ($product_images as $key => $image) {
                            $activeClass = ($key === 0) ? "active" : "";
                            echo "
                            <div class='carousel-item $activeClass'>
                                <img class='img-fluid w-100' src='$image' alt='Product Image'>
                            </div>";
                        }
                    } else {
                        echo "
                        <div class='carousel-item active'>
                            <img class='img-fluid w-100' src='img/no-image.jpg' alt='No Image Available'>
                        </div>";
                        echo "<p>No images found for product ID: $p_id</p>";
                    }
                    ?>
                </div>
                <a class="carousel-control-prev" href="#product-carousel" data-slide="prev">
                    <i class="fa fa-2x fa-angle-left text-dark"></i>
                </a>
                <a class="carousel-control-next" href="#product-carousel" data-slide="next">
                    <i class="fa fa-2x fa-angle-right text-dark"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-7 pb-5">
            <h3 class="font-weight-semi-bold"><?php echo $product['p_name']; ?></h3>
            <div class="d-flex mb-3">
                <div class="text-primary mr-2">
                    <small class="fas fa-star"></small>
                    <small class="fas fa-star"></small>
                    <small class="fas fa-star"></small>
                    <small class="fas fa-star-half-alt"></small>
                    <small class="far fa-star"></small>
                </div>
            </div>
            <h3 class="font-weight-semi-bold mb-4">฿<?php echo number_format($product['p_price'], 2); ?></h3>

                    <!-- ขนาดสินค้า -->
                    <p class="mb-3">
                        <strong>Size:</strong> 
                        <span style="font-size: 16px; color: #555;"><?php echo $product['p_size']; ?></span>
                    </p>

                    <!-- สีสินค้า -->
                    <p class="mb-4">
                        <strong>Color:</strong> 
                        <span style="font-size: 16px; color: #555;"><?php echo $product['p_color']; ?></span>
                    </p>

                    <!-- รายละเอียดสินค้า -->
                    <p class="mb-4" style="font-size: 18px; line-height: 1.6;">
                        <?php echo $product['p_detail']; ?>
                    </p>

                    <div class="d-flex align-items-center mb-4 pt-2">
    <div class="input-group quantity mr-3" style="width: 130px;">
        <div class="input-group-btn">
            <button class="btn btn-primary btn-minus" type="button" id="btn-minus">
                <i class="fa fa-minus"></i>
            </button>
        </div>
        <input type="text" class="form-control bg-secondary text-center" id="quantity" name="quantity" value="1" readonly>
        <div class="input-group-btn">
            <button class="btn btn-primary btn-plus" type="button" id="btn-plus">
                <i class="fa fa-plus"></i>
            </button>
        </div>
    </div>
    
    <form action="cart.php" method="POST">
        <input type="hidden" name="p_id" value="<?php echo htmlspecialchars($_GET['p_id']); ?>">
        <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
        <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
        <button type="submit" class="btn btn-sm text-dark p-0">
            <i class="fas fa-shopping-cart text-primary mr-1"></i>Add To Cart
        </button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Select elements
        const btnMinus = document.getElementById("btn-minus");
        const btnPlus = document.getElementById("btn-plus");
        const quantityInput = document.getElementById("quantity");
        const hiddenQuantity = document.getElementById("hiddenQuantity");

        // Function to update quantity
        function updateQuantity(change) {
            // Get current quantity
            let currentQuantity = parseInt(quantityInput.value, 10);

            // Default to 1 if invalid
            if (isNaN(currentQuantity)) {
                currentQuantity = 1;
            }

            // Calculate new quantity
            let newQuantity = currentQuantity + change;

            // Ensure new quantity is at least 1
            if (newQuantity < 1) {
                newQuantity = 1;
            }

            // Update visible input and hidden input
            quanti

            </div>ช
        </div>
    </div>
</div>

<hr style="border: 1px solid #ddd; margin: 20px 0;">


<?php
include_once("connectdb.php");

// ดึงข้อมูลจากตาราง trendy
$sql = "SELECT p_id, p_name, p_price, p_ext FROM trendy ORDER BY RAND() LIMIT 10";
$rs = mysqli_query($conn, $sql);
?>
<!-- Products Start -->
<div class="container-fluid py-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">You May Also Like</span></h2>
    </div>
    <div class="row px-xl-5">
        <div class="col">
            <div class="owl-carousel related-carousel">
                <?php while ($data = mysqli_fetch_array($rs)) { ?>
                    <div class="card product-item border-0">
                        <div class="card-header product-img position-relative overflow-hidden bg-transparent border p-0">
                            <!-- แสดงรูปภาพ -->
                            <img class="img-fluid w-100" 
                                src="img/trendy/<?php echo $data['p_id']; ?>.<?php echo $data['p_ext']; ?>" 
                                alt="<?php echo htmlspecialchars($data['p_name']); ?>" 
                                style="max-height: 300px; object-fit: cover;">
                        </div>
                        <div class="card-body border-left border-right text-center p-0 pt-4 pb-3">
                            <!-- แสดงชื่อสินค้า -->
                            <h6 class="text-truncate mb-3"><?php echo htmlspecialchars($data['p_name']); ?></h6>
                            <div class="d-flex justify-content-center">
                                <!-- แสดงราคา -->
                                <h6>฿<?php echo number_format($data['p_price'], 2); ?></h6>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between bg-light border">
                            <!-- ปุ่ม View Detail -->
                            <a href="?p_id=<?php echo $data['p_id']; ?>&category=trendy" 
                                class="btn btn-sm text-dark p-0">
                                <i class="fas fa-eye text-primary mr-1"></i>View Detail
                                </a>

                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- Products End -->



    <!-- Footer Start -->
    <div class="container-fluid bg-secondary text-dark mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <a href="" class="text-decoration-none">
                    <h1 class="mb-4 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border border-white px-3 mr-1">E</span>Shopper</h1>
                </a>
                <p>Dolore erat dolor sit lorem vero amet. Sed sit lorem magna, ipsum no sit erat lorem et magna ipsum dolore amet erat.</p>
                <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i>+012 345 67890</p>
            </div>
            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Quick Links</h5>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="index.php"><i class="fa fa-angle-right mr-2"></i>Home</a>
                            <a class="text-dark mb-2" href="shop.php"><i class="fa fa-angle-right mr-2"></i>Our Shop</a>
                            <a class="text-dark mb-2" href="cart.php"><i class="fa fa-angle-right mr-2"></i>Shopping Cart</a>
                            <a class="text-dark mb-2" href="checkout.php"><i class="fa fa-angle-right mr-2"></i>Checkout</a>
                            <a class="text-dark" href="contact.php"><i class="fa fa-angle-right mr-2"></i>Contact Us</a>
                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4"></h5>
                        <div class="d-flex flex-column justify-content-start">
                          

                        </div>
                    </div>
                    <div class="col-md-4 mb-5">
                        <h5 class="font-weight-bold text-dark mb-4">Newsletter</h5>
                        <form action="">
                            <div class="form-group">
                                <input type="text" class="form-control border-0 py-4" placeholder="Your Name" required="required" />
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control border-0 py-4" placeholder="Your Email"
                                    required="required" />
                            </div>
                            <div>
                                <button class="btn btn-primary btn-block border-0 py-3" type="submit">Subscribe Now</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row border-top border-light mx-xl-5 py-4">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-dark">
                    &copy; <a class="text-dark font-weight-semi-bold" href="#">Your Site Name</a>. All Rights Reserved. Designed
                    by
                    <a class="text-dark font-weight-semi-bold" href="https://htmlcodex.com">HTML Codex</a>
                </p>
            </div>
            <div class="col-md-6 px-xl-0 text-center text-md-right">
                <img class="img-fluid" src="img/payments.png" alt="">
            </div>
        </div>
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