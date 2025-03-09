<?php
include_once("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['p_name'];
    $product_detail = $_POST['p_detail'];
    $product_color = $_POST['p_color'];
    $product_size = $_POST['p_size'];
    $product_price = $_POST['p_price'];
    $product_image = $_FILES['p_image']['name'];
    $product_ext = pathinfo($product_image, PATHINFO_EXTENSION);

    // Upload image
    $upload_dir = "../img/products/";
    $image_path = $upload_dir . basename($product_image);

    // Move image to the correct folder
    if (move_uploaded_file($_FILES['p_image']['tmp_name'], $image_path)) {
        // Insert product into the database
        $sql = "INSERT INTO products (p_name, p_detail, p_color, p_size, p_price, p_image, p_ext) 
                VALUES ('$product_name', '$product_detail', '$product_color', '$product_size', '$product_price', '$product_image', '$product_ext')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading image.";
    }
}
?>

<form action="add-product.php" method="POST" enctype="multipart/form-data">
    <label for="p_name">Product Name:</label>
    <input type="text" name="p_name" id="p_name" required><br>

    <label for="p_detail">Product Detail:</label>
    <input type="text" name="p_detail" id="p_detail" required><br>

    <label for="p_color">Product Color:</label>
    <input type="text" name="p_color" id="p_color" required><br>

    <label for="p_size">Product Size:</label>
    <input type="text" name="p_size" id="p_size" required><br>

    <label for="p_price">Product Price:</label>
    <input type="number" name="p_price" id="p_price" required><br>

    <label for="p_image">Product Image:</label>
    <input type="file" name="p_image" id="p_image" required><br>

    <button type="submit">Add Product</button>
</form>
