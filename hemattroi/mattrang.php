<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php'; ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mặt Trăng - Vệ Tinh Kỳ Thú Của Trái Đất</title>
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <script src="/galaxy/js/index.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap&subset=vietnamese" rel="stylesheet">
    

    <style>
        /* CSS CHO NỘI DUNG TRANG MẶT TRĂNG - PHIÊN BẢN NÂNG CẤP */
        body {
            background-color: #05080f; /* Nền tối hơn cho toàn trang, tạo chiều sâu */
            color: #cccccc; /* Màu chữ xám nhạt hơn cho dễ đọc trên nền tối */
            font-family: 'Roboto', sans-serif; /* Phông chữ chính cho nội dung */
              margin: 0;
             padding: 0;
             width: 100%;
             cursor: url('/galaxy/cursor.cur'), auto !important;
        }

        /* --- CSS CHO MÔ HÌNH 3D NASA (ĐƯA LÊN ĐẦU) --- */
        .nasa-iframe-container {
            margin: 30px auto; /* Điều chỉnh margin cho phù hợp khi ở đầu */
            padding: 25px;
            max-width: 1100px; /* Đồng bộ max-width */
            background-color: rgba(0, 0, 0, 0.6);
            border-radius: 15px;
            text-align: center;
            border: 1px solid #006060;
        }

        .nasa-iframe-container h3 {
            font-family: 'Exo 2', sans-serif;
            color: #00ffff;
            margin-bottom: 20px;
            font-size: 2.2em;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.6);
        }
        
        .nasa-iframe-container iframe {
            width: 100%; /* Chiều rộng 100% của container */
            max-width: 900px; /* Giới hạn chiều rộng tối đa của iframe */
            min-height: 500px; /* Tăng chiều cao tối thiểu */
            border-radius: 10px;
            border: 2px solid #00ffff;
        }
        /* --- KẾT THÚC CSS MÔ HÌNH 3D NASA --- */


        .moon-content-section {
            padding: 30px 50px;
            max-width: 1100px;
            margin: 40px auto;
            background: linear-gradient(145deg, rgba(10, 20, 40, 0.9), rgba(20, 30, 55, 0.95));
            border-radius: 15px;
            border: 1px solid #00a0a0;
            box-shadow: 0 4px 25px rgba(0, 160, 160, 0.25);
        }

        .moon-content-section h2, .moon-content-section h3, .moon-content-section h4 {
            font-family: 'Exo 2', sans-serif;
            color: #00ffff;
            text-shadow: 0 0 10px rgba(0, 255, 255, 0.6);
        }

        .moon-content-section h2 { /* Tiêu đề chính của trang */
            text-align: center;
            margin-bottom: 35px;
            font-size: 3.2em;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .moon-content-section h3 { /* Tiêu đề mục lớn */
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 2.4em;
            font-weight: 600;
            border-bottom: 2px solid #007070;
            padding-bottom: 10px;
            color: #87CEFA;
        }
        
        .moon-content-section h4 { /* Tiêu đề mục nhỏ hơn */
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 1.8em;
            font-weight: 600;
            color: #add8e6;
        }

        .moon-content-section p, .moon-content-section li {
            line-height: 1.8;
            margin-bottom: 18px;
            font-size: 1.1em;
            color: #e0e0e0;
            font-weight: 300;
        }

        .moon-content-section p {
             text-align: justify;
        }

        .moon-content-section strong {
            font-weight: 700; /* Lato bold */
            color: #f0f0f0;
        }
        
        .moon-content-section ul {
            list-style-type: none;
            padding-left: 10px;
        }

        .moon-content-section li::before {
            content: "✦";
            color: #00ffff;
            display: inline-block;
            width: 1.5em;
            margin-left: -1.5em;
            font-size: 1.2em;
        }
        
        .moon-image-container { /* Dành cho ảnh đơn lẻ trong bài viết */
            text-align: center;
            margin: 30px 0;
            background-color: rgba(0,0,0,0.2);
            padding: 20px;
            border-radius: 10px;
        }

        .moon-image-container img {
            max-width: 70%;
            height: auto;
            border-radius: 10px;
            border: 4px solid #00a0a0;
            box-shadow: 0 0 20px rgba(0, 160, 160, 0.6);
        }
        
        .info-box {
            background-color: rgba(0, 50, 80, 0.5);
            border-left: 5px solid #00ffff;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box p {
            margin-bottom: 10px;
            font-size: 1.05em;
        }
        
        .info-box strong {
            color: #00ffff;
        }

        /* --- CSS CHO THƯ VIỆN HÌNH ẢNH Ở CUỐI --- */
        .moon-gallery-section {
            max-width: 1100px;
            margin: 50px auto; /* Tăng margin top để tách biệt */
            padding: 30px;
            background: rgba(10, 20, 40, 0.7); /* Nền nhẹ hơn một chút */
            border-radius: 15px;
            border: 1px solid #008080; /* Viền tối hơn */
            box-shadow: 0 4px 20px rgba(0, 128, 128, 0.2);
        }

        .moon-gallery-section h3 {
            font-family: 'Exo 2', sans-serif;
            color: #87CEFA; /* Màu xanh da trời nhạt */
            text-align: center;
            font-size: 2.4em;
            margin-bottom: 30px;
            text-shadow: 0 0 8px rgba(135, 206, 250, 0.5);
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Lưới responsive */
            gap: 20px; /* Khoảng cách giữa các ảnh */
        }

        .image-grid .gallery-item {
            overflow: hidden; /* Đảm bảo ảnh không tràn ra ngoài khi có hiệu ứng */
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            background-color: rgba(0,0,0,0.3);
            border: 2px solid #007070;
        }
        
        .image-grid img {
            width: 100%;
            height: 220px; /* Chiều cao cố định cho ảnh trong lưới */
            object-fit: cover; /* Đảm bảo ảnh vừa vặn và không bị méo */
            display: block;
            border-radius: 8px 8px 0 0; /* Bo góc phía trên */
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .image-grid img:hover {
            transform: scale(1.05); /* Hiệu ứng phóng to nhẹ khi hover */
            opacity: 0.85;
        }
        
        .image-grid .caption {
            padding: 10px;
            text-align: center;
            font-size: 0.9em;
            color: #b0c4de; /* Màu xanh thép nhạt */
            background-color: rgba(0, 20, 30, 0.8);
            border-radius: 0 0 8px 8px; /* Bo góc phía dưới */
        }
        /* --- KẾT THÚC CSS THƯ VIỆN HÌNH ẢNH --- */


        /* Responsive adjustments */
        @media (max-width: 768px) {
            .nasa-iframe-container {
                margin: 20px auto;
                padding: 15px;
            }
            .nasa-iframe-container iframe {
                min-height: 350px;
            }
            .moon-content-section {
                padding: 20px 25px;
            }
            .moon-content-section h2 {
                font-size: 2.5em;
            }
            .moon-content-section h3 {
                font-size: 2em;
            }
            .moon-content-section h4 {
                font-size: 1.6em;
            }
            .moon-image-container img {
                max-width: 90%;
            }
            .image-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Điều chỉnh cho màn hình nhỏ hơn */
            }
        }
    </style>
</head>

<body>
    <header id="head"> <div class="logo-container">
    <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
</div>
        <div id="menuhead">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/galaxy/components/nav.php'; ?>
        </div>
    </header>

     <main class="sun-content-section">
        <?php
            // 1. Xác định đường dẫn đến tệp nội dung dựa trên ngôn ngữ hiện tại
            $content_file = $_SERVER['DOCUMENT_ROOT'] . "/galaxy/content/moon_{$current_lang}.html";

            // 2. Kiểm tra tệp có tồn tại không và đọc toàn bộ nội dung của nó
            if (file_exists($content_file)) {
                echo file_get_contents($content_file);
            } else {
                echo "Nội dung không có sẵn cho ngôn ngữ này.";
            }
        ?>
    </main>
    
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- <script src="script.js"></script> -->
<script src="/galaxy/js/allhemattroi.js"></script>
    </body>
</html>