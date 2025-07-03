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
    <title>Sao Thổ - Chúa Tể Của những Chiếc Vành Đai</title>
    <script src="/galaxy/js/index.js"></script>
      <link rel="stylesheet" href="/galaxy/css/header.css">
     
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap&subset=vietnamese" rel="stylesheet">

    <style>
        /* CSS CHO NỘI DUNG TRANG SAO THỔ */
        body {
            background-color: #1a1a10; /* Nền vàng kem rất đậm */
            color: #cccccc;
            font-family: 'RobotoRoboto', sans-serif;margin: 0;
             padding: 0;
             width: 100%;
             cursor: url('/galaxy/cursor.cur'), auto !important;
        }

        /* --- CSS CHO MÔ HÌNH 3D NASA (ĐƯA LÊN ĐẦU) --- */
        .nasa-iframe-container {
            margin: 30px auto;
            padding: 25px;
            max-width: 1100px;
            background-color: rgba(30, 30, 15, 0.6); /* Nền vàng kem đậm pha chút trong suốt */
            border-radius: 15px;
            text-align: center;
            border: 1px solid #606030; /* Viền vàng nâu sẫm */
        }

        .nasa-iframe-container h3 {
            font-family: 'Exo 2', sans-serif;
            color: #FFFACD; /* LemonChiffon cho tiêu đề iframe */
            margin-bottom: 20px;
            font-size: 2.2em;
            text-shadow: 0 0 10px rgba(255, 250, 205, 0.6);
        }
        
        .nasa-iframe-container iframe {
            width: 100%; 
            max-width: 900px; 
            min-height: 500px; 
            border-radius: 10px;
            border: 2px solid #FFFACD; /* Viền LemonChiffon */
        }
        /* --- KẾT THÚC CSS MÔ HÌNH 3D NASA --- */

        .planet-content-section { 
            padding: 30px 50px;
            max-width: 1100px;
            margin: 40px auto;
            background: linear-gradient(145deg, rgba(80, 80, 40, 0.9), rgba(100, 100, 60, 0.95)); /* Gradient nền vàng kem/nâu nhạt */
            border-radius: 15px;
            border: 1px solid #F0E68C; /* Viền màu Khaki */
            box-shadow: 0 4px 25px rgba(240, 230, 140, 0.25); /* Bóng đổ màu Khaki */
        }

        .planet-content-section h2, .planet-content-section h3, .planet-content-section h4 {
            font-family: 'Exo 2', sans-serif;
            color: #FFD700; /* Gold cho tiêu đề */
            text-shadow: 0 0 10px rgba(255, 215, 0, 0.7);
        }

        .planet-content-section h2 {
            text-align: center;
            margin-bottom: 35px;
            font-size: 3.2em;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .planet-content-section h3 {
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 2.4em;
            font-weight: 600;
            border-bottom: 2px solid #DAA520; /* Goldenrod cho gạch chân */
            padding-bottom: 10px;
            color: #EEE8AA; /* PaleGoldenrod */
        }
        
        .planet-content-section h4 {
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 1.8em;
            font-weight: 600;
            color: #F5DEB3; /* Wheat */
        }

        .planet-content-section p, .planet-content-section li {
            line-height: 1.8;
            margin-bottom: 18px;
            font-size: 1.1em;
            color: #e0e0e0;
            font-weight: 300;
        }

        .planet-content-section p {
             text-align: justify;
        }

        .planet-content-section strong {
            font-weight: 700;
            color: #FFF8DC; /* Cornsilk - màu sáng hơn cho chữ đậm */
        }
        
        .planet-content-section ul {
            list-style-type: none;
            padding-left: 10px;
        }

        .planet-content-section li::before {
            content: "🪐"; /* Emoji Sao Thổ với vành đai */
            color: #FFD700; 
            display: inline-block;
            width: 1.5em;
            margin-left: -1.5em;
            font-size: 1.2em;
        }
        
        .planet-image-container {
            text-align: center;
            margin: 30px 0;
            background-color: rgba(40,40,10,0.2); /* Nền vàng kem tối cho ảnh */
            padding: 20px;
            border-radius: 10px;
        }

        .planet-image-container img {
            max-width: 70%;
            height: auto;
            border-radius: 10px;
            border: 4px solid #F0E68C; /* Viền Khaki */
            box-shadow: 0 0 20px rgba(240, 230, 140, 0.6);
        }
        
        .info-box {
            background-color: rgba(139, 101, 8, 0.5); /* DarkGoldenrod đậm cho info box */
            border-left: 5px solid #FFD700; /* Gold */
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box p {
            margin-bottom: 10px;
            font-size: 1.05em;
        }
        
        .info-box strong {
            color: #EEE8AA; /* PaleGoldenrod */
        }

        /* --- CSS CHO THƯ VIỆN HÌNH ẢNH Ở CUỐI --- */
        .planet-gallery-section { 
            max-width: 1100px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(70, 70, 30, 0.7); /* Nền vàng kem sẫm cho gallery */
            border-radius: 15px;
            border: 1px solid #BDB76B; /* DarkKhaki */
            box-shadow: 0 4px 20px rgba(189, 183, 107, 0.2);
        }

        .planet-gallery-section h3 {
            font-family: 'Exo 2', sans-serif;
            color: #EEE8AA; /* PaleGoldenrod gallery title */
            text-align: center;
            font-size: 2.4em;
            margin-bottom: 30px;
            text-shadow: 0 0 8px rgba(238, 232, 170, 0.5);
        }

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .image-grid .gallery-item {
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            background-color: rgba(0,0,0,0.3);
            border: 2px solid #A0522D; /* Sienna - một chút tương phản */
        }
        
        .image-grid img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            border-radius: 8px 8px 0 0;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .image-grid img:hover {
            transform: scale(1.05);
            opacity: 0.85;
        }
        
        .image-grid .caption {
            padding: 10px;
            text-align: center;
            font-size: 0.9em;
            color: #FFFACD; /* LemonChiffon */
            background-color: rgba(50, 50, 15, 0.8); /* Nền vàng kem tối cho caption */
            border-radius: 0 0 8px 8px;
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
            .planet-content-section {
                padding: 20px 25px;
            }
            .planet-content-section h2 {
                font-size: 2.5em;
            }
            .planet-content-section h3 {
                font-size: 2em;
            }
            .planet-content-section h4 {
                font-size: 1.6em;
            }
            .planet-image-container img {
                max-width: 90%;
            }
            .image-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            $content_file = $_SERVER['DOCUMENT_ROOT'] . "/galaxy/content/saturn_{$current_lang}.html";

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