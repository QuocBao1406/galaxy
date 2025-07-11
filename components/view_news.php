<?php
   if (session_status() == PHP_SESSION_NONE) { session_start(); }
// 1. Tích hợp hệ thống dịch
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

$news_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($news_id === 0) die("Tin tức không hợp lệ.");

// Tăng lượt xem
$stmt = $conn->prepare("UPDATE news SET views = views + 1 WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();

// 2. Xác định các cột ngôn ngữ
$title_col = ($current_lang == 'en') ? 'title_en' : 'title_vi';
$category_col = ($current_lang == 'en') ? 'category_en' : 'category_vi';
$content_col = ($current_lang == 'en') ? 'full_content_en' : 'full_content_vi';
$excerpt_col = ($current_lang == 'en') ? 'excerpt_en' : 'excerpt_vi';

// 3. Sửa câu SQL
$stmt = $conn->prepare("SELECT {$title_col} AS title, {$category_col} AS category, {$content_col} AS full_content, image_url, created_at FROM news WHERE id = ?");
$stmt->bind_param("i", $news_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) die("Không tìm thấy tin tức.");
$news = $result->fetch_assoc();

// đưa bình luận vào db
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /galaxy/TAIKHOAN/login-register.html");
        exit();
    }

    $comment_text = trim($_POST['comment']);
    if (!empty($comment_text)) {
        $stmt = $conn->prepare("INSERT INTO comments_new (news_id, user_id, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $news_id, $_SESSION['user_id'], $comment_text);
        $stmt->execute();
        // Sau khi gửi, reload để không gửi lại khi F5
        header("Location: view_news.php?id=$news_id");
        exit();
    }
}
    // Tin nhiều lượt xem
    $sql_views = "SELECT id, {$title_col} AS title, image_url, views FROM news ORDER BY views DESC LIMIT 3";
    $result_views = $conn->query($sql_views);
    $popular_news = $result_views->fetch_all(MYSQLI_ASSOC);

    // tin tức mới nhất
    $sql_latest = "SELECT id, {$title_col} AS title, {$excerpt_col} AS excerpt, image_url, created_at FROM news ORDER BY created_at DESC LIMIT 3";
    $result_latest = $conn->query($sql_latest);
    $latest_news = $result_latest->fetch_all(MYSQLI_ASSOC);

    // lấy các thẻ category
    $category_query = "SELECT DISTINCT $category_col AS category FROM news ORDER BY $category_col ASC";
    $category_result = $conn->query($category_query);

    $categories = [];
    while ($row = $category_result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
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
         /* Chỉnh ảnh trong tin tức mới nhất và nhiều lượt xem */
        .news-thumbnail {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border: 1px solid #ddd;
        box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
        }

        .news-thumbnail:hover {
            transform: scale(1.05);
        }
              /* chỉnh màu và hover cho thẻ các thẻ tin tức đặc biệt */
        .news-link {
            color: #c4b8b8; /* Màu chữ ban đầu */
            transition: color 0.3s, text-shadow 0.3s;
        }

        .news-link:hover {
            color: #46a3e0; /* Màu khi hover (đỏ đẹp) */
            text-shadow: 0 0 2px rgba(0, 0, 0, 0.1);
        }

        .luotxem .ngaydang {
            color:#ddd;
        }

    </style>
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
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

                    <hr class="my-5">
                <section class="comments-section">
                    <h3><?= t('Bình luận') ?></h3>

                    <!-- Danh sách bình luận -->
                    <?php
                     $stmt = $conn->prepare("SELECT c.comment, c.created_at, u.username, u.avatar 
                        FROM comments_new c 
                        JOIN users u ON c.user_id = u.id 
                        WHERE c.news_id = ? 
                        ORDER BY c.created_at DESC");

                    $stmt->bind_param("i", $news_id);
                    $stmt->execute();
                    $comments_result = $stmt->get_result();
                    while ($comment = $comments_result->fetch_assoc()):
                    ?>
                       <div class="media mb-3 p-3 bg-dark rounded">
                            <img src="<?= htmlspecialchars($comment['avatar'] ?: 'assets/images/default-avatar.jpg') ?>" 
                                class="mr-3 rounded-circle" 
                                alt="<?= htmlspecialchars($comment['username']) ?>" 
                                width="50" height="50" style="object-fit: cover;">
                            
                            <div class="media-body">
                                <h6 class="mt-0 mb-1 text-white"><?= htmlspecialchars($comment['username']) ?></h6>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></small>
                                <p class="mt-2"><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
                            </div>
                        </div>

                    <?php endwhile; ?>

                    <!-- Form bình luận -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form action="" method="POST" class="mt-4">
                            <div class="form-group">
                                <label for="comment"><?= t('Nhập bình luận của bạn') ?>:</label>
                                <textarea name="comment" id="comment" rows="4" class="form-control" required></textarea>
                            </div>
                            <button type="submit" name="submit_comment" class="btn btn-primary"><?= t('Gửi bình luận') ?></button>
                        </form>
                    <?php else: ?>
                        <p><a href="/galaxy/TAIKHOAN/login-register.html" class="text-info"><?= t('Đăng nhập để bình luận') ?></a></p>
                    <?php endif; ?>
                </section>

                </article>
            </div>

             <!-- Cột phải: Tin đặc biệt -->
                <div class="col-md-4">
                    <!-- Tin nhiều lượt xem -->
                    <h5 class="mt-5 pt-4 mb-3 section-title"><?= t('tintuc-tin-nhieuviews') ?></h5>
                    <?php foreach ($popular_news as $news): ?>
                        <div class="d-flex mb-3 border-bottom pb-3 align-items-center">
                            <img src="<?= htmlspecialchars($news['image_url'] ?: 'assets/images/default-news.jpg') ?>"
                                class="mr-3 rounded news-thumbnail"
                                alt="<?= htmlspecialchars($news['title']) ?>">
                            <div>
                                <a href="view_news.php?id=<?= $news['id'] ?>" class="news-link fw-bold d-block text-decoration-none" ><?= htmlspecialchars($news['title']) ?></a>
                                <small class="luotxem"><?= $news['views'] ?> lượt xem</small>
                            </div>
                        </div>
                    <?php endforeach; ?>


                    <!-- Tin mới nhất -->
                    <h5 class="mt-4 mb-3 section-title"><?= t('tintuc-tin-moi') ?></h5>
                    <?php foreach ($latest_news as $news): ?>
                    <div class="d-flex mb-3 border-bottom pb-2 align-items-center">
                        <img src="<?= htmlspecialchars($news['image_url'] ?: 'assets/images/default-news.jpg') ?>"
                                class="mr-3 rounded news-thumbnail"
                                alt="<?= htmlspecialchars($news['title']) ?>">
                        <div>
                        <a href="view_news.php?id=<?= $news['id'] ?>" class="news-link d-block text-decoration-none">
                        <?= htmlspecialchars($news['title']) ?>
                        </a>
                        <small class="ngaydang"><?= date("d/m/Y", strtotime($news['created_at'])) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Lọc theo ngày -->
                    <h5 class="mt-4 mb-3 section-title"><?= t('tintuc-chonngay') ?></h5>
                    <form method="GET" action="news_by_date.php">
                        <input type="date" name="date" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Xem tin</button>
                    </form>
                    
                    <!-- Hiện các thẻ category -->
                     <h5 class="mt-4 mb-3 section-title"><?= t('tag-categoty') ?></h5>
                    <div class="mb-4">
                        <?php foreach ($categories as $cat): ?>
                            <a href="category.php?cat=<?= urlencode($cat) ?>" class="btn btn-outline-secondary btn-sm mx-1 mb-2">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($cat) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
        </div>
    </main>
</body>
</html>