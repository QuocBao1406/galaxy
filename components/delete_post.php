<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("Lỗi: Bạn phải đăng nhập.");
}

// 2. Lấy ID bài viết
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id === 0) {
    die("ID bài viết không hợp lệ.");
}

$current_user_id = $_SESSION['user_id'];

$conn->begin_transaction();
try {
    // 3. Lấy thông tin bài viết để xác thực quyền
    $stmt_check = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt_check->bind_param("i", $post_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        throw new Exception("Không tìm thấy bài viết.");
    }
    $post = $result_check->fetch_assoc();

    // 4. Xác thực quyền sở hữu
    if ($current_user_id != $post['user_id']) {
        throw new Exception("Bạn không có quyền xóa bài viết này.");
    }
    $stmt_check->close();

    // 5. Lấy và xóa tất cả các file media liên quan
    $stmt_get_media = $conn->prepare("SELECT file_path FROM post_media WHERE post_id = ?");
    $stmt_get_media->bind_param("i", $post_id);
    $stmt_get_media->execute();
    $media_files = $stmt_get_media->get_result();
    
    while ($media = $media_files->fetch_assoc()) {
        $file_server_path = $_SERVER['DOCUMENT_ROOT'] . $media['file_path'];
        if (file_exists($file_server_path)) {
            unlink($file_server_path);
        }
    }
    $stmt_get_media->close();

    // 6. Xóa các bản ghi media trong CSDL
    $stmt_delete_media = $conn->prepare("DELETE FROM post_media WHERE post_id = ?");
    $stmt_delete_media->bind_param("i", $post_id);
    $stmt_delete_media->execute();
    $stmt_delete_media->close();

    // 7. Xóa các bản ghi liên quan (cảm xúc, bình luận)
    $stmt_delete_reactions = $conn->prepare("DELETE FROM reactions WHERE post_id = ?");
    $stmt_delete_reactions->bind_param("i", $post_id);
    $stmt_delete_reactions->execute();

    $stmt_delete_comments = $conn->prepare("DELETE FROM comments WHERE post_id = ?");
    $stmt_delete_comments->bind_param("i", $post_id);
    $stmt_delete_comments->execute();

    // 8. Xóa bài đăng
    $stmt_delete_post = $conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt_delete_post->bind_param("i", $post_id);
    $stmt_delete_post->execute();

    $conn->commit();

    header("Location: congdong.php?status=deleted");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Lỗi khi xóa bài viết: " . $e->getMessage());
} finally {
    if (isset($stmt_check)) $stmt_check->close();
    if (isset($stmt_get_media)) $stmt_get_media->close();
    if (isset($stmt_delete_media)) $stmt_delete_media->close();
    if (isset($stmt_delete_reactions)) $stmt_delete_reactions->close();
    if (isset($stmt_delete_comments)) $stmt_delete_comments->close();
    if (isset($stmt_delete_post)) $stmt_delete_post->close();
    $conn->close();
}
?>