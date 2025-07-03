<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    die("Lỗi: Bạn phải đăng nhập để đăng bài.");
}

$user_id = $_SESSION['user_id'];
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
// Kiểm tra xem có file nào được tải lên trong mảng 'media' không
$has_files = isset($_FILES['media']) && !empty($_FILES['media']['name'][0]);

// 2. Yêu cầu phải có nội dung hoặc có file tải lên
if (empty($content) && !$has_files) {
    die("Nội dung bài đăng hoặc file không được để trống.");
}

$conn->begin_transaction();
try {
    // 3. Tạo bài đăng trước để có post_id
    $stmt_post = $conn->prepare("INSERT INTO posts (user_id, content) VALUES (?, ?)");
    $stmt_post->bind_param("is", $user_id, $content);
    $stmt_post->execute();
    $post_id = $conn->insert_id; // Lấy ID của bài viết vừa được tạo
    $stmt_post->close();

    // 4. Xử lý upload và lưu từng file media (nếu có)
    if ($has_files) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/galaxy/uploads/';
        $total_files = count($_FILES['media']['name']);

        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['media']['error'][$i] === UPLOAD_ERR_OK) {
                $file_tmp_path = $_FILES['media']['tmp_name'][$i];
                $file_name = $_FILES['media']['name'][$i];
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

    $conn->commit();
    header("Location: congdong.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    die("Đã xảy ra lỗi khi đăng bài: " . $e->getMessage());
}
?>