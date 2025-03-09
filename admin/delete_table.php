<?php
include_once("connectdb.php");

// รับค่า table_name ที่ส่งมาจาก URL
$table_name = isset($_GET['table_name']) ? $_GET['table_name'] : '';

// ตรวจสอบว่ามีค่าของ table_name และไม่ใช่ตารางที่ไม่สามารถลบได้
if (!empty($table_name) && $table_name != 'user' && $table_name != 'register' && $table_name != 'member') {
    // SQL สำหรับลบตาราง
    $delete_sql = "DROP TABLE `$table_name`";
    
    if (mysqli_query($conn, $delete_sql)) {
        echo "Table '$table_name' has been deleted successfully.";
    } else {
        echo "Error deleting table: " . mysqli_error($conn);
    }
} else {
    echo "Invalid table name or operation not allowed.";
}
?>
