<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

if (!isset($_SESSION['user_id']) || empty($_POST['group_id'])) {
    header("Location: nhom.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$group_id = $_POST['group_id'];

$stmt = $conn->prepare("DELETE FROM group_members WHERE group_id = ? AND user_id = ?");
$stmt->bind_param("ii", $group_id, $user_id);
$stmt->execute();

header("Location: nhom.php?group_id=$group_id");
exit();