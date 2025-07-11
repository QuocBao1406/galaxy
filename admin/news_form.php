<?php
require_once 'check_admin.php';
include 'header.php';

// Khởi tạo các biến cho cả 2 ngôn ngữ
$is_edit = isset($_GET['id']);
$news_id = 0;
$title_vi = $excerpt_vi = $full_content_vi = $category_vi = '';
$title_en = $excerpt_en = $full_content_en = $category_en = '';
$image_url = '';
$is_featured = 0;

if ($is_edit) {
    $news_id = (int)$_GET['id'];
    // Lấy tất cả các cột ngôn ngữ khi sửa
    $stmt = $conn->prepare("SELECT title_vi, excerpt_vi, full_content_vi, category_vi, title_en, excerpt_en, full_content_en, category_en, image_url, is_featured FROM news WHERE id = ?");
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($news = $result->fetch_assoc()) {
        $title_vi = $news['title_vi'];
        $excerpt_vi = $news['excerpt_vi'];
        $full_content_vi = $news['full_content_vi'];
        $category_vi = $news['category_vi'];
        $title_en = $news['title_en'];
        $excerpt_en = $news['excerpt_en'];
        $full_content_en = $news['full_content_en'];
        $category_en = $news['category_en'];
        $image_url = $news['image_url'];
        $is_featured = $news['is_featured'];
    }
    $stmt->close();
}
?>

<h2><?= $is_edit ? 'Sửa' : 'Thêm' ?> Tin tức</h2>

<form action="news_action.php" method="post" enctype="multipart/form-data">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?= $news_id ?>">
    <?php endif; ?>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#vietnamese" role="tab">Tiếng Việt</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="en-tab" data-toggle="tab" href="#english" role="tab">Tiếng Anh</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="vietnamese" role="tabpanel">
            <div class="form-group mt-3">
                <label>Tiêu đề (Tiếng Việt)</label>
                <input type="text" name="title_vi" class="form-control" value="<?= htmlspecialchars($title_vi) ?>" required>
            </div>
            <div class="form-group">
                <label>Thể loại (Tiếng Việt)</label>
                <input type="text" name="category_vi" class="form-control" value="<?= htmlspecialchars($category_vi) ?>">
            </div>
            <div class="form-group">
                <label>Đoạn tóm tắt (Tiếng Việt)</label>
                <textarea name="excerpt_vi" class="form-control" rows="3"><?= htmlspecialchars($excerpt_vi) ?></textarea>
            </div>
            <div class="form-group">
                <label>Nội dung đầy đủ (Tiếng Việt)</label>
                <textarea name="full_content_vi" id="content_editor_vi" class="form-control" rows="15"><?= htmlspecialchars($full_content_vi) ?></textarea>
            </div>
        </div>
        <div class="tab-pane fade" id="english" role="tabpanel">
            <div class="form-group mt-3">
                <label>Title (English)</label>
                <input type="text" name="title_en" class="form-control" value="<?= htmlspecialchars($title_en) ?>">
            </div>
            <div class="form-group">
                <label>Category (English)</label>
                <input type="text" name="category_en" class="form-control" value="<?= htmlspecialchars($category_en) ?>">
            </div>
            <div class="form-group">
                <label>Excerpt (English)</label>
                <textarea name="excerpt_en" class="form-control" rows="3"><?= htmlspecialchars($excerpt_en) ?></textarea>
            </div>
            <div class="form-group">
                <label>Full Content (English)</label>
                <textarea name="full_content_en" id="content_editor_en" class="form-control" rows="15"><?= htmlspecialchars($full_content_en) ?></textarea>
            </div>
        </div>
    </div>
    
    <hr>
    <div class="form-group">
        <label>Ảnh đại diện (Featured Image)</label>
        <input type="file" name="featured_image" class="form-control-file">
        <?php if ($is_edit && $image_url): ?>
           <p class="mt-2">Ảnh hiện tại: <img src="<?= htmlspecialchars($image_url) ?>" height="100"></p>
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($image_url) ?>">
        <?php endif; ?>
    </div>

    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured" value="1" <?= $is_edit && $is_featured ? 'checked' : '' ?>>
        <label class="form-check-label" for="is_featured">Đánh dấu là tin nổi bật</label>
    </div>
    <button type="submit" name="save_news" class="btn btn-primary mt-3">Lưu</button>
</form>

<?php if ($is_edit): ?>
    <hr>
    <h4 class="mt-5">Bình luận của bài viết</h4>

    <?php
    $stmt = $conn->prepare("SELECT c.id, c.comment, c.created_at, u.username, u.avatar 
                            FROM comments_new c 
                            JOIN users u ON c.user_id = u.id 
                            WHERE c.news_id = ? 
                            ORDER BY c.created_at DESC");
    $stmt->bind_param("i", $news_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <?php while ($comment = $result->fetch_assoc()): ?>
        <div class="media mb-3 p-3 bg-light rounded shadow-sm">
            <img src="<?= htmlspecialchars($comment['avatar'] ?: 'assets/images/default-avatar.jpg') ?>" 
                 class="mr-3 rounded-circle" width="50" height="50" style="object-fit: cover;">
            <div class="media-body">
                <h6 class="mt-0 mb-1"><?= htmlspecialchars($comment['username']) ?></h6>
                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></small>
                <p class="mt-2"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                <form method="post" action="delete_comment_new.php" onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này?');">
                    <input type="hidden" name="comment_id" value="<?= $comment['id'] ?>">
                    <input type="hidden" name="news_id" value="<?= $news_id ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

<script>
  tinymce.init({ selector: 'textarea#content_editor_vi', /* ... các cấu hình khác ... */ });
  tinymce.init({ selector: 'textarea#content_editor_en', /* ... các cấu hình khác ... */ });
</script>

<?php include 'footer.php'; ?>