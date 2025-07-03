<?php
require_once '../db.php';

// <<< SỬA LỖI: Đặt múi giờ cho PHP >>>
date_default_timezone_set('Asia/Ho_Chi_Minh');

if (!isset($_GET['token'])) {
    die("Yêu cầu không hợp lệ.");
}

$token = $_GET['token'];

// Bây giờ MySQL sẽ so sánh với NOW() theo cùng múi giờ
$stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Token không hợp lệ hoặc đã hết hạn. Vui lòng thực hiện lại yêu cầu quên mật khẩu.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt lại Mật khẩu</title>
    </head>
<body>
    <div class="form-box">
        <h2>Đặt lại mật khẩu mới</h2>
        <form action="update_password.php" method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="input-group">
                <i class="fas fa-key"></i>
                <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
            </div>
            <div class="input-group">
                <i class="fas fa-key"></i>
                <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required>
            </div>
            <button type="submit">Cập nhật Mật khẩu</button>
        </form>
    </div>
</body>
</html>