<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

if (!isset($_SESSION['user_id']) || empty($_POST['group_id']) || empty($_POST['approve_users'])) {
    header("Location: nhom.php");
    exit();
}

$group_id = $_POST['group_id'];
$approved_users = $_POST['approve_users'];
$creator_id = $_SESSION['user_id'];

// Kiểm tra người tạo nhóm
$stmt_check = $conn->prepare("SELECT id FROM groups WHERE id = ? AND created_by = ?");
$stmt_check->bind_param("ii", $group_id, $creator_id);
$stmt_check->execute();
$result = $stmt_check->get_result();
if ($result->num_rows === 0) {
    header("Location: nhom.php?error=unauthorized");
    exit();
}

// Thêm vào group_members và xóa khỏi request
foreach ($approved_users as $user_id) {
    $stmt_add = $conn->prepare("INSERT INTO group_members (group_id, user_id, role) VALUES (?, ?, 'member')");
    $stmt_add->bind_param("ii", $group_id, $user_id);
    $stmt_add->execute();

    $stmt_del = $conn->prepare("DELETE FROM group_join_requests WHERE group_id = ? AND user_id = ?");
    $stmt_del->bind_param("ii", $group_id, $user_id);
    $stmt_del->execute();
}

header("Location: nhom.php?group_id=$group_id&success=approved");
exit();