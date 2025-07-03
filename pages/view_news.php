<?php
session_start();
// 1. Tích hợp hệ thống dịch
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/lang.php';
require_once 'db.php';

$news_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($news_id === 0) die("Tin tức không hợp lệ.");

// 2. Xác định các cột ngôn ngữ
$title_col = ($current_lang == 'en') ? 'title_en' : 'title_vi';
$category_col = ($current_lang == 'en') ? 'category_en' : 'category_vi';
$content_col = ($current_lang == 'en') ? 'full_content_en' : 'full_content_vi';

// 3. Sửa câu SQL
$stmt = $conn->prepare("SELECT {$title_col} AS title, {$category_col} AS category, {$content_col} AS full_content, image_url, created_at FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Không tìm thấy tin tức.");
$news = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="<?= $current_lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($news['title']) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
   <style>
        body { background-color: #0a0f1f; color: #e0e0e0; font-family: 'Lato', sans-serif;  cursor:  url('/galaxy/cursor.cur'),  auto !important;}
        .article-header { border-bottom: 1px solid #334155; }
        .article-title { font-family: 'Merriweather', serif; font-weight: 700; color: #fff; }
        .featured-image { width: 100%; max-height: 500px; object-fit: cover; margin-bottom: 30px; border-radius: 0.5rem; }
        .article-content { font-size: 1.1rem; line-height: 1.9; color: #cbd5e1; }
        .article-content img { max-width: 100%; height: auto; border-radius: 0.5rem; margin: 25px 0; }
        .article-content h2, .article-content h3 { font-family: 'Merriweather', serif; color: #7dd3fc; margin-top: 2rem; }
        .back-link { color: #38bdf8; text-decoration: none; font-weight: bold; }
        .back-link:hover { color: #7dd3fc; }
    </style>
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="mb-4">
                    <a href="tintuc.php" class="back-link">&laquo; <?= t('tintuc-6') ?></a>
                </div>

                <article>
                    <header class="article-header mb-4 pb-3">
                        <h1 class="article-title display-4"><?= htmlspecialchars($news['title']) ?></h1>
                        <p class="text-muted mb-0">
                            <?php if(!empty($news['category'])): ?>
                                <span class="badge badge-primary p-2"><?= htmlspecialchars($news['category']) ?></span>
                            <?php endif; ?>
                            <?= t('tintuc-7') ?>: <?= date("d/m/Y", strtotime($news['created_at'])) ?>
                        </p>
                    </header>

                    <?php if (!empty($news['image_url'])): ?>
                        <img src="<?= htmlspecialchars($news['image_url']) ?>" alt="<?= htmlspecialchars($news['title']) ?>" class="featured-image">
                    <?php endif; ?>

                    <div class="article-content mt-4">
                        <?php echo $news['full_content']; ?>
                    </div>
                </article>
            </div>
        </div>
    </main>
</body>
</html>