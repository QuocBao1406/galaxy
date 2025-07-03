<?php
$servername = "localhost";
$username = "root";
$password = ""; // Mặc định XAMPP không có mật khẩu
$dbname = "galaxy"; // Thay bằng tên database của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
