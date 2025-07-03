<?php $currentPage = basename($_SERVER['PHP_SELF']); ?>
<?php $hemattroi = [
    'mattroi.php',
    'saothuy.php',
    'saokim.php',
    'traidat.php',
    'mattrang.php',
    'saohoa.php',
    'saomoc.php',
    'saotho.php',
    'saothienvuong.php',
    'saohaivuong.php'
]; ?>
<?php $vutru = [ 
    'sukien.php',
    'tintuc.php'
]; ?>

<nav>
    <button id="menu-toggle" aria-label="Mở menu">☰</button>

    <ul id="main-menu">
        <li>
            <a href="/galaxy/pages/trangchu.php" class="<?= $currentPage == 'trangchu.php' ? 'active' : ''; ?>">
                <img src="/galaxy/images-icon/home.png" alt=""><?= t('1') ?>
            </a>
        </li>

        <li class="dropdown">
            <a href="#" class="<?= in_array($currentPage, $hemattroi) ? 'active' : '' ?>"><img src="/galaxy/images-icon/hemattroi.png" alt=""><?= t('2,1') ?></a>

            <div class="dropdown-content">
                <a class="item" href="/galaxy/hemattroi/mattroi.php"><img src="/galaxy/images-icon/sun.png" alt=""><?= t('2,1') ?></a>
                <a class="item" href="/galaxy/hemattroi/saothuy.php"><img src="/galaxy/images-icon/mercury.png" alt=""><?= t('2,2') ?></a>
                <a class="item" href="/galaxy/hemattroi/saokim.php"><img src="/galaxy/images-icon/venus.png" alt=""><?= t('2,3') ?></a>
                <a class="item" href="/galaxy/hemattroi/traidat.php"><img src="/galaxy/images-icon/earth.png" alt=""><?= t('2,4') ?></a>
                <a class="item" href="/galaxy/hemattroi/mattrang.php"><img src="/galaxy/images-icon/full-moon.png" alt=""><?= t('2,5') ?></a>
                <a class="item" href="/galaxy/hemattroi/saohoa.php"><img src="/galaxy/images-icon/mars.png" alt=""><?= t('2,6') ?></a>
                <a class="item" href="/galaxy/hemattroi/saomoc.php"><img src="/galaxy/images-icon/jupiter.png" alt=""><?= t('2,7') ?></a>
                <a class="item" href="/galaxy/hemattroi/saotho.php"><img src="/galaxy/images-icon/saturn.png" alt=""><?= t('2,8') ?></a>
                <a class="item" href="/galaxy/hemattroi/saothienvuong.php"><img src="/galaxy/images-icon/uranus.png" alt=""><?= t('2,9') ?></a>
                <a class="item" href="/galaxy/hemattroi/saohaivuong.php"><img src="/galaxy/images-icon/neptune.png" alt=""><?= t('2,10') ?>
            </div>
        </li>

        <li class="dropdown">
            <a href="#" class="<?= in_array($currentPage, $vutru) ? 'active' : ''; ?>"><img src="/galaxy/images-icon/black-hole.png" alt=""><?= t('3') ?></a>

            <div class="dropdown-content">
                <a class="item" href="/galaxy/pages/sukien.php"><img src="/galaxy/images-icon/sukien.png" alt=""><?= t('3,1') ?></a>
                <a class="item" href="/galaxy/pages/tintuc.php"><img src="/galaxy/images-icon/news.png" alt=""><?= t('3,2') ?></a>
            </div>
        </li>

        <li>
            <a href="/galaxy/pages/congdong.php" class="<?= $currentPage == 'congdong.php' ? 'active' : ''; ?>"><img src="/galaxy/images-icon/group.png" alt=""><?= t('4') ?></a>
        </li>

        <li>
            <a href="<?= $loggedIn ? 'taikhoan.php' : './TAIKHOAN/login-register.html'; ?>" class="<?= $currentPage == 'taikhoan.php' ? 'active' : ''; ?>">
                <img src="/galaxy/images-icon/dangnhap.png" alt=""><?= t('5') ?>
            </a>
        </li>

        <li class="dropdown">
            <a href="#"><img src="/galaxy/images-icon/more.png" alt=""><?= t('6') ?></a>

            <div class="dropdown-content" style="left: -170%">
                <a class="item" href="/galaxy/pages/vechungtoi.php"><img src="/galaxy/images-icon/group.png" alt=""><?= t('6,1') ?></a>
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

    </ul>
</nav>