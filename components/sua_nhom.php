<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

$user_id = $_SESSION['user_id'];
$group_id = $_POST['group_id'];
$name = $_POST['name'];
$desc = $_POST['description'];
$privacy = $_POST['privacy'];
$cover_path = null;

// Kiểm tra người tạo nhóm
$stmt_check = $conn->prepare("SELECT * FROM groups WHERE id = ? AND created_by = ?");
$stmt_check->bind_param("ii", $group_id, $user_id);
$stmt_check->execute();
if ($stmt_check->get_result()->num_rows === 0) {
    header("Location: nhom.php?error=unauthorized");
    exit();
}

// Xử lý ảnh mới (nếu có)
if (!empty($_FILES['cover_image']['name'])) {
    $filename = time() . '_' . basename($_FILES['cover_image']['name']);
    $upload_dir = '/galaxy/uploads/group_covers/';
    $target_path = $_SERVER['DOCUMENT_ROOT'] . $upload_dir . $filename;

    if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_path)) {
        $cover_path = $upload_dir . $filename;
        $stmt = $conn->prepare("UPDATE groups SET name = ?, description = ?, cover_image = ?, privacy = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $name, $desc, $cover_path, $privacy, $group_id);
    } else {
        header("Location: nhom.php?error=upload_fail");
        exit();
    }
} else {
    $stmt = $conn->prepare("UPDATE groups SET name = ?, description = ?, privacy = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $desc, $privacy, $group_id);
}

$stmt->execute();
header("Location: nhom.php?group_id=$group_id&success=updated");
exit();