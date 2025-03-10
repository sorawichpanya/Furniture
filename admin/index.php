<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); 
    exit;
}
$currentPage = basename($_SERVER['PHP_SELF']);
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

<body id="reportsPage">
    <div class="" id="home">
        <nav class="navbar navbar-expand-xl">
            <div class="container h-100">
                <a class="navbar-brand" href="index.php">
                    <h1 class="tm-site-title mb-0">Product Admin</h1>
                </a>
                <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars tm-nav-icon"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto h-100">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'index.php' ? 'active' : ''; ?>" href="index.php">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                                <?php if ($currentPage == 'index.php') : ?>
                                    <span class="sr-only">(current)</span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $currentPage == 'products.php' ? 'active' : ''; ?>" href="products.php">
                                <i class="fas fa-shopping-cart"></i> Products
                                <?php if ($currentPage == 'products.php') : ?>
                                    <span class="sr-only">(current)</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($_SESSION['username']); ?>
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
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="text-white mt-5 mb-5">Welcome back, <b>Admin</b></p>
                </div>
            </div>
            <!-- row -->
            <div class="row tm-content-row">
    <div class="col-12 tm-block-col">
        <div class="tm-bg-primary-dark tm-block tm-block-taller tm-block-scroll">
            <h2 class="tm-block-title">Orders List</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ORDER ID.</th>
                        <th scope="col">FULL NAME</th>
                        <th scope="col">PHONE</th>
                        <th scope="col">ADDRESS</th>
                        <th scope="col">PROVINCE</th>
                        <th scope="col">ZIP CODE</th>
                        <th scope="col">TOTAL PRICE</th>
                        <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // เรียกใช้ PHP สำหรับดึงข้อมูลจากฐานข้อมูลและแสดงในตาราง
                    include_once("connectdb.php");

                    if ($conn->connect_error) {
                        die("❌ Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM orders";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<th scope='row'><b>#".$row['order_id']."</b></th>";
                            echo "<td><b>".$row['full_name']."</b></td>";
                            echo "<td><b>".$row['phone']."</b></td>";
                            echo "<td><b>".$row['address']."</b></td>";
                            echo "<td><b>".$row['province']."</b></td>";
                            echo "<td><b>".$row['zip_code']."</b></td>";
                            echo "<td><b>฿".number_format($row['total_price'], 2)."</b></td>";
                            echo "<td><b>".$row['status']."</b></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>ไม่มีข้อมูลคำสั่งซื้อ</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-12 tm-block-col">
        <div class="tm-bg-primary-dark tm-block tm-block-taller tm-block-scroll">
            <h2 class="tm-block-title">Orders List</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ORDER ID.</th>
                        <th scope="col">FULL NAME</th>
                        <th scope="col">PHONE</th>
                        <th scope="col">ADDRESS</th>
                        <th scope="col">PROVINCE</th>
                        <th scope="col">ZIP CODE</th>
                        <th scope="col">TOTAL PRICE</th>
                        <th scope="col">STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // เรียกใช้ PHP สำหรับดึงข้อมูลจากฐานข้อมูลและแสดงในตาราง
                    include_once("connectdb.php");

                    if ($conn->connect_error) {
                        die("❌ Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM orders";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<th scope='row'><b>#".$row['order_id']."</b></th>";
                            echo "<td><b>".$row['full_name']."</b></td>";
                            echo "<td><b>".$row['phone']."</b></td>";
                            echo "<td><b>".$row['address']."</b></td>";
                            echo "<td><b>".$row['province']."</b></td>";
                            echo "<td><b>".$row['zip_code']."</b></td>";
                            echo "<td><b>฿".number_format($row['total_price'], 2)."</b></td>";
                            echo "<td><b>".$row['status']."</b></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>ไม่มีข้อมูลคำสั่งซื้อ</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
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
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <!-- https://jquery.com/download/ -->
    <script src="js/moment.min.js"></script>
    <!-- https://momentjs.com/ -->
    <script src="js/Chart.min.js"></script>
    <!-- http://www.chartjs.org/docs/latest/ -->
    <script src="js/bootstrap.min.js"></script>
    <!-- https://getbootstrap.com/ -->
    <script src="js/tooplate-scripts.js"></script>
    <script>
        Chart.defaults.global.defaultFontColor = 'white';
        let ctxLine,
            ctxBar,
            ctxPie,
            optionsLine,
            optionsBar,
            optionsPie,
            configLine,
            configBar,
            configPie,
            lineChart;
        barChart, pieChart;
        // DOM is ready
        $(function () {
            drawLineChart(); // Line Chart
            drawBarChart(); // Bar Chart
            drawPieChart(); // Pie Chart

            $(window).resize(function () {
                updateLineChart();
                updateBarChart();                
            });
        })
    </script>
</body>

</html>