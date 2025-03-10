<?php

session_start();
require 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['full_name'] = $_POST['full_name'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['address'] = $_POST['address'];

    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['payment_slip']['name']);
    $target_file = $upload_dir . $file_name;
    
    if (in_array($_FILES['payment_slip']['type'], $allowed_types)) {
        if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
            $_SESSION['payment_uploaded'] = true;
            $_SESSION['payment_slip'] = $target_file; 
            $_SESSION['payment_slip_type'] = mime_content_type($target_file);
            $_SESSION['success_message'] = "Payment slip uploaded successfully.";
        } else {
            $_SESSION['error_message'] = "Error uploading payment slip.";
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or PDF files are allowed.";
    }

    header("Location: checkout.php");
    exit;
}
?>
