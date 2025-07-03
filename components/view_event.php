<?php
session_start();
// --- BẮT ĐẦU SỬA ĐỔI ---

// 1. Thêm 2 dòng này để tích hợp hệ thống dịch và kết nối DB
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/lang.php';
require_once 'db.php';

$event_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($event_id === 0) die("Sự kiện không hợp lệ.");

// 2. Xác định các cột ngôn ngữ sẽ lấy từ database
$title_col = ($current_lang == 'en') ? 'title_en' : 'title_vi';
$content_col = ($current_lang == 'en') ? 'content_en' : 'content_vi';

// 3. Sửa câu SQL để lấy đúng cột ngôn ngữ và dùng "AS"
$stmt = $conn->prepare("SELECT {$title_col} AS title, {$content_col} AS content, image_url, event_date FROM events WHERE id = ?");
$stmt->bind_param("i", $event_id);

// --- KẾT THÚC SỬA ĐỔI ---

$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Không tìm thấy sự kiện.");
$event = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="<?= $current_lang; // Cập nhật lang cho thẻ html ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['title']) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #0a0f1f; color: #e0e0e0; font-family: 'Lato', sans-serif;  cursor:  url('/galaxy/cursor.cur'),  auto !important;}
        .article-header { border-bottom: 1px solid #4a5568; }
        .article-title { font-family: 'Merriweather', serif; font-weight: 700; color: #fff; }
        .featured-image { width: 100%; max-height: 500px; object-fit: cover; margin-bottom: 30px; border-radius: 0.5rem; }
        .article-content { font-size: 1.1rem; line-height: 1.9; color: #cbd5e1; }
        .article-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 25px 0; }
        .article-content h2, .article-content h3 { font-family: 'Merriweather', serif; color: #fbbf24; margin-top: 2rem; }
        .back-link { color: #f59e0b; text-decoration: none; font-weight: bold; }
        .back-link:hover { color: #fef3c7; }
        .event-date-badge { font-size: 1.1rem; }
    </style>
</head>
<body>
    <?php // Include header chung ?>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="mb-4">
                    <a href="sukien.php" class="back-link">&laquo; <?= t('sukien-7') ?></a>
                </div>
                
                <article>
                    <header class="article-header mb-4 pb-3">
                        <h1 class="article-title display-4"><?= htmlspecialchars($event['title']) ?></h1>
                        <p class="badge badge-warning p-2 event-date-badge">
                           <?= t('sukien-8') ?>: <?= date("d/m/Y", strtotime($event['event_date'])) ?>
                        </p>
                    </header>
                    
                    <?php if (!empty($event['image_url'])): ?>
                        <img src="<?= htmlspecialchars($event['image_url']) ?>" alt="<?= htmlspecialchars($event['title']) ?>" class="featured-image">
                    <?php endif; ?>
                    
                    <div class="article-content mt-4">
                        <?php echo $event['content']; // In ra nội dung HTML từ trình soạn thảo ?>
                    </div>
                </article>
            </div>
        </div>
    </main>
    <?php // Include footer chung ?>
</body>
</html>