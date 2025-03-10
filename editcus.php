<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once("../connectdb.php");

// ดึงข้อมูลลูกค้าจากตาราง Register
$sql = "SELECT * FROM `Register` WHERE `id` = '{$_GET['id']}'";
$rs = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($rs);

if (!$data) {
    echo "ไม่พบข้อมูลลูกค้า";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head Section -->
</head>

<body>
    <!-- Topbar, Navbar -->

    <!-- แก้ไขข้อมูลลูกค้า Start -->
    <div class="container-fluid pt-5">
        <!-- Form Content -->
        <form method="post" action="">
            <div class="control-group">
                <label for="name">ชื่อ-สกุล:</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($data['name']); ?>" required>
            </div>

            <div class="control-group mt-3">
                <label for="phone">เบอร์โทรศัพท์:</label>
                <input type="text" class="form-control" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($data['phone']); ?>" required>
            </div>

            <div class="control-group mt-3">
                <label for="username">ชื่อผู้ใช้:</label>
                <input type="text" class="form-control" id="username" name="username" 
                       value="<?php echo htmlspecialchars($data['username']); ?>" required>
            </div>

            <div class="control-group mt-3">
                <label for="password">รหัสผ่าน:</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="text-muted">หากไม่ต้องการเปลี่ยนรหัสผ่าน, ไม่ต้องใส่ข้อมูลในช่องนี้</small>
            </div>

            <div class="mt-4">
                <button class="btn btn-primary py-2 px-4" type="submit">บันทึกการเปลี่ยนแปลง</button>
            </div>
        </form>
    </div>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        
        // Hash รหัสผ่าน (ถ้ามีการเปลี่ยนแปลง)
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE Register SET 
                    name = '$name',
                    phone = '$phone',
                    username = '$username',
                    password = '$password'
                    WHERE id = '{$_GET['id']}'";
        } else {
            $sql = "UPDATE Register SET 
                    name = '$name',
                    phone = '$phone',
                    username = '$username'
                    WHERE id = '{$_GET['id']}'";
        }
        
        echo "SQL: " . $sql . "<br>";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('อัปเดตข้อมูลสำเร็จ!');
                window.location.href = 'editcus.php?id={$_GET['id']}';
            </script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "');</script>";
        }
    }
    ?>
</body>
</html>
