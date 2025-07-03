<?php
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/lang.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php'; 
    $loggedIn = isset($_SESSION['user_id']);

    // 1. Kiểm tra đăng nhập
    if (!$loggedIn) {
        header("Location: /galaxy/TAIKHOAN/login.html");
        exit();
    }

    // 2. Lấy thông tin bài viết và media
    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($post_id === 0) { die("ID bài viết không hợp lệ."); }

    // Lấy thông tin post
    $stmt_post = $conn->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt_post->bind_param("i", $post_id);
    $stmt_post->execute();
    $result_post = $stmt_post->get_result();
    if ($result_post->num_rows === 0) { die("Không tìm thấy bài viết."); }
    $post = $result_post->fetch_assoc();
    $stmt_post->close();

    // 3. Kiểm tra quyền sở hữu bài viết
    if ($_SESSION['user_id'] != $post['user_id']) {
        die("Bạn không có quyền chỉnh sửa bài viết này.");
    }

    // Lấy media hiện có của post
    $stmt_media = $conn->prepare("SELECT id, file_path, media_type FROM post_media WHERE post_id = ?");
    $stmt_media->bind_param("i", $post_id);
    $stmt_media->execute();
    $current_media = $stmt_media->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_media->close();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <title>Chỉnh sửa bài viết</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0a0a0a; color: #ccc;  cursor:  url('/galaxy/cursor.cur'),  auto !important;}
        .main-body { max-width: 800px; margin: 120px auto 50px auto; padding: 0 20px; }
        .edit-form-container { background: #222; padding: 25px; border-radius: 12px; }
        .current-media-container { display: flex; flex-wrap: wrap; gap: 15px; }
        .media-item { position: relative; }
        .media-item img, .media-item video { width: 150px; height: 150px; object-fit: cover; border-radius: 8px; }
        .delete-checkbox { position: absolute; top: 5px; right: 5px; }
    </style>
</head>
<body>
     <header id="head"> <div class="logo-container">
    <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
</div>
       <div id="menuhead">
        
        <nav>
           <button id="menu-toggle" aria-label="Mở menu">☰</button>

        <ul id="main-menu">
    <li><a href="trangchu.php" ><img src="/galaxy/images-icon/home.png" alt=""><?= t('1') ?></a></li>

    <li class="dropdown">
        <a href="#"><img src="/galaxy/images-icon/hemattroi.png" alt=""><?= t('2') ?></a>
        <div class="dropdown-content">
            <a class="item" href="/galaxy/hemattroi/mattroi.php"><img src="/galaxy/images-icon/sun.png" alt=""><?= t('2,1') ?></a>
            <a class="item" href="/galaxy/hemattroi/saothuy.php"><img src="/galaxy/images-icon/mercury.png" alt=""><?= t('2,2') ?></a>
            <a class="item" href="/galaxy/hemattroi/saokim.php"><img src="/galaxy/images-icon/venus.png" alt=""><?= t('2,3') ?></a>
            <a class="item" href="/galaxy/hemattroi/traidat.php"><img src="/galaxy/images-icon/earth.png" alt=""><?= t('2,4') ?></a>
            <a class="item" href="/galaxy/hemattroi/mattrang.php"><img src="/galaxy/images-icon/full-moon.png" alt=""><?= t('2,5') ?> </a>
            <a class="item" href="/galaxy/hemattroi/saohoa.php"><img src="/galaxy/images-icon/mars.png" alt=""><?= t('2,6') ?></a>
            <a class="item" href="/galaxy/hemattroi/saomoc.php"><img src="/galaxy/images-icon/jupiter.png" alt=""><?= t('2,7') ?></a>
            <a class="item" href="/galaxy/hemattroi/saotho.php"><img src="/galaxy/images-icon/saturn.png" alt=""><?= t('2,8') ?></a>
            <a class="item" href="/galaxy/hemattroi/saothienvuong.php"><img src="/galaxy/images-icon/uranus.png" alt=""><?= t('2,9') ?></a>
            <a class="item" href="/galaxy/hemattroi/saohaivuong.php"><img src="/galaxy/images-icon/neptune.png" alt=""><?= t('2,10') ?></a>
        </div>
    </li>

    <li class="dropdown">
        <a href="#"><img src="/galaxy/images-icon/black-hole.png" alt=""><?= t('3') ?></a>
        <div class="dropdown-content">
            <a class="item" href="sukien.php"><img src="/galaxy/images-icon/sukien.png" alt=""><?= t('3,1') ?> </a>
            <a class="item" href="tintuc.php"><img src="/galaxy/images-icon/news.png" alt=""><?= t('3,2') ?> </a>
        </div>
    </li> <li><a href="congdong.php" class="active"><img src="/galaxy/images-icon/group (1).png" alt=""><?= t('4') ?></a></li>

    <li>
        <a href="<?php echo $loggedIn ? 'taikhoan.php' : './TAIKHOAN/login-register.html'; ?>">
            <img src="/galaxy/images-icon/dangnhap.png" alt=""><?= t('5') ?>
        </a>
    </li>

    <li class="dropdown">
        <a href="#"><img src="/galaxy/images-icon/more.png" alt=""><?= t('6') ?></a>
        <div class="dropdown-content" style="left: -170%">
            <a class="item" href="vechungtoi.php"><img src="/galaxy/images-icon/group.png" alt=""><?= t('6,1') ?></a>
            <a class="language-switcher-container">
        <input type="checkbox" id="lang-toggle" class="lang-toggle-checkbox"
               <?php if(isset($current_lang)) echo ($current_lang == 'en') ? 'checked' : ''; ?>
        >
        <label for="lang-toggle" class="lang-toggle-label">
            <span class="lang-toggle-inner"></span>
            <span class="lang-toggle-switch"></span>
        </label>
             </a>
        </div>
    </li> 

</ul>
        </nav></div>
</header>

    <div class="main-body">
        <main class="container">
            <h1 class="mb-4 text-white"><?= t('edit-congdong-1') ?></h1>
            <div class="edit-form-container">
                <form action="update_post.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">

                    <div class="mb-3">
                        <label for="content" class="form-label"><?= t('edit-congdong-2') ?></label>
                        <textarea name="content" class="form-control" rows="5"><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><?= t('edit-congdong-3') ?></label>
                        <?php if(!empty($current_media)): ?>
                            <div class="current-media-container">
                                <?php foreach($current_media as $media): ?>
                                    <div class="media-item">
                                        <?php if($media['media_type'] == 'image'): ?>
                                            <img src="<?= htmlspecialchars($media['file_path']) ?>" alt="Ảnh hiện tại">
                                        <?php else: ?>
                                            <video src="<?= htmlspecialchars($media['file_path']) ?>" controls></video>
                                        <?php endif; ?>
                                        <input type="checkbox" name="delete_media[]" value="<?= $media['id'] ?>" class="form-check-input delete-checkbox" title="Đánh dấu để xóa">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <small class="form-text text-muted">Đánh dấu vào ô vuông trên ảnh/video để xóa.</small>
                        <?php else: ?>
                            <p class="text-muted">Không có media.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_media" class="form-label"><?= t('edit-congdong-4') ?></label>
                        <input type="file" class="form-control" id="new_media" name="new_media[]" multiple accept="image/*,video/*">
                    </div>

                    <button type="submit" class="btn btn-primary"><?= t('edit-congdong-5') ?></button>
                    <a href="congdong.php" class="btn btn-secondary"><?= t('edit-congdong-6') ?></a>
                </form>
            </div>
        </main>
    </div>
    <script src="/galaxy/js/edit_post.js"></script>
</body>
</html>