<?php
require_once 'check_admin.php';
$id = (int)$_GET['id'];
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: user_management.php");
?>