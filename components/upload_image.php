<?php
session_start();
header("Content-Type: application/json");

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Lỗi upload']);
    exit;
}

$upload_dir = 'uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$image = $_FILES['image'];

// Kiểm tra loại file
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $image['tmp_name']);
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($mime_type, $allowed_types)) {
    echo json_encode(['success' => false, 'error' => 'Loại ảnh không hợp lệ']);
    exit;
}

// Giới hạn kích thước (5MB)
if ($image['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'error' => 'Ảnh quá lớn']);
    exit;
}

// Đặt tên file
$ext = pathinfo($image['name'], PATHINFO_EXTENSION);
$filename = $upload_dir . 'chat_' . uniqid() . '_' . time() . '.' . $ext;

if (move_uploaded_file($image['tmp_name'], $filename)) {
    echo json_encode(['success' => true, 'image_path' => $filename]);
} else {
    echo json_encode(['success' => false, 'error' => 'Lỗi lưu file']);
}