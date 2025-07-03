<?php
require_once 'check_admin.php';
include 'header.php';

// Khởi tạo các biến cho cả 2 ngôn ngữ
$is_edit = isset($_GET['id']);
$event_id = 0;
$title_vi = $content_vi = $title_en = $content_en = ''; 
$event_date = $image_url = '';

if ($is_edit) {
    $event_id = (int)$_GET['id'];
    // Lấy tất cả các cột ngôn ngữ khi sửa
    $stmt = $conn->prepare("SELECT title_vi, content_vi, title_en, content_en, event_date, image_url FROM events WHERE id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($event = $result->fetch_assoc()) {
        $title_vi = $event['title_vi'];
        $content_vi = $event['content_vi'];
        $title_en = $event['title_en'];
        $content_en = $event['content_en'];
        $event_date = $event['event_date'];
        $image_url = $event['image_url'];
    }
    $stmt->close();
}
?>

<h2><?= $is_edit ? 'Sửa' : 'Thêm' ?> Sự kiện</h2>

<form action="event_action.php" method="post" enctype="multipart/form-data">
    <?php if ($is_edit): ?>
        <input type="hidden" name="id" value="<?= $event_id ?>">
    <?php endif; ?>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="vi-tab" data-toggle="tab" href="#vietnamese" role="tab" aria-controls="vietnamese" aria-selected="true">Tiếng Việt</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="en-tab" data-toggle="tab" href="#english" role="tab" aria-controls="english" aria-selected="false">Tiếng Anh</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="vietnamese" role="tabpanel" aria-labelledby="vi-tab">
            <div class="form-group mt-3">
                <label>Tiêu đề sự kiện (Tiếng Việt)</label>
                <input type="text" name="title_vi" class="form-control" value="<?= htmlspecialchars($title_vi) ?>" required>
            </div>
            <div class="form-group">
                <label>Nội dung chi tiết (Tiếng Việt)</label>
                <textarea name="content_vi" id="content_editor_vi" class="form-control" rows="15"><?= htmlspecialchars($content_vi) ?></textarea>
            </div>
        </div>
        <div class="tab-pane fade" id="english" role="tabpanel" aria-labelledby="en-tab">
            <div class="form-group mt-3">
                <label>Event Title (English)</label>
                <input type="text" name="title_en" class="form-control" value="<?= htmlspecialchars($title_en) ?>">
            </div>
            <div class="form-group">
                <label>Detailed Content (English)</label>
                <textarea name="content_en" id="content_editor_en" class="form-control" rows="15"><?= htmlspecialchars($content_en) ?></textarea>
            </div>
        </div>
    </div>

    <hr>

    <div class="form-group">
        <label>Ngày diễn ra</label>
        <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event_date) ?>">
    </div>

    <div class="form-group">
        <label>Ảnh đại diện</label>
        <input type="file" name="featured_image" class="form-control-file">
        <?php if ($is_edit && $image_url): ?>
            <p class="mt-2">Ảnh hiện tại: <img src="<?= htmlspecialchars($image_url) ?>" height="100"></p>  
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($image_url) ?>">
        <?php endif; ?>
    </div>
    
    <button type="submit" name="save_event" class="btn btn-primary mt-3">Lưu Sự kiện</button>
</form>

<script>
  // Khởi tạo TinyMCE cho cả hai trình soạn thảo
  tinymce.init({ 
    selector: 'textarea#content_editor_vi', 
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
  tinymce.init({ 
    selector: 'textarea#content_editor_en',
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
  });
</script>

<?php include 'footer.php'; ?>