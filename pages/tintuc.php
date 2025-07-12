<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php'; ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

// 1. Xác định các cột ngôn ngữ
$title_col = ($current_lang == 'en') ? 'title_en' : 'title_vi';
$category_col = ($current_lang == 'en') ? 'category_en' : 'category_vi';
$excerpt_col = ($current_lang == 'en') ? 'excerpt_en' : 'excerpt_vi';

// lấy các thẻ category
$category_query = "SELECT DISTINCT $category_col AS category FROM news ORDER BY $category_col ASC";
$category_result = $conn->query($category_query);

$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

// 2. Sửa câu SQL
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT id, {$title_col} AS title, {$category_col} AS category, {$excerpt_col} AS excerpt, image_url, created_at FROM news";
$params = [];
$types = '';

if (!empty($search_term)) {
    $sql .= " WHERE title_vi LIKE ? OR excerpt_vi LIKE ? OR title_en LIKE ? OR excerpt_en LIKE ?";
    $search_like = "%" . $search_term . "%";
    $params = [$search_like, $search_like, $search_like, $search_like];
    $types = 'ssss';
}

$sql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if (!empty($search_term)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

// **PHẦN CẬP NHẬT QUAN TRỌNG**
// Lấy tất cả kết quả vào một mảng để dễ dàng xử lý
$all_news = [];
while ($row = $result->fetch_assoc()) {
    $all_news[] = $row;
}

$stmt->close();

// Tin nhiều lượt xem
$sql_views = "SELECT id, {$title_col} AS title, image_url, views FROM news ORDER BY views DESC LIMIT 3";
$result_views = $conn->query($sql_views);
$popular_news = $result_views->fetch_all(MYSQLI_ASSOC);

// tin nổi bật
$sql_featured = "SELECT id, {$title_col} AS title, image_url, created_at FROM news WHERE is_featured = 1 ORDER BY created_at DESC LIMIT 5";
$result_featured = $conn->query($sql_featured);
$featured_news = $result_featured->fetch_all(MYSQLI_ASSOC);

// tin tức mới nhất
$sql_latest = "SELECT id, {$title_col} AS title, {$excerpt_col} AS excerpt, image_url, created_at FROM news ORDER BY created_at DESC LIMIT 3";
$result_latest = $conn->query($sql_latest);
$latest_news = $result_latest->fetch_all(MYSQLI_ASSOC);


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin Tức & Khám Phá Vũ Trụ</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link rel="stylesheet" href="/galaxy/css/tintuc.css">
    <link rel="icon" type="image/ong" href="/galaxy/images-icon/logo.png">
    <link rel="stylesheet" href="/galaxy/css/footer.css">

</head>
<body>
    <header id="head"> 
        <div class="logo-container">
            <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
        </div>
        <div id="menuhead">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/galaxy/components/nav.php'; ?>
        </div>
    </header>
    <br><br>

    <main class="container my-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <form action="tintuc.php" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="<?= t('tintuc-1') ?>" value="<?= htmlspecialchars($search_term) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-lg px-4" type="submit"><?= t('tintuc-2') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(!empty($search_term)): ?>
            <h3 class="text-white mb-4"><?= t('tintuc-3') ?>"<?= htmlspecialchars($search_term) ?>"</h3>
        <?php endif; ?>

        <?php if (empty($search_term) && !empty($featured_news)): ?>
        <div class="featured-news-slider mb-5">
            <h2 class="section-title"><?= t('tintuc-tin-noi-bat') ?></h2>
            <div class="slider-wrapper">
                <div class="swiper-container mySwiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($featured_news as $row): ?>
                        <div class="swiper-slide">
                            <a href="/galaxy/components/view_news.php?id=<?= $row['id'] ?>" class="slider-card-link">
                                <div class="card slider-card text-white">
                                    <img src="<?= htmlspecialchars($row['image_url'] ?: 'assets/images/default-news.jpg') ?>" class="card-img" alt="<?= htmlspecialchars($row['title']) ?>">
                                    <div class="card-img-overlay d-flex flex-column justify-content-end pb-4">
                                        <h4 class="card-title"><?= htmlspecialchars($row['title']) ?></h4>
                                        <p class="card-text small"><?= date("d/m/Y", strtotime($row['created_at'])) ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <?php endif; ?>

        <div class="other-news">
            <div class="row">
                <?php
                    // Nhóm tin tức theo chuyên mục (category)
                    $grouped_news = [];
                    foreach ($all_news as $news) {
                        $cat = $news['category'];
                        if (!isset($grouped_news[$cat])) {
                            $grouped_news[$cat] = [];
                        }
                        $grouped_news[$cat][] = $news;
                    }
                    ?>

                <div class="col-md-8">
                    <?php foreach ($grouped_news as $category => $news_list): ?>
                        <div class="mb-5"> <!-- Khoảng cách giữa các nhóm -->
                            <h3 class="section-title mb-3"><?= htmlspecialchars($category) ?></h3>
                            <div class="row">
                                <?php foreach (array_slice($news_list, 0, 4) as $row): ?>
                                    <div class="col-md-12 col-lg-6 mb-4" data-aos="fade-up" data-aos-duration="800">
                                        <div class="card news-card h-100">
                                            <img src="<?= htmlspecialchars($row['image_url'] ?: 'assets/images/default-news.jpg') ?>" class="card-img-top news-card-img" alt="<?= htmlspecialchars($row['title']) ?>">
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                                                    <p class="card-text small text-muted"><?= htmlspecialchars($row['category']) ?> - <?= date("d/m/Y", strtotime($row['created_at'])) ?></p>
                                                    <p class="card-text flex-grow-1"><?= htmlspecialchars($row['excerpt']) ?></p>
                                                    <a href="/galaxy/components/view_news.php?id=<?= $row['id'] ?>" class="btn btn-custom mt-auto align-self-start"><?= t('tintuc-4') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php 
                                endforeach; ?>
                            </div>
                            <a href="/galaxy/components/category.php?cat=<?= urlencode($category) ?>" class="btn btn-outline-primary btn-sm">
                                <?= t('tintuc-xem-them-theo-chuyen-muc') ?> <?= htmlspecialchars($category) ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                 <!-- Cột phải: Tin đặc biệt -->
                <div class="col-md-4">
                    <!-- Tin nhiều lượt xem -->
                    <h5 class="mt-5 pt-4 mb-3 section-title"><?= t('tintuc-tin-nhieuviews') ?></h5>
                    <?php foreach ($popular_news as $news): ?>
                        <div class="d-flex mb-3 border-bottom pb-3 align-items-center">
                            <img src="<?= htmlspecialchars($news['image_url'] ?: 'assets/images/default-news.jpg') ?>"
                                class="me-3 rounded news-thumbnail"
                                alt="<?= htmlspecialchars($news['title']) ?>">
                            <div>
                                <a href="/galaxy/components/view_news.php?id=<?= $news['id'] ?>" class="news-link fw-bold d-block text-decoration-none" ><?= htmlspecialchars($news['title']) ?></a>
                                <small class="luotxem"><?= $news['views'] ?> lượt xem</small>
                            </div>
                        </div>
                    <?php endforeach; ?>


                    <!-- Tin mới nhất -->
                    <h5 class="mt-4 mb-3 section-title"><?= t('tintuc-tin-moi') ?></h5>
                    <?php foreach ($latest_news as $news): ?>
                    <div class="d-flex mb-3 border-bottom pb-2 align-items-center">
                        <img src="<?= htmlspecialchars($news['image_url'] ?: 'assets/images/default-news.jpg') ?>"
                                class="me-3 rounded news-thumbnail"
                                alt="<?= htmlspecialchars($news['title']) ?>">
                        <div>
                        <a href="/galaxy/components/view_news.php?id=<?= $news['id'] ?>" class="news-link d-block text-decoration-none">
                        <?= htmlspecialchars($news['title']) ?>
                        </a>
                        <small class="ngaydang"><?= date("d/m/Y", strtotime($news['created_at'])) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <!-- Lọc theo ngày -->
                    <h5 class="mt-4 mb-3 section-title"><?= t('tintuc-chonngay') ?></h5>
                    <form method="GET" action="/galaxy/components/news_by_date.php">
                        <input type="date" name="date" class="form-control mb-2" required>
                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Xem tin</button>
                    </form>
                    
                    <!-- Hiện các thẻ category -->
                     <h5 class="mt-4 mb-3 section-title"><?= t('tag-categoty') ?></h5>
                    <div class="mb-4">
                        <?php foreach ($categories as $cat): ?>
                            <a href="/galaxy/components/category.php?cat=<?= urlencode($cat) ?>" class="btn btn-outline-secondary btn-sm mx-1 mb-2">
                                <i class="fas fa-tag"></i> <?= htmlspecialchars($cat) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <main class="sun-content-section">
        <?php
            // 1. Xác định đường dẫn đến tệp nội dung dựa trên ngôn ngữ hiện tại
            $content_file = $_SERVER['DOCUMENT_ROOT'] . "/galaxy/content/footer_{$current_lang}.html";

            // 2. Kiểm tra tệp có tồn tại không và đọc toàn bộ nội dung của nó
            if (file_exists($content_file)) {
                echo file_get_contents($content_file);
            } else {
                echo "Nội dung không có sẵn cho ngôn ngữ này.";
            }
        ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script src="/galaxy/js/tintuc.js"></script>
</body>