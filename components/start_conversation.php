<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

$from_id = $_SESSION['user_id'];
$to_id = intval($_GET['to_user_id']);

$min_id = min($from_id, $to_id);
$max_id = max($from_id, $to_id);

// Kiểm tra đã có cuộc trò chuyện chưa
$stmt = $conn->prepare("SELECT id FROM conversations WHERE user1_id = ? AND user2_id = ?");
$stmt->bind_param("ii", $min_id, $max_id);
$stmt->execute();
$stmt->bind_result($conv_id);
if ($stmt->fetch()) {
    $stmt->close();
    header("Location: /galaxy/components/message.php?conversation_id=$conv_id");
    exit();
}
$stmt->close();

// Tạo mới nếu chưa có
$stmt = $conn->prepare("INSERT INTO conversations (user1_id, user2_id) VALUES (?, ?)");
$stmt->bind_param("ii", $min_id, $max_id);
$stmt->execute();
$new_conv_id = $stmt->insert_id;
$stmt->close();

header("Location: /galaxy/components/message.php?conversation_id=$conv_id");
exit();