<?php
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/lang.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php'; 
    $loggedIn = isset($_SESSION['user_id']);
    $current_user_id = $_SESSION['user_id'] ?? 0;

    $post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($post_id === 0) die("Bài viết không hợp lệ.");

    // Lấy thông tin bài viết chính
    $sql_post = "SELECT p.*, u.username, u.avatar, u.is_verified FROM posts p JOIN users u ON p.user_id = u.id WHERE p.id = ?";
    $stmt_post = $conn->prepare($sql_post);
    $stmt_post->bind_param("i", $post_id);
    $stmt_post->execute();
    $post = $stmt_post->get_result()->fetch_assoc();
    $stmt_post->close();
    if (!$post) die("Không tìm thấy bài viết.");

    // Lấy tất cả bình luận và sắp xếp vào mảng
    $sql_comments_all = "SELECT c.*, u.username, u.avatar, u.is_verified FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC";
    $stmt_comments_all = $conn->prepare($sql_comments_all);
    $stmt_comments_all->bind_param("i", $post_id);
    $stmt_comments_all->execute();
    $result_comments_all = $stmt_comments_all->get_result();
    $total_comments = $result_comments_all->num_rows;
    $comments_by_parent = [];
    while($comment_row = $result_comments_all->fetch_assoc()) {
        $comments_by_parent[$comment_row['parent_id']][] = $comment_row;
    }
    $stmt_comments_all->close();

    // Hàm đệ quy để hiển thị bình luận
    function display_comments($parent_id, $comments_by_parent, $post_id, $loggedIn, $conn) {
        if (!isset($comments_by_parent[$parent_id])) return;
        
        foreach ($comments_by_parent[$parent_id] as $comment) {
            $comment_avatar = !empty($comment['avatar']) ? htmlspecialchars($comment['avatar']) : '/galaxy/images-icon/default_avatar.png';
            
            echo '<div class="comment-container" id="comment-' . $comment['id'] . '">';
                echo '<div class="comment">';
                    echo '<img src="'.$comment_avatar.'" class="avatar-sm">';
                    echo '<div class="comment-body">';
                        echo '<p class="mb-0"><strong class="comment-author">'.htmlspecialchars($comment['username']);
                            if ($comment['is_verified']) {
                          echo ' <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>';
                              }
                           echo '</strong></p>';
                        if (!empty($comment['content'])) echo '<p class="mb-0 small text-light">'.nl2br(htmlspecialchars($comment['content'])).'</p>';
                        
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
                        
                        if ($loggedIn) {
                            echo "<a href='#' class='reply-btn small' data-comment-id='" . $comment['id'] . "'>" . htmlspecialchars(t('congdong-14')) . "</a>";
                        }
                        
                        // SỬA LỖI DỊCH THUẬT CHO NÚT XEM/ẨN TRẢ LỜI (BƯỚC 1 - PHP)
                        $has_replies = isset($comments_by_parent[$comment['id']]);
                        if ($has_replies) {
                            $reply_count = count($comments_by_parent[$comment['id']]);
                            // Chuẩn bị sẵn các chuỗi văn bản đã dịch
                            $view_text = htmlspecialchars(t('congdong-16')) . " " . $reply_count . " " . htmlspecialchars(t('congdong-17')); // "Xem X câu trả lời"
                            $hide_text = htmlspecialchars(t('congdong-18')); // "Ẩn câu trả lời"
                            
                            // Gán các chuỗi đã dịch vào thuộc tính data-* để JavaScript có thể đọc
                            echo "<a href='#' class='view-replies-btn small ms-2' 
                                   data-target-replies='replies-for-{$comment['id']}' 
                                   data-view-text=\"{$view_text}\" 
                                   data-hide-text=\"{$hide_text}\">";
                            echo "    <i class='fas fa-arrow-turn-down'></i> {$view_text}"; // Hiển thị ban đầu
                            echo "</a>";
                        }
                    echo '</div>';
                echo '</div>';

                // Form trả lời
                if ($loggedIn) {
                    echo '<div class="reply-form" id="reply-form-'.$comment['id'].'" style="display:none;">';
                    echo '  <form action="submit_comment.php" method="POST" enctype="multipart/form-data" class="comment-submission-form">';
                    echo '      <input type="hidden" name="post_id" value="'.$post_id.'">';
                    echo '      <input type="hidden" name="parent_id" value="'.$comment['id'].'">';
                    echo '      <div class="comment-input-area">';
                    echo '          <textarea name="content" rows="1" placeholder="' . htmlspecialchars(t('congdong-15')) . '" class="form-control"></textarea>';
                    echo '          <label class="file-upload-label" title="Thêm media"><i class="fas fa-paperclip"></i></label>';
                    echo '          <input type="file" name="media[]" multiple accept="image/*,video/*" style="display:none;" class="comment-media-input">';
                    echo '          <button type="submit" class="btn-send-comment" title="Gửi"><i class="fas fa-paper-plane"></i></button>';
                    echo '      </div>';
                    echo '      <div class="preview-container mt-2"></div>';
                    echo '  </form>';
                    echo '</div>';
                }

                echo "<div class='replies nested-replies' id='replies-for-{$comment['id']}'>";
                    display_comments($comment['id'], $comments_by_parent, $post_id, $loggedIn, $conn);
                echo '</div>';
            echo '</div>';
        }
    }
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <title><?= htmlspecialchars(t('post-detail-title')) ?> - GALAXY</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/galaxy/css/post_details.css">
</head>
<body>
    
     <header id="head"> <div class="logo-container">
    <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
