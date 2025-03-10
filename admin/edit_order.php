<?php
include_once("connectdb.php");

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // คำสั่ง SQL ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล
    $sql = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // แสดงข้อมูลในฟอร์ม
        echo "
        <form action='update_order.php' method='POST'>
            <input type='hidden' name='order_id' value='".$row['order_id']."'>
            <label for='full_name'>Full Name:</label>
            <input type='text' name='full_name' value='".$row['full_name']."'>
            <label for='phone'>Phone:</label>
            <input type='text' name='phone' value='".$row['phone']."'>
            <label for='address'>Address:</label>
            <input type='text' name='address' value='".$row['address']."'>
            <label for='province'>Province:</label>
            <input type='text' name='province' value='".$row['province']."'>
            <label for='zip_code'>Zip Code:</label>
            <input type='text' name='zip_code' value='".$row['zip_code']."'>
            <label for='total_price'>Total Price:</label>
            <input type='number' name='total_price' value='".$row['total_price']."'>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending" <?php echo ($current_status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="shipped" <?php echo ($current_status == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                    <option value="completed" <?php echo ($current_status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo ($current_status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>            
    <button type='submit' class='btn btn-success'>Update</button>
        </form>
        ";
    } else {
        echo "ไม่พบข้อมูลคำสั่งซื้อ";
    }

    $stmt->close();
} else {
    echo "ไม่พบ order_id";
}

$conn->close();
?>
