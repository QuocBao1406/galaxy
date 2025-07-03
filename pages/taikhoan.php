<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php'; ?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: ./TAIKHOAN/login.html"); 
    exit();
}
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php'; 

$username_session = $_SESSION['username'];
$user = null; 
$defaultAvatar = '/galaxy/assets/images/default-avatar.png'; 

$stmt = $conn->prepare("SELECT id, username, fullname, email, phone, birthday, avatar, is_verified FROM users WHERE username = ?");
if ($stmt) {
    $stmt->bind_param("s", $username_session);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        
        $avatarPathDB = $user['avatar'];
        if (!empty($avatarPathDB)) {
            $physicalPath = $_SERVER['DOCUMENT_ROOT'] . $avatarPathDB;
            if (file_exists($physicalPath)) {
                $user['avatar_display'] = $avatarPathDB;
            } else {
                $user['avatar_display'] = $defaultAvatar;
            }
        } else {
            $user['avatar_display'] = $defaultAvatar;
        }
    } else {
        session_destroy(); header("Location: ./TAIKHOAN/login.html"); exit();
    }
    $stmt->close();
} else {
    die("Lỗi SQL.");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài khoản - <?php echo htmlspecialchars($user['username']); ?> | Galaxy</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/galaxy/css/header.css">
        <link rel="stylesheet" href="/galaxy/css/taikhoan.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;600;700&family=Lexend+Deca:wght@500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- <link rel="stylesheet" href="/galaxy/css/taikhoan.css">  -->

</head>
<style>body{ cursor:  url('/galaxy/cursor.cur'),  auto !important;}</style>
<body>
   <header id="head"> <div class="logo-container">
    <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
</div>
       <div id="menuhead">
        
        <nav>
           <button id="menu-toggle" aria-label="Mở menu">☰</button>

        <ul id="main-menu">
    <li><a href="trangchu.php"><img src="/galaxy/images-icon/home.png" alt=""><?= t('1') ?></a></li>

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
    </li> <li><a href="congdong.php"><img src="/galaxy/images-icon/group (1).png" alt=""><?= t('4') ?></a></li>

    <li>
        <a href="<?php echo $loggedIn ? 'taikhoan.php' : './TAIKHOAN/login-register.html'; ?>"  class="active">
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
    
    <div class="profile-dashboard-wrapper">
        <div class="profile-dashboard">
            <aside class="dashboard-sidebar">
                <div class="sidebar-user-info">
                    <div class="sidebar-avatar-container">
                        <img id="profile-avatar-img" src="<?php echo htmlspecialchars($user['avatar_display']); ?>" alt="Ảnh đại diện">
                        <label for="avatar-upload-input" class="avatar-edit-button" title="Thay đổi ảnh đại diện"><i class="fas fa-camera"></i></label>
                        <input type="file" id="avatar-upload-input" name="avatar" accept="image/*" style="display: none;">
                    </div>
                    <h2 id="profile-fullname-display"><?php echo htmlspecialchars($user['fullname'] ? $user['fullname'] : $user['username']); ?></h2>
                    <p>@<span id="profile-username-value"><?php echo htmlspecialchars($user['username']); ?></span>
    <?php if ($user['is_verified']): ?>
        <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>
    <?php endif; ?>
</p>
                </div>
                <ul class="dashboard-nav">
                    <li><a data-tab="personal-info" class="active"><i class="fas fa-user-circle"></i> <?= t('taikhoan-1') ?></a></li>
                    <li><a data-tab="security"><i class="fas fa-shield-alt"></i> <?= t('taikhoan-7') ?></a></li>
                </ul>
                <button id="profile-logout-btn" class="profile-btn btn-logout"><i class="fas fa-sign-out-alt"></i> <?= t('taikhoan-13') ?></button>
            </aside>
            <div class="dashboard-content-wrapper">
                <main id="personal-info" class="dashboard-content active">
                    <div class="content-header">
                        <h2><?= t('taikhoan-1') ?></h2>
                        <div class="header-actions">
                            <button id="profile-edit-btn" class="action-btn-v2 btn-edit"><i class="fas fa-user-edit"></i> <?= t('taikhoan-2') ?></button>
                            <button id="profile-save-btn" class="action-btn-v2 btn-save" style="display: none;"><i class="fas fa-save"></i> Lưu</button>
                            <button id="profile-cancel-btn" class="action-btn-v2 btn-cancel" style="display: none;"><i class="fas fa-times-circle"></i> Hủy</button>
                        </div>
                    </div>
                    <form id="profile-info-form">
                        <div class="info-grid-v2">
                            <div class="info-item-v2"><span class="info-label-v2"><i class="fas fa-id-badge"></i> ID</span><span class="info-value-v2" data-field="id"><?php echo htmlspecialchars($user['id']); ?></span></div>
                            <div class="info-item-v2"><span class="info-label-v2"><i class="fas fa-user-shield"></i> <?= t('taikhoan-3') ?></span><span class="info-value-v2" data-field="username"><?php echo htmlspecialchars($user['username']); ?></span></div>
                            <div class="info-item-v2"><span class="info-label-v2"><i class="fas fa-user-tag"></i> <?= t('taikhoan-4') ?></span><span class="info-value-v2 editable" data-field="fullname"><?= htmlspecialchars($user['fullname']?:'Chưa cập nhật');?></span><input type="text" class="edit-input-v2" name="fullname" data-field-input="fullname" value="<?= htmlspecialchars($user['fullname']);?>" style="display:none;"></div>
                            <div class="info-item-v2"><span class="info-label-v2"><i class="fas fa-envelope"></i> Email</span><span class="info-value-v2 editable" data-field="email"><?= htmlspecialchars($user['email']);?></span><input type="email" class="edit-input-v2" name="email" data-field-input="email" value="<?= htmlspecialchars($user['email']);?>" style="display:none;"></div>
                            <div class="info-item-v2"><span class="info-label-v2"><i class="fas fa-phone-alt"></i> <?= t('taikhoan-5') ?></span><span class="info-value-v2 editable" data-field="phone"><?= htmlspecialchars($user['phone']?:'Chưa cập nhật');?></span><input type="tel" class="edit-input-v2" name="phone" data-field-input="phone" value="<?= htmlspecialchars($user['phone']);?>" style="display:none;"></div>
                            <div class="info-item-v2"><span class="info-label-v2"><i class="fas fa-calendar-day"></i> <?= t('taikhoan-6') ?></span><span class="info-value-v2 editable" data-field="birthday"><?= htmlspecialchars($user['birthday']?date("d/m/Y",strtotime($user['birthday'])):'Chưa cập nhật');?></span><input type="date" class="edit-input-v2" name="birthday" data-field-input="birthday" value="<?= htmlspecialchars($user['birthday']);?>" style="display:none;"></div>
                        </div>
                    </form>
                </main>
                <main id="security" class="dashboard-content">
                    <div class="content-header"><h2><?= t('taikhoan-8') ?></h2></div>
                    <form id="change-password-form">
                        <div class="info-grid-v2">
                            <div class="info-item-v2"><label class="info-label-v2"><?= t('taikhoan-9') ?></label><input type="password" name="old_password" class="edit-input-v2" required></div>
                            <div class="info-item-v2"><label class="info-label-v2"><?= t('taikhoan-10') ?></label><input type="password" name="new_password" class="edit-input-v2" required minlength="8"></div>
                            <div class="info-item-v2"><label class="info-label-v2"><?= t('taikhoan-11') ?></label><input type="password" name="confirm_password" class="edit-input-v2" required></div>
                        </div><br>
                        <button type="submit" class="action-btn-v2 btn-save"><i class="fas fa-key"></i><?= t('taikhoan-12') ?></button>
                    </form>
                </main>
            </div>
        </div>
    </div>
    <script>
    const logoutMessage = "<?= t('taikhoan-14') ?>";
</script>
   <script src="/galaxy/js/taikhoan.js"></script>
</body>
</html>