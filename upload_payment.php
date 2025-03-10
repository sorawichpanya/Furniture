<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_FILES['payment_slip']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $file_name = basename($_FILES['payment_slip']['name']);
    $target_file = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
        $_SESSION['payment_uploaded'] = true;
        $_SESSION['payment_slip'] = $target_file;
        header("Location: checkout.php");
        exit;
    } else {
        echo "Error moving uploaded file.";
    }
} else {
    echo "File upload error: " . $_FILES['payment_slip']['error'];
}
// ตรวจสอบว่าไฟล์ได้รับการอัปโหลด
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบประเภทไฟล์
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    if (in_array($_FILES['payment_slip']['type'], $allowed_types)) {
        // ตั้งตำแหน่งที่เก็บไฟล์
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['payment_slip']['name']);
        $target_file = $upload_dir . $file_name;

        // อัปโหลดไฟล์
        if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
            $_SESSION['payment_uploaded'] = true;  // ตั้งค่าสถานะการอัปโหลด

            // เก็บ path ของไฟล์ใน session เพื่อบันทึกลงฐานข้อมูล
            $_SESSION['payment_slip'] = $target_file;

            // รีไดเร็กต์ไปยังหน้าที่ผู้ใช้จะเห็นผลการอัปโหลด
            $_SESSION['success_message'] = "Payment slip uploaded successfully.";
            header("Location: checkout.php"); // หรือหน้าที่แสดงข้อความ success
            exit;
        } else {
            $_SESSION['error_message'] = "Error uploading payment slip.";
            header("Location: checkout.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or PDF files are allowed.";
        header("Location: checkout.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "No file uploaded.";
    header("Location: checkout.php");
    exit;
}
?>
