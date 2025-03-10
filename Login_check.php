<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'connectdb.php';
session_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password']

    $sql = "SELECT * FROM Register WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); 
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_array(MYSQLI_ASSOC);

    if ($row) {
        if (password_verify($password, $row['password'])) {
        
            $_SESSION["username"] = $row['username'];
            $_SESSION["name"] = $row['name'];
            $_SESSION["phone"] = $row['phone'];

        
            header("Location: index.php");

        } else {
            $_SESSION["Error"] = "<p>Your username or password is invalid</p>";
            header("Location: Login.php");
           
        }
    } else {

        $_SESSION["Error"] = "<p>Your username or password is invalid</p>";
        header("Location: Login.php");
        exit();
    }
} else {
    $_SESSION["Error"] = "<p>Please fill in both fields</p>";
    header("Location: Login.php");
    exit(); 
}
?>
