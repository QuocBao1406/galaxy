<?php
session_start();
require_once 'db.php'; // Kết nối CSDL

// Hàm để gửi phản hồi JSON và kết thúc script
function send_json_response($success, $message) {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit();
}

// 1. Kiểm tra xem user đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    send_json_response(false, 'Lỗi: Phiên đăng nhập không hợp lệ. Vui lòng đăng nhập lại.');
}
$user_id = $_SESSION['user_id'];

// 2. Nhận dữ liệu JSON từ JavaScript
$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    send_json_response(false, 'Lỗi: Dữ liệu gửi lên không hợp lệ.');
}

$old_password = $input['old_password'] ?? '';
$new_password = $input['new_password'] ?? '';
$confirm_password = $input['confirm_password'] ?? '';

// 3. Validate dữ liệu đầu vào
if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
    send_json_response(false, 'Vui lòng nhập đầy đủ các trường.');
}
if (strlen($new_password) < 8) {
    send_json_response(false, 'Mật khẩu mới phải có ít nhất 8 ký tự.');
}
if ($new_password !== $confirm_password) {
    send_json_response(false, 'Mật khẩu mới và mật khẩu xác nhận không khớp.');
}
if ($new_password === $old_password) {
    send_json_response(false, 'Mật khẩu mới không được trùng với mật khẩu cũ.');
}

// 4. Lấy mật khẩu hiện tại từ CSDL
$stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    send_json_response(false, 'Lỗi: Không tìm thấy người dùng.');
}
$user = $result->fetch_assoc();
$current_hashed_password = $user['password'];
$stmt->close();

// 5. So sánh mật khẩu cũ người dùng nhập với mật khẩu trong CSDL
if (!password_verify($old_password, $current_hashed_password)) {
    send_json_response(false, 'Mật khẩu cũ không chính xác.');
}

// 6. Mã hóa mật khẩu mới và cập nhật vào CSDL
$new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
$update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
$update_stmt->bind_param('si', $new_hashed_password, $user_id);

if ($update_stmt->execute()) {
    send_json_response(true, 'Đổi mật khẩu thành công!');
} else {
    send_json_response(false, 'Đã xảy ra lỗi khi cập nhật mật khẩu. Vui lòng thử lại.');
}

$update_stmt->close();
$conn->close();
?>