<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

if (!isset($_SESSION['user_id']) || empty($_POST['group_id'])) {
    header("Location: nhom.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$group_id = $_POST['group_id'];

// Thêm vào bảng chờ duyệt
$stmt = $conn->prepare("INSERT IGNORE INTO group_join_requests (group_id, user_id) VALUES (?, ?)");
$stmt->bind_param("ii", $group_id, $user_id);
$stmt->execute();

header("Location: nhom.php?group_id=$group_id&request_sent=1");
exit();