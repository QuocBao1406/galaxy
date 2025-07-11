<?php
require_once 'check_admin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'], $_POST['news_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    $news_id = (int)$_POST['news_id'];

    // Xóa bình luận theo ID
    $stmt = $conn->prepare("DELETE FROM comments_new WHERE id = ?");
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();

    // Quay lại trang sửa tin
    header("Location: news_form.php?id=$news_id");
    exit();
}
?>