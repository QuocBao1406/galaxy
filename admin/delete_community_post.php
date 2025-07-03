<?php
require_once 'check_admin.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id === 0) die("ID bài viết không hợp lệ.");

$conn->begin_transaction();
try {
    // Lấy và xóa media của các bình luận thuộc bài đăng này (Nếu có)
    // Lấy và xóa media của bài đăng
    $stmt_media = $conn->prepare("SELECT file_path FROM post_media WHERE post_id = ?");
    $stmt_media->bind_param("i", $post_id);
    $stmt_media->execute();
    $media_files = $stmt_media->get_result();
    while ($media = $media_files->fetch_assoc()) {
        if (file_exists('..' . $media['file_path'])) unlink('..' . $media['file_path']);
    }
    $stmt_media->close();
    $conn->query("DELETE FROM post_media WHERE post_id = $post_id");
    $conn->query("DELETE FROM reactions WHERE post_id = $post_id");
    $conn->query("DELETE FROM comments WHERE post_id = $post_id");
    $conn->query("DELETE FROM posts WHERE id = $post_id");

    $conn->commit();
    header("Location: community_management.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Lỗi khi xóa: " . $e->getMessage());
}
?>