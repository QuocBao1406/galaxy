<?php
session_start();
// Đường dẫn này đúng vì db.php ở thư mục cha (galaxy)
include '../db.php'; 

$username = $_POST['username'];
$password = $_POST['password'];


// Lấy thông tin user, bao gồm cả 'role' và 'avatar'
$sql = "SELECT id, username, password, role, avatar FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['avatar'] = $user['avatar'];

        if ($user['role'] === 'admin') {
            // SỬA LẠI ĐƯỜNG DẪN: đi ra khỏi thư mục TAIKHOAN rồi mới vào admin
            header("Location: ../admin/index.php"); 
        } else {
            // SỬA LẠI ĐƯỜNG DẪN: đi ra khỏi thư mục TAIKHOAN để về trang chủ
            header("Location: ../trangchu.php"); 
        }
        exit();
    }
}

// Nếu đăng nhập thất bại
echo "Sai tên đăng nhập, mật khẩu, hoặc bạn không có quyền truy cập.";
$stmt->close();
$conn->close();
?>