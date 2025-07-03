<?php
require_once 'check_admin.php'; // Luôn kiểm tra quyền admin

// Lấy ID bình luận và ID bài viết từ URL
$comment_id = isset($_GET['comment_id']) ? (int)$_GET['comment_id'] : 0;
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

// Kiểm tra ID có hợp lệ không
if ($comment_id === 0 || $post_id === 0) {
    die("ID không hợp lệ.");
}

// Chuẩn bị và thực thi lệnh xóa
$stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
$stmt->bind_param("i", $comment_id);

if ($stmt->execute()) {
    // Nếu xóa thành công, chuyển hướng người dùng về lại trang xem bài viết
    header("Location: view_post.php?id=" . $post_id);
    exit();
} else {
    echo "Lỗi khi xóa bình luận: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>