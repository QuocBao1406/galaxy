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

    $user_id_post = intval($_GET['user_id']);

    // Nếu không phải chính mình thì tăng view
    if ($user_id_post !==  $current_user_id) {
        $stmt_inc_view = $conn->prepare("UPDATE users SET view = view + 1 WHERE id = ?");
        $stmt_inc_view->bind_param("i", $user_id_post);
        $stmt_inc_view->execute();
        $stmt_inc_view->close();
    }

    $isFollowing = false;
    $sql = "SELECT * FROM follows WHERE follower_id = ? AND followed_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $current_user_id, $user_id_post);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) $isFollowing = true;

    // Người theo dõi mình
    $res = $conn->query("SELECT COUNT(*) AS count FROM follows WHERE followed_id = $user_id_post");
    $followers = $res->fetch_assoc()['count'];

    // Mình đang theo dõi
    $res = $conn->query("SELECT COUNT(*) AS count FROM follows WHERE follower_id = $user_id_post");
    $following = $res->fetch_assoc()['count'];

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
     <header id="head"> 
        <div class="logo-container">
            <img src="images-icon/logo3.png" alt="logonhom" class="logo-overlay">
        </div>
        <div id="menuhead">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/galaxy/components/nav.php'; ?>
        </div>
    </header>

    <div class="main-body">
        <main class="container mt-4">

            <div class="row">
                <?php
                $user_id_post = intval($_GET['user_id']);

                $stmt_user_info = $conn->prepare("SELECT username, fullname, email, phone, birthday, avatar, is_verified 
                                                FROM users WHERE id = ?");
                $stmt_user_info->bind_param("i", $user_id_post);
                $stmt_user_info->execute();
                $user_info = $stmt_user_info->get_result()->fetch_assoc();
                $stmt_user_info->close();

                 ?>
                 <!-- Cột trái: Thông tin người dùng -->
                <div class="col-md-4">
                   <div class="card mb-4 shadow-sm sticky-top" style="top: 100px; z-index: 1000;">
                        <div class="card-body text-center">
                            <img src="<?php echo !empty($user_info['avatar']) ? htmlspecialchars($user_info['avatar']) : '/galaxy/images-icon/default_avatar.png'; ?>" 
                                class="rounded-circle mb-3 border" width="120" height="120" alt="Avatar">
                            <h4 class="mb-0">
                                <?php echo htmlspecialchars($user_info['username']); ?>
                                <?php if ($user_info['is_verified']) echo ' <i class="fas fa-check-circle text-primary" title="Đã xác minh"></i>'; ?>
                            </h4>
                            <p class="text-muted mb-2"><?php echo htmlspecialchars($user_info['fullname']); ?></p>
                        </div>
                        <div style="display: flex; justify-content: center; gap: 10px;">
                            <p style="font-size: 14px; color: #4682b4;"><?= $followers ?> người theo dõi</p>
                            <p style="font-size: 14px; color: #2e8b57;">Đang theo dõi <?= $following ?> người</p>
                        </div>
                        <ul class="list-group list-group-flush px-3 pb-3">
                            <li class="list-group-item border-0"><i class="fas fa-envelope me-2 text-primary"></i><strong>Email:</strong> <?php echo htmlspecialchars($user_info['email']); ?></li>
                            <li class="list-group-item border-0"><i class="fas fa-phone me-2 text-success"></i><strong>Điện thoại:</strong> <?php echo htmlspecialchars($user_info['phone']); ?></li>
                            <li class="list-group-item border-0"><i class="fas fa-birthday-cake me-2 text-warning"></i><strong>Ngày sinh:</strong> <?php echo date("d/m/Y", strtotime($user_info['birthday'])); ?></li>
                        </ul>
                        <?php if($current_user_id !== $user_id_post):?>
                        <div class="d-flex align-items-center justify-content-between" style="width: 100%; gap: 10px;">
                        <form method="post" action="/galaxy/components/follow_action.php" style="width: 50%;">
                            <input type="hidden" name="followed_id" value="<?= $user_id_post ?>">
                            <?php if ($isFollowing): ?>
                                <button type="submit" name="action" value="unfollow" class="btn btn-second" style="width: 100%; background-color:cadetblue">Đang theo dõi</button>
                            <?php else: ?>
                                <button type="submit" name="action" value="follow" class="btn btn-second" style="width: 100%; background-color:cadetblue;">Theo dõi</button>
                            <?php endif; ?>
                        </form>
                        <a href="/galaxy/components/start_conversation.php?to_user_id=<?= $user_id_post ?>" class="btn btn-primary" style="width: 50%;">Nhắn tin</a>
                        </div>
                         <?php endif; ?>
                    </div>
                </div>

                  <!-- Cột phải: Danh sách bài đăng -->
                <div class="col-md-8">
                    <div class="post-feed">
                        <?php
                            $user_id_post = intval($_GET['user_id']); // Chuyển sang số nguyên để tránh lỗi/lỗ hổng
                            $stmt_sql_posts = $conn->prepare("SELECT posts.id, posts.user_id, posts.content, posts.created_at, users.username, users.avatar, users.is_verified
                                    FROM posts JOIN users ON posts.user_id = users.id 
                                    WHERE posts.user_id = ?
                                    ORDER BY posts.created_at DESC");
                            $stmt_sql_posts->bind_param("i", $user_id_post);
                            $stmt_sql_posts->execute();
                            $result_posts =  $stmt_sql_posts->get_result();
                            $stmt_sql_posts->close();
        
                            if ($result_posts && $result_posts->num_rows > 0) {
                                while($post = $result_posts->fetch_assoc()) {
                                    $post_id = $post['id'];
                                    echo '<div class="post">';
                                        echo '<div class="post-header">';
                                        echo '<a href="trangcanhan.php?user_id=' . $post['user_id'] . '" class="link-trangcanhan"><img src="' . 
                                            (!empty($post['avatar']) ? htmlspecialchars($post['avatar']) : '/galaxy/images-icon/default_avatar.png') . 
                                            '" alt="Avatar" class="avatar"></a>';
                                            echo '<div class="author-info">';
                                            echo '<div class="post-author mb-0">';
                                            echo '  <a href="trangcanhan.php?user_id='. $post['user_id'] .'" class="link-trangcanhan">';
                                            echo      htmlspecialchars($post['username']);
                                            if ($post['is_verified']) {
                                                echo ' <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>';
                                            }
                                            echo '  </a>';
                                            echo '</div>';
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
                                            echo "<a href='/galaxy/components/post_details.php?id=" . $post_id . "' class='btn btn-secondary btn-sm w-100'>" . htmlspecialchars(t('congdong-11')) . " (" . $comment_count . ")</a>";
                                            echo '</div>';

                                        echo '</div>';
                                    echo '</div>';
                                }
                            } else {
                                echo '<p class="text-center">Chưa có bài đăng nào.</p>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
<script src="/galaxy/js/congdong.js"></script>

</body>
</html>