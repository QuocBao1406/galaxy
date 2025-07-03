<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

header('Content-Type: application/json');

// --- HÀM TẠO HTML CHO BÌNH LUẬN (ĐÃ ĐƯỢC SỬA) ---
function generate_comment_html($comment_id, $conn, $post_id, $loggedIn) {
    // SỬA LẠI CÂU SQL: Thêm "u.is_verified" để lấy trạng thái tích xanh
    $sql = "SELECT c.*, u.username, u.avatar, u.is_verified 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $comment_id);
    $stmt->execute();
    $comment = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$comment) return '';

    ob_start(); // Bắt đầu bộ đệm đầu ra để tạo HTML
    $comment_avatar = !empty($comment['avatar']) ? htmlspecialchars($comment['avatar']) : '/galaxy/images-icon/default_avatar.png';
    ?>
    <div class="comment-container" id="comment-<?php echo $comment['id']; ?>">
        <div class="comment">
            <img src="<?php echo $comment_avatar; ?>" class="avatar-sm">
            <div class="comment-body">
                <p class="mb-0">
                    <strong class="comment-author">
                        <?php 
                        // SỬA LẠI PHẦN HIỂN THỊ TÊN: Thêm logic kiểm tra và chèn icon tích xanh
                        echo htmlspecialchars($comment['username']); 
                        if ($comment['is_verified']) {
                            echo ' <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>';
                        }
                        ?>
                    </strong>
                </p>
                <?php if (!empty($comment['content'])): ?>
                    <p class="mb-0 small text-light"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                <?php endif; ?>
                
                <?php
                // Phần hiển thị media giữ nguyên
                $stmt_media = $conn->prepare("SELECT file_path, media_type FROM comment_media WHERE comment_id = ?");
                $stmt_media->bind_param("i", $comment['id']);
                $stmt_media->execute();
                $result_media = $stmt_media->get_result();
                if ($result_media->num_rows > 0) {
                    echo '<div class="comment-media-grid">';
                    while($media = $result_media->fetch_assoc()){
                        if ($media['media_type'] == 'image') echo '<img src="'.htmlspecialchars($media['file_path']).'" class="comment-media">';
                        elseif ($media['media_type'] == 'video') echo '<video src="'.htmlspecialchars($media['file_path']).'" controls class="comment-media"></video>';
                    }
                    echo '</div>';
                }
                $stmt_media->close();
                ?>

                <?php if ($loggedIn): ?>
                    <a href="#" class="reply-btn small" data-comment-id="<?php echo $comment['id']; ?>">Trả lời</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if ($loggedIn): ?>
        <div class="reply-form" id="reply-form-<?php echo $comment['id']; ?>" style="display:none;">
            <form action="submit_comment.php" method="POST" enctype="multipart/form-data" class="comment-submission-form">
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="hidden" name="parent_id" value="<?php echo $comment['id']; ?>">
                <div class="comment-input-area">
                    <textarea name="content" rows="1" placeholder="Viết câu trả lời..." class="form-control"></textarea>
                    <label class="file-upload-label" title="Thêm media"><i class="fas fa-paperclip"></i></label>
                    <input type="file" name="media[]" multiple accept="image/*,video/*" style="display:none;" class="comment-media-input">
                    <button type="submit" class="btn-send-comment" title="Gửi"><i class="fas fa-paper-plane"></i></button>
                </div>
                <div class="preview-container mt-2"></div>
            </form>
        </div>
        <?php endif; ?>
        <div class="replies nested-replies" id="replies-for-<?php echo $comment['id']; ?>"></div>
    </div>
    <?php
    return ob_get_clean(); // Trả về chuỗi HTML đã tạo
}


// --- PHẦN LOGIC XỬ LÝ CHÍNH (giữ nguyên không đổi) ---
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn phải đăng nhập.']);
    exit();
}
// ... phần code còn lại của bạn giữ nguyên ...
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
    exit();
}
$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$parent_id = isset($_POST['parent_id']) && !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : NULL;
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$has_files = isset($_FILES['media']) && !empty($_FILES['media']['name'][0]);
if (empty($content) && !$has_files) {
    echo json_encode(['success' => false, 'message' => 'Nội dung không được để trống.']);
    exit();
}
$conn->begin_transaction();
try {
    $stmt_comment = $conn->prepare("INSERT INTO comments (post_id, user_id, parent_id, content) VALUES (?, ?, ?, ?)");
    $stmt_comment->bind_param("iiis", $post_id, $user_id, $parent_id, $content);
    $stmt_comment->execute();
    $comment_id = $conn->insert_id;
    $stmt_comment->close();
    if ($has_files) {
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/galaxy/uploads/comments/';
        $total_files = count($_FILES['media']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            if ($_FILES['media']['error'][$i] === UPLOAD_ERR_OK) {
                $file_tmp_path = $_FILES['media']['tmp_name'][$i];
                $file_name = $_FILES['media']['name'][$i];
                $file_type = mime_content_type($file_tmp_path);
                $media_type = '';
                if (strpos($file_type, 'image/') === 0) $media_type = 'image';
                elseif (strpos($file_type, 'video/') === 0) $media_type = 'video';
                if ($media_type) {
                    if (!file_exists($upload_dir)) { mkdir($upload_dir, 0777, true); }
                    $new_file_name = uniqid() . '-' . htmlspecialchars(basename($file_name));
                    $destination_path = $upload_dir . $new_file_name;
                    if (move_uploaded_file($file_tmp_path, $destination_path)) {
                        $file_path_db = '/galaxy/uploads/comments/' . $new_file_name;
                        $stmt_media = $conn->prepare("INSERT INTO comment_media (comment_id, file_path, media_type) VALUES (?, ?, ?)");
                        $stmt_media->bind_param("iss", $comment_id, $file_path_db, $media_type);
                        $stmt_media->execute();
                        $stmt_media->close();
                    }
                }
            }
        }
    }
    $conn->commit();
    $new_comment_html = generate_comment_html($comment_id, $conn, $post_id, true);
    echo json_encode([
        'success' => true, 
        'comment_html' => $new_comment_html,
        'parent_id' => $parent_id
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>