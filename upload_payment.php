<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_slip'])) {
    $upload_dir = "uploads/";
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];

    // ตรวจสอบประเภทไฟล์
    if (!in_array($_FILES['payment_slip']['type'], $allowed_types)) {
        $_SESSION['error_message'] = "Only JPG, PNG, and PDF files are allowed.";
        header("Location: checkout.php");
        exit;
    }

    // ตั้งชื่อไฟล์ใหม่
    $file_name = time() . "_" . basename($_FILES['payment_slip']['name']);
    $target_file = $upload_dir . $file_name;

    // อัปโหลดไฟล์
    if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
        $_SESSION['payment_uploaded'] = true;
        $_SESSION['payment_slip'] = $target_file; // เก็บ path ของสลิป
        $_SESSION['success_message'] = "Payment slip uploaded successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to upload payment slip.";
    }

    header("Location: checkout.php");
    exit;
}
?>
