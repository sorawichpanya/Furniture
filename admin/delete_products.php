<?php
include_once("connectdb.php");

if (isset($_POST['product_ids'])) {
    $product_ids = $_POST['product_ids'];

    // Loop through the selected product IDs and delete them
    foreach ($product_ids as $product_id) {
        $sql = "DELETE FROM products WHERE p_id = $product_id";
        if (mysqli_query($conn, $sql)) {
            echo "Product with ID $product_id has been deleted successfully.<br>";
        } else {
            echo "Error deleting product with ID $product_id: " . mysqli_error($conn) . "<br>";
        }
    }
} else {
    echo "No products selected.";
}

header("Location: index.php");  // Redirect back to the main page
exit;
?>
