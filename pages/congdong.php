<?php
    if (session_status() == PHP_SESSION_NONE) { session_start(); }
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: /galaxy/TAIKHOAN/login-register.html");
        exit();
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';
    $loggedIn = true;
    $current_user_id = $_SESSION['user_id'];

    // Lấy avatar của người dùng đang đăng nhập để hiển thị trong form
    $stmt_user_avatar = $conn->prepare("SELECT avatar FROM users WHERE id = ?");
    $stmt_user_avatar->bind_param("i", $current_user_id);
    $stmt_user_avatar->execute();
    $user_result = $stmt_user_avatar->get_result()->fetch_assoc();
    $currentUserAvatar = !empty($user_result['avatar']) ? htmlspecialchars($user_result['avatar']) : '/galaxy/images-icon/default_avatar.png';
    $stmt_user_avatar->close();
?>
<!DOCTYPE html>
<html lang="<?php echo $current_lang; ?>">
<head>
    <title>Cộng đồng</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/galaxy/css/congdong.css">
    
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
    </li> <li><a href="congdong.php" class="active"><img src="/galaxy/images-icon/group.png" alt=""><?= t('4') ?></a></li>

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
          
            
            <div class="post-form-container">
                <form action="submit_post.php" method="POST" enctype="multipart/form-data">
                    <div class="post-form-body">
                        <img src="<?php echo $currentUserAvatar; ?>" alt="Avatar" class="form-avatar">
                        <textarea name="content" class="form-control stylish-textarea" rows="3" placeholder="<?= t('congdong-1') ?> <?php echo htmlspecialchars($_SESSION['username']); ?>?"></textarea>
                    </div>
                    <div class="form-actions">
                        <div class="d-flex justify-content-between">
                            <label for="media-upload" class="file-upload-btn"><i class="fas fa-photo-video text-success"></i> <?= t('congdong-2') ?></label>
                            <button type="submit" class="btn btn-primary w-50" style=" cursor:  url('/galaxy/cursor.cur'),  auto !important;"><?= t('congdong-3') ?></button>
                        </div>
                        <input type="file" id="media-upload" name="media[]" multiple accept="image/*,video/*" style="display: none;">
                        <div id="preview-container" class="mt-3"></div>
                    </div>
                </form>
            </div>
            
            <div class="post-feed">
                <?php
                   $sql_posts = "SELECT posts.id, posts.user_id, posts.content, posts.created_at, users.username, users.avatar, users.is_verified
                              FROM posts JOIN users ON posts.user_id = users.id 
                              ORDER BY posts.created_at DESC";
                    $result_posts = $conn->query($sql_posts);

                    if ($result_posts && $result_posts->num_rows > 0) {
                        while($post = $result_posts->fetch_assoc()) {
                            $post_id = $post['id'];
                            echo '<div class="post">';
                                echo '<div class="post-header">';
                                    echo '<img src="'.(!empty($post['avatar']) ? htmlspecialchars($post['avatar']) : '/galaxy/images-icon/default_avatar.png').'" alt="Avatar" class="avatar">';
                                    echo '<div class="author-info">';
                                        echo '<p class="post-author mb-0">'.htmlspecialchars($post['username']);
                                            if ($post['is_verified']) {
                                           echo ' <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>';
                                                       }
                                               echo '</p>';
                                        echo '<p class="post-time mb-0">'.date("H:i, d/m/Y", strtotime($post['created_at'])).'</p>';
                                    echo '</div>';
                                    if ($current_user_id == $post['user_id']) {
                                        echo '<div class="post-options-menu">';
                                            echo '<a href="#" class="options-btn"><i class="fas fa-ellipsis-h"></i></a>';
                                            echo '<div class="options-dropdown">';
                                                echo "<a href='edit_post.php?id=" . $post['id'] . "'>" . htmlspecialchars(t('congdong-4')) . "</a>";
                                                echo "<a href='delete_post.php?id=" . $post['id'] . "' onclick=\"return confirm('" . addslashes(t('congdong-6')) . "');\">" . htmlspecialchars(t('congdong-5')) . "</a>";
                                            echo '</div>';
                                        echo '</div>';
                                    }
                                echo '</div>';
                                
                                if (!empty($post['content'])) { echo '<div class="post-content">' . nl2br(htmlspecialchars($post['content'])) . '</div>'; }
                                
                                $stmt_media = $conn->prepare("SELECT file_path, media_type FROM post_media WHERE post_id = ?");
                                $stmt_media->bind_param("i", $post_id);
                                $stmt_media->execute();
                                $result_media = $stmt_media->get_result();
                                if ($result_media->num_rows > 0) {
                                    echo '<div class="post-media-grid">';
                                    while($media = $result_media->fetch_assoc()){
                                        if ($media['media_type'] == 'image') echo '<img src="' . htmlspecialchars($media['file_path']) . '" alt="Ảnh bài đăng">';
                                        elseif ($media['media_type'] == 'video') echo '<video src="' . htmlspecialchars($media['file_path']) . '" controls></video>';
                                    }
                                    echo '</div>';
                                }
                                $stmt_media->close();
                                
                                $stmt_reactions = $conn->prepare("SELECT user_id, reaction_type FROM reactions WHERE post_id = ?");
                                $stmt_reactions->bind_param("i", $post_id);
                                $stmt_reactions->execute();
                                $result_reactions = $stmt_reactions->get_result();
                                $reaction_counts = [];
                                $total_reactions = 0;
                                $user_reaction_type = null;
                                while($reaction = $result_reactions->fetch_assoc()){
                                    @$reaction_counts[$reaction['reaction_type']]++;
                                    $total_reactions++;
                                    if ($reaction['user_id'] == $current_user_id) $user_reaction_type = $reaction['reaction_type'];
                                }
                                $stmt_reactions->close();

                                echo '<div class="reaction-summary" id="reactions-count-'.$post_id.'">';
                                if ($total_reactions > 0) {
                                    $icons_str = '';
                                    $reaction_map = ['love' => '❤️', 'like' => '👍', 'haha' => '😂', 'angry' => '😡'];
                                    foreach($reaction_map as $type => $icon) { if (isset($reaction_counts[$type])) $icons_str .= $icon; }
                                    echo trim($icons_str) . ' ' . $total_reactions;
                                }
                                echo '</div>';

                                echo '<div class="post-actions">';
                                    echo '<div class="reaction-bar" id="reaction-bar-'.$post_id.'">';
                                        $reactions_config = ['like' => t('congdong-7'), 'love' =>t('congdong-8'), 'haha' => t('congdong-9'), 'angry' =>t('congdong-10')];
                                        foreach ($reactions_config as $type => $text) {
                                            $active_class = ($user_reaction_type == $type) ? "active-reaction {$type}" : "";
                                            echo '<a href="#" class="reaction-btn '.$active_class.'" data-post-id="'.$post_id.'" data-reaction="'.$type.'">'.$text.'</a>';
                                        }
                                    echo '</div>';

                                    $stmt_comment_count = $conn->prepare("SELECT COUNT(id) as comment_count FROM comments WHERE post_id = ?");
                                    $stmt_comment_count->bind_param("i", $post_id);
                                    $stmt_comment_count->execute();
                                    $comment_count = $stmt_comment_count->get_result()->fetch_assoc()['comment_count'];
                                    $stmt_comment_count->close();
                                    
                                    echo '<div class="text-center mt-2 border-top border-secondary pt-2">';
                                     echo "<a href='post_details.php?id=" . $post_id . "' class='btn btn-secondary btn-sm w-100'>" . htmlspecialchars(t('congdong-11')) . " (" . $comment_count . ")</a>";
                                    echo '</div>';

                                echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="text-center">Chưa có bài đăng nào.</p>';
                    }
                ?>
            </div>
        </main>
    </div>
<script src="/galaxy/js/congdong.js"></script>

</body>
</html>