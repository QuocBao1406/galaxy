<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE username LIKE CONCAT(?, '%') ORDER BY username LIMIT 10");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($users);
}
?>