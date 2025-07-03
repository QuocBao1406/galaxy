<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        die("Mật khẩu xác nhận không khớp.");
    }
    if (strlen($new_password) < 8) {
        die("Mật khẩu mới phải có ít nhất 8 ký tự.");
    }

    // Kiểm tra lại token một lần nữa
    $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token hợp lệ, tiến hành cập nhật mật khẩu
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu và xóa token
        $update_stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE reset_token = ?");
        $update_stmt->bind_param("ss", $hashed_password, $token);
        
        if ($update_stmt->execute()) {
            echo "Cập nhật mật khẩu thành công! Bạn có thể đăng nhập ngay bây giờ.";
            echo "<br><a href='login-register.html'>Tới trang Đăng nhập</a>";
        } else {
            echo "Đã có lỗi xảy ra. Vui lòng thử lại.";
        }
    } else {
        echo "Token không hợp lệ hoặc đã hết hạn.";
    }
}
?>