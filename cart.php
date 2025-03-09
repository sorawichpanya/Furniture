<?php
session_start();
include("connectdb.php"); // เชื่อมต่อฐานข้อมูล

// เปิดการแสดง Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// ตรวจสอบค่าที่รับมาจาก GET
if (isset($_GET['action']) && $_GET['action'] == "add" && isset($_GET['p_id']) && isset($_GET['category'])) {
    $p_id = $_GET['p_id'];
    $category = $_GET['category'];

    // Debug: เช็คค่าที่รับมา
    var_dump($p_id, $category);
    
    // ตรวจสอบว่า category มีอยู่ในฐานข้อมูล
    $sql = "SELECT p_id, p_name, p_price FROM $category WHERE p_id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        die("SQL Error: " . $conn->error); // แสดง error ถ้า prepare ไม่สำเร็จ
    }

    $stmt->bind_param("i", $p_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    // Debug: เช็คค่าที่ดึงมาได้
    var_dump($product);
    die(); // หยุดการทำงานเพื่อดูค่า

    session_start();  // เริ่มต้น session

    // ตรวจสอบว่า cart ถูกตั้งค่าหรือยัง
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    // ตัวแปรที่เก็บข้อมูลสินค้า เช่น $p_id, $p_name, $p_price
    $p_id = $_POST['p_id'];
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    $category = $_POST['category'];
    
    // ถ้าสินค้าอยู่ในตะกร้าแล้ว ให้เพิ่มจำนวนสินค้า
    if (isset($_SESSION['cart'][$p_id])) {
        $_SESSION['cart'][$p_id]['quantity'] += 1;
        $_SESSION['cart'][$p_id]['total_price'] = $_SESSION['cart'][$p_id]['quantity'] * $_SESSION['cart'][$p_id]['p_price'];
    } else {
        // ถ้าสินค้าใหม่ ให้เพิ่มลงในตะกร้า
        $_SESSION['cart'][$p_id] = [
            'p_id' => $p_id,
            'p_name' => $p_name,
            'p_price' => $p_price,
            'quantity' => 1,
            'total_price' => $p_price
        ];
    }
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
                <a href="" class="text-decoration-none">
                    <h1 class="m-0 display-5 font-weight-semi-bold"><span class="text-primary font-weight-bold border px-3 mr-1">E</span>Shopper</h1>
                </a>
            </div>
            <div class="col-lg-6 col-6 text-left">
                <form action="">
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

                        <a href="" class="nav-item nav-link">living room</a>
                        <a href="" class="nav-item nav-link">bathroom</a>
                        <a href="" class="nav-item nav-link">bedroom</a>
                        <a href="" class="nav-item nav-link">kitchen</a>
                        <a href="" class="nav-item nav-link">garden</a>
                        <a href="" class="nav-item nav-link">work room</a>
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-light navbar-light py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
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
                                <a href="#" class="nav-link dropdown-toggle active" data-toggle="dropdown">Pages</a>
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
            <h1 class="font-weight-semi-bold text-uppercase mb-3">Shopping Cart</h1>
            <div class="d-inline-flex">
                <p class="m-0"><a href="">Home</a></p>
                <p class="m-0 px-2">-</p>
                <p class="m-0">Shopping Cart</p>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Cart Start -->
    <div class="container pt-5">
    <?php 
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];  // ดึงข้อมูลสินค้าจากตะกร้า

if (!empty($cart_items)) : ?>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['p_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['category']); ?></td>
                    <td>฿<?php echo number_format($item['p_price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td>฿<?php echo number_format($item['total_price'], 2); ?></td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($item['p_id']); ?>">
                            <input type="hidden" name="category" value="<?php echo htmlspecialchars($item['category']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="text-right">
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    </div>
<?php else : ?>
    <p class="text-center">Your cart is empty!</p>
    <div class="text-center mt-3">
        <a href="shop.php" class="btn btn-secondary">Go Back to Shop</a>
    </div>
<?php endif; ?>

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