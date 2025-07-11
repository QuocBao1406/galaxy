<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php'; // file kết nối CSDL

$current_user_id = $_SESSION['user_id'];
$followed_id = $_POST['followed_id'];
$action = $_POST['action'];

if ($current_user_id && $followed_id && $current_user_id != $followed_id) {
    if ($action === 'follow') {
        $stmt = $conn->prepare("INSERT IGNORE INTO follows (follower_id, followed_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $current_user_id, $followed_id);
        $stmt->execute();
    } elseif ($action === 'unfollow') {
        $stmt = $conn->prepare("DELETE FROM follows WHERE follower_id = ? AND followed_id = ?");
        $stmt->bind_param("ii", $current_user_id, $followed_id);
        $stmt->execute();
    }
}
header("Location: /galaxy/components/trangcanhan.php?user_id=$followed_id");
exit;