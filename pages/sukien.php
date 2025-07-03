<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php'; ?>
<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

// --- BẮT ĐẦU SỬA ĐỔI ---

// 1. Xác định cột ngôn ngữ sẽ được lấy từ database
$title_col = ($current_lang == 'en') ? 'title_en' : 'title_vi';

// 2. Sửa câu truy vấn SQL
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Lấy đúng cột tiêu đề theo ngôn ngữ, và dùng "AS title" để phần HTML bên dưới không cần sửa đổi
$sql = "SELECT id, {$title_col} AS title, image_url, event_date FROM events"; 
$params = [];
$types = '';

if (!empty($search_term)) {
    // Tìm kiếm trên cả 2 cột ngôn ngữ
    $sql .= " WHERE title_vi LIKE ? OR title_en LIKE ?";
    $search_like = "%" . $search_term . "%";
    // Thêm tham số 2 lần cho 2 dấu ?
    $params[] = $search_like;
    $params[] = $search_like;
    $types .= 'ss';
}

$sql .= " ORDER BY event_date DESC";
$stmt = $conn->prepare($sql);

if (!empty($search_term)) {
    // Bind 2 tham số đã thêm
    $stmt->bind_param($types, ...$params);
}

// --- KẾT THÚC SỬA ĐỔI ---

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sự Kiện Thiên Văn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/galaxy/css/sukien.css">
</head>
<body>
    <?php // Bạn có thể include header chung của trang public ở đây nếu có ?>
    
     <header id="head"> <div class="logo-container">
    <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
</div>
       <div id="menuhead">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/galaxy/components/nav.php'; ?>
        </div>
</header>

<br>
<br>

    <main class="container my-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8">
                <form action="sukien.php" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg" placeholder="<?= t('sukien-1') ?>" value="<?= htmlspecialchars($search_term) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-lg px-4" type="submit"><?= t('sukien-2') ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if(!empty($search_term)): ?>
            <h3 class="text-white mb-4"><?= t('sukien-3') ?>"<?= htmlspecialchars($search_term) ?>"</h3>
        <?php endif; ?>

        <div class="row">
            <?php
            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $image = htmlspecialchars($row['image_url'] ? $row['image_url'] : 'assets/images/default-event.png');
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card event-card h-100">
                    <img src="<?= $image ?>" class="card-img-top event-card-img" alt="<?= htmlspecialchars($row['title']) ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
                        <p class="card-text small text-warning">
                            <strong><?= t('sukien-4') ?></strong> <?= date("d/m/Y", strtotime($row['event_date'])) ?>
                        </p>
                        <!-- <p class="card-text flex-grow-1"><?= htmlspecialchars(substr($row['content'], 0, 120)) ?>...</p>   -->
                        <a href="view_event.php?id=<?= $row['id'] ?>" class="btn btn-custom mt-auto align-self-start"><?= t('sukien-5') ?></a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <div class="col-12">
                <div class="alert alert-warning text-center"><?= t('sukien-6') ?></div>
            </div>
            <?php endif; $stmt->close(); ?>
        </div>
    </main>
     <script src="/galaxy/js/sukien.js"></script>
    

</body>
</html>