<?php

session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES['payment_slip'])) {
        // ตรวจสอบประเภทไฟล์ที่อัปโหลด
        $file_tmp = $_FILES['payment_slip']['tmp_name'];
        $file_type = mime_content_type($file_tmp); // ตรวจสอบประเภทไฟล์จริง
        
        $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
        if (in_array($_FILES['payment_slip']['type'], $allowed_types)) {
            // ตรวจสอบว่าไฟล์ไม่มีข้อผิดพลาด
            if ($_FILES['payment_slip']['error'] == UPLOAD_ERR_OK) {
                $upload_dir = 'uploads/'; // โฟลเดอร์ที่จะเก็บไฟล์
                $file_name = basename($_FILES['payment_slip']['name']);
                $target_file = $upload_dir . $file_name;

                // อัปโหลดไฟล์
                if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
                    $_SESSION['payment_uploaded'] = true;  // ตั้งค่าสถานะการอัปโหลด
                    $_SESSION['payment_slip'] = $target_file;  // เก็บ path ของไฟล์ใน session

                    $_SESSION['success_message'] = "Payment slip uploaded successfully."; // ข้อความสำเร็จ
                    header("Location: checkout.php"); // รีไดเร็กต์ไปหน้า checkout
                    exit;
                } else {
                    $_SESSION['error_message'] = "Error moving uploaded file."; // ข้อความเมื่อไม่สามารถย้ายไฟล์
                    header("Location: checkout.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "File upload error: " . $_FILES['payment_slip']['error']; // แสดงข้อความผิดพลาด
                header("Location: checkout.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or PDF files are allowed."; // ตรวจสอบประเภทไฟล์
            header("Location: checkout.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "No file uploaded."; // ถ้าไม่มีไฟล์
        header("Location: checkout.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request."; // ถ้าไม่ได้ส่งข้อมูล POST
    header("Location: checkout.php");
    exit;
}
?>
