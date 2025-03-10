<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['payment_slip'])) {
    $upload_dir = "uploads/";
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $file_name = basename($_FILES['payment_slip']['name']);
    $file_type = $_FILES['payment_slip']['type'];
    $file_tmp = $_FILES['payment_slip']['tmp_name'];
    $file_path = $upload_dir . time() . "_" . $file_name; // ป้องกันชื่อไฟล์ซ้ำ

    $total_amount = $_SESSION['cart_total'] + 50; // คำนวณราคารวม + ค่าส่ง
    $paid_amount = floatval($_POST['paid_amount']); // รับค่าที่กรอกมา

    if ($paid_amount < $total_amount) {
        $_SESSION['error_message'] = "กรุณาโอนเงินให้ครบตามยอด Total: ฿" . number_format($total_amount, 2);
        header("Location: checkout.php");
        exit;
    }

    if (in_array($file_type, $allowed_types)) {
        if (move_uploaded_file($file_tmp, $file_path)) {
            $_SESSION['payment_uploaded'] = true;
            $_SESSION['payment_slip'] = $file_path;
            $_SESSION['success_message'] = "อัปโหลดสลิปสำเร็จ! ✅";
        } else {
            $_SESSION['error_message'] = "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    } else {
        $_SESSION['error_message'] = "ไฟล์ต้องเป็น JPG, PNG หรือ PDF เท่านั้น";
    }
} else {
    $_SESSION['error_message'] = "กรุณาเลือกไฟล์ก่อนอัปโหลด";
}

header("Location: checkout.php");
exit;
?>
