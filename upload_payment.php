<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES['payment_slip'])) {
        // ตรวจสอบประเภทไฟล์ที่อัปโหลด
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        $file_type = $_FILES['payment_slip']['type']; // อ่านประเภทไฟล์
        $file_error = $_FILES['payment_slip']['error']; // ตรวจสอบข้อผิดพลาดไฟล์
        $file_tmp = $_FILES['payment_slip']['tmp_name']; // ไฟล์ชั่วคราว

        if (in_array($file_type, $allowed_types)) {
            // ตรวจสอบว่าไฟล์ไม่มีข้อผิดพลาด
            if ($file_error == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/'; // โฟลเดอร์ที่จะเก็บไฟล์
                $file_name = uniqid() . '_' . basename($_FILES['payment_slip']['name']); // ชื่อไฟล์ที่ไม่ซ้ำ
                $target_file = $upload_dir . $file_name;

                // อัปโหลดไฟล์
                if (move_uploaded_file($file_tmp, $target_file)) {
                    $_SESSION['payment_uploaded'] = true;
                    $_SESSION['payment_slip'] = $target_file;
                    $_SESSION['payment_slip_type'] = mime_content_type($target_file); // MIME Type

                    // แสดงข้อความสำเร็จ
                    $_SESSION['success_message'] = "Payment slip uploaded successfully.";
                } else {
                    $_SESSION['error_message'] = "Error uploading payment slip.";
                    header("Location: checkout.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "File upload error: " . $file_error;
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
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: checkout.php");
    exit;
}
?>