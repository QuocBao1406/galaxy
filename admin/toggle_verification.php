<?php
require_once 'check_admin.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($user_id > 0 && ($action === 'grant' || $action === 'revoke')) {
    // Nếu hành động là 'grant', đặt is_verified = 1 (TRUE)
    // Nếu là 'revoke', đặt is_verified = 0 (FALSE)
    $new_status = ($action === 'grant') ? 1 : 0;

    $stmt = $conn->prepare("UPDATE users SET is_verified = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_status, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Sau khi xử lý, quay trở lại trang quản lý người dùng
header("Location: user_management.php");
exit();
?>