</div>
       <div id="menuhead">
        <nav>
           <button id="menu-toggle" aria-label="Mở menu">☰</button>
            <ul id="main-menu">
                <li><a href="/galaxy/trangchu.php" ><img src="/galaxy/images-icon/home.png" alt=""><?= t('1') ?></a></li>
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
                        <a class="item" href="/galaxy/sukien.php"><img src="/galaxy/images-icon/sukien.png" alt=""><?= t('3,1') ?> </a>
                        <a class="item" href="/galaxy/tintuc.php"><img src="/galaxy/images-icon/news.png" alt=""><?= t('3,2') ?> </a>
                    </div>
                </li>
                <li><a href="/galaxy/congdong.php" class="active"><img src="/galaxy/images-icon/group (1).png" alt=""><?= t('4') ?></a></li>
                <li>
                    <a href="<?php echo $loggedIn ? '/galaxy/taikhoan.php' : '/galaxy/TAIKHOAN/login-register.html'; ?>">
                        <img src="/galaxy/images-icon/dangnhap.png" alt=""><?= t('5') ?>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#"><img src="/galaxy/images-icon/more.png" alt=""><?= t('6') ?></a>
                    <div class="dropdown-content" style="left: -170%">
                        <a class="item" href="/galaxy/vechungtoi.php"><img src="/galaxy/images-icon/group.png" alt=""><?= t('6,1') ?></a>
                        <a class="language-switcher-container">
                            <input type="checkbox" id="lang-toggle" class="lang-toggle-checkbox"
                                <?php if(isset($current_lang) && $current_lang == 'en') echo 'checked'; ?>>
                            <label for="lang-toggle" class="lang-toggle-label">
                                <span class="lang-toggle-inner"></span>
                                <span class="lang-toggle-switch"></span>
                            </label>
                        </a>
                    </div>
                </li> 
            </ul>
        </nav>
       </div>
</header>

    <div class="main-body">
        <main class="container">
            <a href="congdong.php" class="btn btn-outline-secondary mb-4"><i class="fas fa-arrow-left"></i> <?= t('congdong-12') ?> </a>
            
            <div class="post">
                <div class="post-header">
                    <img src="<?php echo !empty($post['avatar']) ? htmlspecialchars($post['avatar']) : '/galaxy/images-icon/default_avatar.png'; ?>" class="avatar">
                    <div class="author-info">
                        <p class="post-author mb-0">
                          <?php echo htmlspecialchars($post['username']); ?>
                          <?php if ($post['is_verified']): ?>
                            <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>
                            <?php endif; ?>
                              </p>
                        <p class="post-time mb-0"><?php echo date("H:i, d/m/Y", strtotime($post['created_at'])); ?></p>
                    </div>
                </div>
                <div class="post-content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                <?php
                    $stmt_media_post = $conn->prepare("SELECT file_path, media_type FROM post_media WHERE post_id = ?");
                    $stmt_media_post->bind_param("i", $post_id);
                    $stmt_media_post->execute();
                    $result_media_post = $stmt_media_post->get_result();
                    if ($result_media_post->num_rows > 0) {
                        echo '<div class="post-media-grid">';
                        while($media = $result_media_post->fetch_assoc()){
                            if ($media['media_type'] == 'image') echo '<img src="' . htmlspecialchars($media['file_path']) . '">';
                            elseif ($media['media_type'] == 'video') echo '<video src="' . htmlspecialchars($media['file_path']) . '" controls></video>';
                        }
                        echo '</div>';
                    }
                    $stmt_media_post->close();
                ?>
            </div>
            
            <div class="comment-section" 
                 data-text-comment-plural="<?= htmlspecialchars(t('congdong-11')) ?>"
                 data-text-error-generic="<?= htmlspecialchars(t('error-generic')) ?>">
                
                <h3 class="text-white mb-3" id="comment-count"><?php echo $total_comments; ?> <?= htmlspecialchars(t('congdong-11')) ?></h3>
                <?php if ($loggedIn): ?>
                <div class="comment-form">
                    <form action="submit_comment.php" method="POST" enctype="multipart/form-data" class="comment-submission-form">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <div class="comment-input-area">
                            <textarea name="content" rows="1" placeholder="<?= htmlspecialchars(t('congdong-13')) ?>"></textarea>
                            <label class="file-upload-label" title="Thêm media"><i class="fas fa-paperclip"></i></label>
                            <input type="file" name="media[]" multiple class="comment-media-input" accept="image/*,video/*" style="display:none;">
                            <button type="submit" class="btn-send-comment" title="Gửi"><i class="fas fa-paper-plane"></i></button>
                        </div>
                        <div class="preview-container mt-2"></div>
                    </form>
                </div>
                <?php endif; ?>

                <div class="comment-list">
                    <?php
                        if (!empty($comments_by_parent[NULL])) {
                            display_comments(NULL, $comments_by_parent, $post_id, $loggedIn, $conn);
                        }
                    ?>
                    <?php
                        // SỬA LỖI DỊCH THUẬT: Dùng hàm t() cho thông báo chưa có bình luận
                        if ($total_comments === 0) {
                            // Nhớ thêm key 'congdong-no-comment' vào file lang.php của bạn
                            echo '<p id="no-comment-message">' . htmlspecialchars(t('congdong-no-comment')) . '</p>';
                        }
                    ?>
                </div>
            </div>
        </main>
    </div>
    
<script src="/galaxy/js/post_details.js"></script>
</body>
</html>