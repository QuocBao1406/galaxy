<?php
// File này đã được gọi từ các file quản lý, nên session và kết nối DB đã có sẵn
// Ví dụ: user_management.php đã có dòng require_once 'check_admin.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Galaxy Admin Panel</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.tiny.cloud/1/hqvxm4gjav13aefmubtmp0ubu0a9631gs4536ck1x6tnbg8c/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        body {
            background-color: #f4f7f6;
        }
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-rocket"></i>
            Galaxy Admin
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="user_management.php">Người dùng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="community_management.php">Bài đăng CĐ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="news_management.php">Tin tức</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="event_management.php">Sự kiện</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-shield"></i>
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i>
                            Đăng xuất
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">