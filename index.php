<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galaxy Intro</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        #intro {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999;
        }
        #intro video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #skip-intro {
            position: absolute;
            top: 30px;
            right: 30px;
            padding: 12px 24px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(8px);
            color: #fff;
            font-weight: bold;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
    </style>
</head>
<body>
    <div id="intro">
        <video id="intro-video" autoplay muted>
            <source src="/galaxy/videos/eath.mp4" type="video/mp4">
            Trình duyệt không hỗ trợ video.
        </video>
        <button id="skip-intro">Bỏ qua</button>
    </div>

    <script>
        const video = document.getElementById('intro-video');
        const skipButton = document.getElementById('skip-intro');

        video.onended = () => {
            window.Location.href = '/galaxy/pages/trangchu.php';
        }

        skipButton.onclick = () => {
            window.location.href = '/galaxy/pages/trangchu.php';
        };
    </script>
</body>
</html>