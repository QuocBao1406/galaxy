<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$desc = $_POST['description'];
$privacy = $_POST['privacy'];
$cover_path = null;

// Xử lý ảnh bìa nếu có
if (!empty($_FILES['cover_image']['name'])) {
    $filename = time() . '_' . basename($_FILES['cover_image']['name']);
    $upload_dir = '/galaxy/uploads/group_covers/';
    $target_path = $_SERVER['DOCUMENT_ROOT'] . $upload_dir . $filename;

    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_path)) {
        $cover_path = $upload_dir . $filename;
    }
}

// Tạo nhóm mới
$stmt = $conn->prepare("INSERT INTO groups (name, description, cover_image, privacy, created_by) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $name, $desc, $cover_path, $privacy, $user_id);
$stmt->execute();

// Lấy ID nhóm vừa tạo
$group_id = $conn->insert_id;

// Thêm người tạo nhóm vào bảng group_members với vai trò admin
$role = 'admin';
$stmt = $conn->prepare("INSERT INTO group_members (group_id, user_id, role) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $group_id, $user_id, $role);
$stmt->execute();

// Chuyển hướng sau khi tạo nhóm
header("Location: nhom.php");
exit();