<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

// 1. Kiểm tra đăng nhập và phương thức
if (!isset($_SESSION['user_id'])) { die("Lỗi: Bạn phải đăng nhập."); }
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Yêu cầu không hợp lệ."); }

$conn->begin_transaction();
try {
    // 2. Lấy dữ liệu từ form
    $post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $media_to_delete = isset($_POST['delete_media']) ? $_POST['delete_media'] : [];

    if ($post_id === 0) { throw new Exception("ID bài viết không hợp lệ."); }

    // 3. Xác thực quyền sở hữu
    $stmt_check = $conn->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt_check->bind_param("i", $post_id);
    $stmt_check->execute();
    $post = $stmt_check->get_result()->fetch_assoc();
    if (!$post || $_SESSION['user_id'] != $post['user_id']) {
        throw new Exception("Bạn không có quyền sửa bài viết này.");
    }
    $stmt_check->close();

    // 4. Cập nhật nội dung bài viết
    $stmt_update_content = $conn->prepare("UPDATE posts SET content = ? WHERE id = ?");
    $stmt_update_content->bind_param("si", $content, $post_id);
    $stmt_update_content->execute();
    $stmt_update_content->close();

    // 5. Xử lý xóa media đã chọn
    if (!empty($media_to_delete)) {
        // Đảm bảo các ID là số nguyên để tránh SQL injection
        $ids_placeholder = implode(',', array_fill(0, count($media_to_delete), '?'));
        $types = str_repeat('i', count($media_to_delete));

        // Lấy đường dẫn file để xóa trên server
        $stmt_get_paths = $conn->prepare("SELECT file_path FROM post_media WHERE id IN ($ids_placeholder)");
        $stmt_get_paths->bind_param($types, ...$media_to_delete);
        $stmt_get_paths->execute();
        $paths_result = $stmt_get_paths->get_result();
        while($row = $paths_result->fetch_assoc()) {
            $file_server_path = $_SERVER['DOCUMENT_ROOT'] . $row['file_path'];
            if(file_exists($file_server_path)) {
                unlink($file_server_path);
            }
        }
        $stmt_get_paths->close();

        // Xóa bản ghi media trong CSDL
        $stmt_delete_media = $conn->prepare("DELETE FROM post_media WHERE id IN ($ids_placeholder)");
        $stmt_delete_media->bind_param($types, ...$media_to_delete);
        $stmt_delete_media->execute();
        $stmt_delete_media->close();
    }

    // 6. Xử lý upload file media mới (logic tương tự submit_post.php)
    if (isset($_FILES['new_media']) && !empty($_FILES['new_media']['name'][0])) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/galaxy/uploads/';
        $total_files = count($_FILES['new_media']['name']);

        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['new_media']['error'][$i] === UPLOAD_ERR_OK) {
                $file_tmp_path = $_FILES['new_media']['tmp_name'][$i];
                $file_name = $_FILES['new_media']['name'][$i];
                $file_type = mime_content_type($file_tmp_path);
                
                $media_type = '';
                $sub_dir = '';

                if (strpos($file_type, 'image/') === 0) {
                    $media_type = 'image';
                    $sub_dir = 'images/';
                } elseif (strpos($file_type, 'video/') === 0) {
                    $media_type = 'video';
                    $sub_dir = 'videos/';
                }

                if ($media_type) {
                    $destination_dir = $upload_dir . $sub_dir;
                    if (!file_exists($destination_dir)) { mkdir($destination_dir, 0777, true); }
                    
                    $new_file_name = uniqid() . '-' . htmlspecialchars(basename($file_name));
                    $destination_path = $destination_dir . $new_file_name;
                    
                    if (move_uploaded_file($file_tmp_path, $destination_path)) {
                        $file_path_db = '/galaxy/uploads/' . $sub_dir . $new_file_name;
                        
                        $stmt_media = $conn->prepare("INSERT INTO post_media (post_id, file_path, media_type) VALUES (?, ?, ?)");
                        $stmt_media->bind_param("iss", $post_id, $file_path_db, $media_type);
                        $stmt_media->execute();
                        $stmt_media->close();
                    }
                }
            }
        }
    }

    // 7. Hoàn tất và chuyển hướng
    $conn->commit();
    header("Location: post_details.php?id=" . $post_id . "&status=updated");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Đã xảy ra lỗi khi cập nhật: " . $e->getMessage());
} finally {
    $conn->close();
}
?>