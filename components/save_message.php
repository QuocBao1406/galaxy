<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$sender_id = $_SESSION['user_id'] ?? 0;
$conversation_id = intval($data['conversation_id']);
$message = trim($data['message'] ?? '');
$image_path = $data['image_path'] ?? null;

if ($sender_id && $conversation_id && ($message || $image_path)) {
    $stmt = $conn->prepare("INSERT INTO messages (conversation_id, sender_id, message, image_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $conversation_id, $sender_id, $message, $image_path);
    $stmt->execute();
    $stmt->close();

    $stmt2 = $conn->prepare("UPDATE conversations SET updated_at = NOW() WHERE id = ?");
    $stmt2->bind_param("i", $conversation_id);
    $stmt2->execute();
    $stmt2->close();

    echo json_encode(["success" => true]);
} else {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid input"]);
}