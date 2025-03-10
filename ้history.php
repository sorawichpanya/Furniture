<?php
session_start();
include 'connectdb.php'; // ไฟล์เชื่อมต่อฐานข้อมูล

if (!isset($_SESSION['username'])) {
    die("กรุณาเข้าสู่ระบบก่อน!");
}

// ดึง user_id ของผู้ใช้จาก session
$username = $_SESSION['username'];
$sql_user = "SELECT id FROM users WHERE username = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("s", $username);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$user = $result_user->fetch_assoc();
$user_id = $user['id'];

$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการสั่งซื้อ</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <h2>ประวัติการสั่งซื้อ</h2>
    <table>
        <tr>
            <th>รหัสคำสั่งซื้อ</th>
            <th>วันที่สั่ง</th>
            <th>ราคารวม</th>
            <th>สถานะ</th>
            <th>ดูรายละเอียด</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= $row['order_date']; ?></td>
            <td><?= number_format($row['total_price'], 2); ?> บาท</td>
            <td><?= ucfirst($row['status']); ?></td>
            <td><a href="order_detail.php?order_id=<?= $row['id']; ?>">ดู</a></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
