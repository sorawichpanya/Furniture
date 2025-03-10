<?php
session_start();
include_once("connectdb.php");

// รับค่าจากฟอร์ม
$search_query = isset($_POST['search_query']) ? trim($_POST['search_query']) : '';
$category = isset($_POST['category']) ? $_POST['category'] : 'all';
$min_price = isset($_POST['min_price']) ? (float)$_POST['min_price'] : 0;
$max_price = isset($_POST['max_price']) ? (float)$_POST['max_price'] : 9999999;
$sort_order = isset($_POST['sort_order']) ? $_POST['sort_order'] : 'p_price ASC';

// ใช้ Prepared Statements
$items_per_page = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$category_condition = "";
$params = [];
$param_types = "";

// เงื่อนไขสำหรับเลือกหมวดหมู่
if ($category !== 'all') {
    $category_condition = "AND ? = category";
    $params[] = $category;
    $param_types .= "s";
}

// สร้าง SQL Query พร้อมตัวกรอง
$sql = "
    SELECT p_id, p_name, p_price, p_ext, 'bedroom' AS category FROM `bedroom` WHERE (p_name LIKE ? OR p_detail LIKE ?) AND p_price BETWEEN ? AND ?
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'living_room' AS category FROM `living_room` WHERE (p_name LIKE ? OR p_detail LIKE ?) AND p_price BETWEEN ? AND ?
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'bathroom' AS category FROM `bathroom` WHERE (p_name LIKE ? OR p_detail LIKE ?) AND p_price BETWEEN ? AND ?
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'kitchen_room' AS category FROM `kitchen_room` WHERE (p_name LIKE ? OR p_detail LIKE ?) AND p_price BETWEEN ? AND ?
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'garden' AS category FROM `garden` WHERE (p_name LIKE ? OR p_detail LIKE ?) AND p_price BETWEEN ? AND ?
    UNION ALL
    SELECT p_id, p_name, p_price, p_ext, 'workroom' AS category FROM `workroom` WHERE (p_name LIKE ? OR p_detail LIKE ?) AND p_price BETWEEN ? AND ?
    $category_condition
    ORDER BY $sort_order
    LIMIT ? OFFSET ?";

$stmt = mysqli_prepare($conn, $sql);
$search_param = "%$search_query%";

// รวมพารามิเตอร์ทั้งหมด
$params = array_merge(
    array_fill(0, 6, $search_param),
    array_fill(0, 6, $min_price),
    array_fill(0, 6, $max_price),
    $params, // สำหรับเงื่อนไขหมวดหมู่ (ถ้ามี)
    [$items_per_page, $offset]
);
$param_types = str_repeat("s", 6) . str_repeat("d", 6) . $param_types . "ii";
mysqli_stmt_bind_param($stmt, $param_types, ...$params);

mysqli_stmt_execute($stmt);
$rs = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>EShopper - Search Results</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>Search Results</h1>
    <form action="search_results.php" method="POST">
        <input type="text" name="search_query" value="<?php echo htmlspecialchars($search_query); ?>" placeholder="Search for products">

        <label>Category:</label>
        <select name="category">
            <option value="all" <?php if ($category == 'all') echo 'selected'; ?>>All</option>
            <option value="bedroom" <?php if ($category == 'bedroom') echo 'selected'; ?>>Bedroom</option>
            <option value="living_room" <?php if ($category == 'living_room') echo 'selected'; ?>>Living Room</option>
            <option value="bathroom" <?php if ($category == 'bathroom') echo 'selected'; ?>>Bathroom</option>
            <option value="kitchen_room" <?php if ($category == 'kitchen_room') echo 'selected'; ?>>Kitchen</option>
            <option value="garden" <?php if ($category == 'garden') echo 'selected'; ?>>Garden</option>
            <option value="workroom" <?php if ($category == 'workroom') echo 'selected'; ?>>Workroom</option>
        </select>

        <label>Price Range:</label>
        <input type="number" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>" placeholder="Min Price">
        <input type="number" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>" placeholder="Max Price">

        <label>Sort By:</label>
        <select name="sort_order">
            <option value="p_price ASC" <?php if ($sort_order == 'p_price ASC') echo 'selected'; ?>>Price: Low to High</option>
            <option value="p_price DESC" <?php if ($sort_order == 'p_price DESC') echo 'selected'; ?>>Price: High to Low</option>
            <option value="p_name ASC" <?php if ($sort_order == 'p_name ASC') echo 'selected'; ?>>Name: A to Z</option>
            <option value="p_name DESC" <?php if ($sort_order == 'p_name DESC') echo 'selected'; ?>>Name: Z to A</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <div class="row">
        <?php if (mysqli_num_rows($rs) > 0): ?>
            <?php while ($data = mysqli_fetch_assoc($rs)): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="img/<?php echo htmlspecialchars($data['category']); ?>/<?php echo htmlspecialchars($data['p_id']); ?>.<?php echo htmlspecialchars($data['p_ext']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($data['p_name']); ?>">
                        <div class="card-body text-center">
                            <h6><?php echo htmlspecialchars($data['p_name']); ?></h6>
                            <p>Price: ฿<?php echo number_format($data['p_price'], 2); ?></p>
                            <a href="detail.php?p_id=<?php echo urlencode($data['p_id']); ?>&category=<?php echo urlencode($data['category']); ?>" class="btn btn-primary">View Detail</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found matching your filters.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
