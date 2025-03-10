<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    echo "🔍 Debug: POST request received.<br>";

    if (isset($_FILES['payment_slip'])) {
        echo "🔍 Debug: File uploaded detected.<br>";

        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $file_type = $_FILES['payment_slip']['type']; 
        $file_error = $_FILES['payment_slip']['error']; 
        $file_tmp = $_FILES['payment_slip']['tmp_name']; 
        $upload_dir = 'uploads/'; 

        if (in_array($file_type, $allowed_types)) {
            echo "✅ Valid file type: $file_type<br>";
            if ($file_error == UPLOAD_ERR_OK) {
                $file_name = uniqid() . '_' . basename($_FILES['payment_slip']['name']); 
                $target_file = $upload_dir . $file_name;

                if (move_uploaded_file($file_tmp, $target_file)) {
                    $_SESSION['payment_uploaded'] = true;
                    $_SESSION['payment_slip'] = $target_file;
                    $_SESSION['payment_slip_type'] = mime_content_type($target_file);
                    $_SESSION['success_message'] = "Payment slip uploaded successfully.";
                    echo "✅ File uploaded successfully to: $target_file<br>";
                } else {
                    $_SESSION['error_message'] = "Error moving uploaded file.";
                    echo "⛔ Failed to move uploaded file.<br>";
                }
            } else {
                $_SESSION['error_message'] = "File upload error: $file_error";
                echo "⛔ File upload error code: $file_error<br>";
            }
        } else {
            $_SESSION['error_message'] = "Invalid file type: $file_type.";
            echo "⛔ Invalid file type: $file_type<br>";
        }
    } else {
        $_SESSION['error_message'] = "No file uploaded.";
        echo "⛔ No file uploaded.<br>";
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    echo "⛔ Invalid request method.<br>";
}
header("Location: checkout.php");
exit;
?>
