<?php
session_start();
require 'connectdb.php'; // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบว่าได้อัปโหลดสลิปหรือยัง
    if (!isset($_SESSION['payment_uploaded'])) {
        $_SESSION['error_message'] = "กรุณาอัปโหลดสลิปโอนเงินก่อนยืนยันคำสั่งซื้อ";
        header("Location: checkout.php");
        exit;
    }

    // ตรวจสอบประเภทไฟล์
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf', 'image/jpg'];

    $payment_slip = $_SESSION['payment_slip']; // ดึง path ไฟล์ที่อัปโหลด
    $payment_slip_type = mime_content_type($payment_slip); // ตรวจสอบประเภทไฟล์จริง
    
    if (!in_array($payment_slip_type, $allowed_types)) {
        $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or PDF files are allowed.";
        header("Location: checkout.php");
        exit;
    }
        if (isset($_FILES['payment_slip']) && in_array($_FILES['payment_slip']['type'], $allowed_types)) {
        // ตรวจสอบข้อผิดพลาดของไฟล์
        if ($_FILES['payment_slip']['error'] == UPLOAD_ERR_OK) {
            $upload_dir = 'uploads/'; // โฟลเดอร์เก็บไฟล์
            $file_name = basename($_FILES['payment_slip']['name']);
            $target_file = $upload_dir . $file_name;

            // อัปโหลดไฟล์
            if (move_uploaded_file($_FILES['payment_slip']['tmp_name'], $target_file)) {
                $_SESSION['payment_uploaded'] = true;
                $_SESSION['payment_slip'] = $target_file;

                // ดึงค่าจาก Session
                $full_name = $_SESSION['user_full_name'];
                $phone = $_SESSION['user_phone'];
                $address = $_SESSION['user_address'];
                $province = $_SESSION['user_province'];
                $zip_code = $_SESSION['user_zip_code'];
                $total_price = $_SESSION['cart_total'] + 50;  // คำนวณรวมค่าจัดส่ง
                $payment_proof = $_SESSION['payment_slip'];  // ใช้ path ของไฟล์ที่อัปโหลด

                // บันทึกลงฐานข้อมูล
                $stmt = $conn->prepare("INSERT INTO orders (full_name, phone, address, province, zip_code, total_price, payment_proof, status) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->bind_param("sssssss", $full_name, $phone, $address, $province, $zip_code, $total_price, $payment_proof);

                if ($stmt->execute()) {
                    $_SESSION['success_message'] = "คำสั่งซื้อของคุณได้รับการบันทึกเรียบร้อย!";
                    unset($_SESSION['cart']); // ล้างตะกร้า
                    unset($_SESSION['payment_uploaded']); // รีเซ็ตการอัปโหลดสลิป
                    header("Location: order_success.php");
                    exit;
                } else {
                    $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการสั่งซื้อ กรุณาลองใหม่";
                    header("Location: checkout.php");
                    exit;
                }
            } else {
                $_SESSION['error_message'] = "Error moving uploaded file.";
                header("Location: checkout.php");
                exit;
            }
        } else {
            $_SESSION['error_message'] = "File upload error: " . $_FILES['payment_slip']['error'];
            header("Location: checkout.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Invalid file type. Only JPEG, PNG, or PDF files are allowed.";
        header("Location: checkout.php");
        exit;
    }
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: checkout.php");
    exit;
}
?>
