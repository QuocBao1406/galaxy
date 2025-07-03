<?php
require_once 'check_admin.php';
include 'header.php';

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($post_id === 0) {
    die("ID bài viết không hợp lệ.");
}

// SỬA LẠI BƯỚC 1: Thêm u.is_verified vào câu lệnh SQL để lấy tích xanh của người đăng bài
$sql_post = "SELECT p.id, p.content, p.created_at, u.username, u.avatar, u.is_verified
             FROM posts p JOIN users u ON p.user_id = u.id
             WHERE p.id = ?";
$stmt_post = $conn->prepare($sql_post);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$post_result = $stmt_post->get_result();
if ($post_result->num_rows === 0) {
    die("Không tìm thấy bài viết.");
}
$post = $post_result->fetch_assoc();
$stmt_post->close();

// Lấy media của bài viết (nếu có)
$sql_media = "SELECT file_path, media_type FROM post_media WHERE post_id = ?";
$stmt_media = $conn->prepare($sql_media);
$stmt_media->bind_param("i", $post_id);
$stmt_media->execute();
$media_result = $stmt_media->get_result();

// Lấy bình luận của bài viết (phần này đã đúng)
$sql_comments = "SELECT c.id, c.content, c.created_at, u.username as comment_author, u.is_verified
                 FROM comments c JOIN users u ON c.user_id = u.id
                 WHERE c.post_id = ? ORDER BY c.created_at DESC";
$stmt_comments = $conn->prepare($sql_comments);
$stmt_comments->bind_param("i", $post_id);
$stmt_comments->execute();
$comments_result = $stmt_comments->get_result();
?>

<div class="container mt-4">
    <a href="community_management.php" class="btn btn-secondary mb-3">&laquo; Quay lại danh sách</a>

    <div class="card">
        <div class="card-header d-flex align-items-center">
            <?php $avatar_path = $post['avatar'] ? $post['avatar'] : '/galaxy/assets/images/default-avatar.png'; ?>
            <img src="<?= htmlspecialchars($avatar_path) ?>" width="40" height="40" class="rounded-circle mr-2">
            <strong>
                <?php
                // SỬA LẠI BƯỚC 2: Thêm logic hiển thị tích xanh cho người đăng bài
                echo htmlspecialchars($post['username']);
                if ($post['is_verified']) {
                    echo ' <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>';
                }
                ?>
            </strong>
            <small class="ml-auto text-muted"><?= $post['created_at'] ?></small>
        </div>
        <div class="card-body">
            <?php if(!empty($post['content'])): ?>
                <p class="card-text" style="white-space: pre-wrap; font-size: 1.1rem;"><?= htmlspecialchars($post['content']) ?></p>
            <?php endif; ?>

            <?php
            if ($media_result->num_rows > 0) {
                echo '<hr>';
                while ($media = $media_result->fetch_assoc()) {
                    if ($media['media_type'] == 'image') {
                        echo '<img src="' . htmlspecialchars($media['file_path']) . '" class="img-fluid rounded mb-2" alt="Ảnh bài đăng">';
                    }
                }
            }
            $stmt_media->close();
            ?>
        </div>
    </div>

    <div class="mt-4">
        <h4>Bình luận (<?= $comments_result->num_rows ?>)</h4>
        <ul class="list-group">
            <?php
            if ($comments_result->num_rows > 0) {
                while ($comment = $comments_result->fetch_assoc()) {
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>
                            <?= htmlspecialchars($comment['comment_author']) ?>
                            <?php if ($comment['is_verified']): ?>
                                <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>
                            <?php endif; ?>
                        :</strong>
                        <p class="mb-0 d-inline"><?= htmlspecialchars($comment['content']) ?></p>
                        <small class="text-muted d-block"><?= $comment['created_at'] ?></small>
                    </div>
                    <a href="delete_comment.php?comment_id=<?= $comment['id'] ?>&post_id=<?= $post_id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa bình luận này?');">Xóa</a>
                </li>
            <?php
                }
            } else {
                echo '<li class="list-group-item">Chưa có bình luận nào.</li>';
            }
            $stmt_comments->close();
            ?>
        </ul>
    </div>
</div>

<?php include 'footer.php'; ?>