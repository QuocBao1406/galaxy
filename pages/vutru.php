<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/languages/lang.php'; ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vũ Trụ</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link rel="stylesheet" href="/galaxy/css/vutru.css">
    <link rel="icon" type="image/ong" href="/galaxy/images-icon/logo.png">
    <link rel="stylesheet" href="/galaxy/css/footer.css">
</head>
<body>
     <header id="head"> 
        <div class="logo-container">
            <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
        </div>
        <div id="menuhead">
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/galaxy/components/nav.php'; ?>
        </div>
    </header>
<br>
<br>     
     <main>
        <section class="hero-section" id="hero">
            <img src="https://img.tripi.vn/cdn-cgi/image/width=700,height=700/https://gcs.tripi.vn/public-tripi/tripi-feed/img/474091KwB/hinh-nen-vu-tru-cute-full-hd-cho-may-tinh_084106188.jpg" alt="Vũ trụ" class="hero-bg">
            <div class="hero-overlay"></div>
            <div class="hero-content container text-center">
                <h1 class="animate-on-scroll display-3">Khám phá vũ trụ</h1> <p class="subtitle animate-on-scroll lead mt-3">Nơi những bí ẩn lớn nhất của nhân loại bắt đầu hé lộ.</p>
            </div>
            <a href="#intro-section" class="scroll-down-indicator" aria-label="Scroll down"></a>
        </section>
    <div class="universe-content">
        <section id="intro-section" class="py-5 text-light" style="background-color: #112240;">
            <div class="container">
            <h2 class="section-title">Vũ Trụ Bao La</h2>
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p><strong>Vũ trụ</strong> là toàn bộ không gian và thời gian cùng với tất cả nội dung của nó, bao gồm các hành tinh, ngôi sao, thiên hà, và tất cả các dạng vật chất và năng lượng khác. Theo thuyết <strong>Vụ Nổ Lớn (Big Bang)</strong>, vũ trụ bắt đầu từ một trạng thái cực kỳ nóng và đặc, sau đó giãn nở và nguội dần trong khoảng <strong>13.8 tỷ năm</strong> qua.</p>
                    <p>Quy mô của vũ trụ là không thể tưởng tượng nổi. Vũ trụ quan sát được – phần vũ trụ mà chúng ta có thể nhìn thấy ánh sáng từ đó – có đường kính ước tính khoảng <strong>93 tỷ năm ánh sáng</strong>. Và nó vẫn đang tiếp tục giãn nở, một khám phá vĩ đại của thế kỷ 20.</p>
                </div>
                <div class="col-md-5 text-center">
                    <img src="https://images.spiderum.com/sp-images/3bc69850ebba11ef8e21b545c1ebbe2a.png" alt="Ảnh vũ trụ" class="img-fluid rounded shadow-lg">
                </div>
            </div>
            </div>
        </section>

        <section id="components-universe" class="py-5 text-light" style="background-color:#0a192f;">
            <div class="container">
            <h2 class="section-title text-center mb-5">Những Thành Phần Cấu Tạo Nên Vũ Trụ</h2>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card card-universe h-100">
                        <img src="https://images.unsplash.com/photo-1504333638930-c8787321eee0?crop=topentropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8YW5kcm9tZWRhJTIwZ2FsYXh5fHwwfHx8fDE2Nzg0NTQxMzA&ixlib=rb-4.0.3&q=80&w=400" class="card-img-top" alt="Thiên hà Tiên Nữ">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Thiên Hà (Galaxies)</h5>
                            <p class="card-text">Những "thành phố sao" khổng lồ chứa hàng tỷ ngôi sao, khí, bụi và vật chất tối, liên kết với nhau bởi lực hấp dẫn.</p>
                            <button class="btn btn-outline-info mt-auto" data-bs-toggle="modal" data-bs-target="#galaxyModal">Tìm hiểu thêm</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-universe h-100">
                        <img src="https://i.pinimg.com/736x/5f/82/fa/5f82faacd6b544ad86d2fc3fe9e8c431.jpg" class="card-img-top" alt="Chu kỳ sống của sao">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Sao (Stars)</h5>
                            <p class="card-text">Những "lò hạt nhân" vũ trụ, tạo ra ánh sáng và nhiệt, nơi các nguyên tố nặng được hình thành từ hydro và heli.</p>
                             <button class="btn btn-outline-info mt-auto" data-bs-toggle="modal" data-bs-target="#starModal">Tìm hiểu thêm</button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card card-universe h-100">
                        <img src="https://media.istockphoto.com/id/1135482189/vi/anh/tinh-v%C3%A2n-rosette-trong-ch%C3%B2m-sao-%C4%91%C6%A1n-s%E1%BA%AFc-h%C3%ACnh-%E1%BA%A3nh-hst.jpg?s=612x612&w=0&k=20&c=G_nyLUwZC-akZ7Qcc1DXsaI8KVLTadUiBxht6yEigMM=" class="card-img-top" alt="Tinh vân Lạp Hộ">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Tinh Vân (Nebulae)</h5>
                            <p class="card-text">Những đám mây khí và bụi khổng lồ, là "vườn ươm" của các ngôi sao mới hoặc tàn dư của những ngôi sao đã chết.</p>
                            <button class="btn btn-outline-info mt-auto" data-bs-toggle="modal" data-bs-target="#nebulaModal">Tìm hiểu thêm</button>
                        </div>
                    </div>
                </div>
                 <div class="col-lg-3 col-md-6">
                    <div class="card card-universe h-100">
                        <img src="https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8ZGFyayUyMG1hdHRlciUyMGNvbmNlcHR8ZW58MHx8fHwxNjc4NDU0MzY5&ixlib=rb-4.0.3&q=80&w=400" class="card-img-top" alt="Khái niệm Vật chất tối">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Vật Chất Tối & Năng Lượng Tối</h5>
                            <p class="card-text">Những thành phần bí ẩn chiếm phần lớn khối lượng-năng lượng của vũ trụ, nhưng bản chất vẫn là một câu đố lớn.</p>
                             <button class="btn btn-outline-info mt-auto" data-bs-toggle="modal" data-bs-target="#darkModal">Tìm hiểu thêm</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section id="cosmology-concepts" class="py-5 text-light" style="background-color: #112240;">
            <div class="container">
            <h2 class="section-title text-center">Những Khái Niệm Nền Tảng</h2>
            <div class="accordion" id="cosmologyAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingBigBang">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBigBang" aria-expanded="false" aria-controls="collapseBigBang">
                            Vụ Nổ Lớn (Big Bang)
                        </button>
                    </h2>
                    <div id="collapseBigBang" class="accordion-collapse collapse" aria-labelledby="headingBigBang" data-bs-parent="#cosmologyAccordion">
                        <div class="accordion-body">
                            <strong>Thuyết Big Bang</strong> không phải là một "vụ nổ" theo nghĩa thông thường, mà là sự giãn nở cực nhanh của không gian từ một điểm kỳ dị ban đầu có mật độ và nhiệt độ vô hạn. Lý thuyết này mô tả vũ trụ đã tiến hóa từ trạng thái cực kỳ nóng và đặc đó, nguội dần và hình thành các hạt hạ nguyên tử, sau đó là các nguyên tử (chủ yếu là Hydro và Heli), và cuối cùng là các cấu trúc lớn như sao và thiên hà. Đây là mô hình được chấp nhận rộng rãi nhất về nguồn gốc và sự phát triển sơ khai của vũ trụ, được hỗ trợ bởi nhiều bằng chứng quan sát như bức xạ nền vi sóng vũ trụ (CMB) và sự phong phú của các nguyên tố nhẹ.
                            <video controls width="100%" class="d-block mx-auto my-3">
                                <source src="https://cdn.pixabay.com/video/2023/11/01/187348-880352410_large.mp4" type="video/mp4">
                                Trình duyệt của bạn không hỗ trợ video.
                            </video>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingExpansion">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExpansion" aria-expanded="false" aria-controls="collapseExpansion">
                            Sự Giãn Nở Của Vũ Trụ
                        </button>
                    </h2>
                    <div id="collapseExpansion" class="accordion-collapse collapse" aria-labelledby="headingExpansion" data-bs-parent="#cosmologyAccordion">
                        <div class="accordion-body">
                            Một trong những khám phá quan trọng nhất của vũ trụ học hiện đại là <strong>vũ trụ không tĩnh mà đang giãn nở</strong>. Điều này có nghĩa là khoảng cách giữa các cụm thiên hà xa xôi đang tăng lên theo thời gian, giống như các điểm trên bề mặt một quả bóng bay đang được thổi phồng. Sự giãn nở này không phải là các thiên hà di chuyển qua không gian, mà là chính không gian giữa chúng đang được mở rộng. Quan sát các siêu tân tinh loại Ia vào cuối những năm 1990 đã cho thấy một điều còn đáng ngạc nhiên hơn: sự giãn nở này đang <strong>tăng tốc</strong>, được cho là do tác động của năng lượng tối.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingHubbleLaw">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHubbleLaw" aria-expanded="false" aria-controls="collapseHubbleLaw">
                            Định Luật Hubble-Lemaître
                        </button>
                    </h2>
                    <div id="collapseHubbleLaw" class="accordion-collapse collapse" aria-labelledby="headingHubbleLaw" data-bs-parent="#cosmologyAccordion">
                        <div class="accordion-body">
                            Định luật Hubble-Lemaître (trước đây thường gọi là Định luật Hubble) là một phát biểu quan trọng trong vũ trụ học, cho thấy mối quan hệ giữa vận tốc lùi ra xa của các thiên hà và khoảng cách của chúng tới người quan sát. Cụ thể, định luật này nói rằng <strong>vận tốc ($v$) mà một thiên hà đang di chuyển ra xa chúng ta tỷ lệ thuận với khoảng cách ($D$) của nó</strong>.
                            Mối quan hệ này được biểu diễn bằng công thức:
                            $$v = H_0 D$$
                            Trong đó, <span class="latex">$H_0$</span> là <strong>hằng số Hubble</strong>, đại diện cho tốc độ giãn nở của vũ trụ tại thời điểm hiện tại. Giá trị của <span class="latex">$H_0$</span> vẫn đang được tinh chỉnh, nhưng thường nằm trong khoảng 67-74 km/s/Mpc (kilômét trên giây trên megaparsec). Định luật này là một trong những bằng chứng quan sát đầu tiên và mạnh mẽ nhất cho sự giãn nở của vũ trụ.
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <section id="universe-exploration" class="py-5 text-light" style="background-color:#0a192f;">
            <div class="container">
            <h2 class="section-title text-center">Hành Trình Khám Phá</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p>Từ những đêm ngước nhìn bầu trời đầy sao của tổ tiên, con người đã không ngừng khao khát tìm hiểu về vũ trụ. Phát minh ra <strong>kính thiên văn</strong> vào thế kỷ 17 bởi Galileo Galilei đã mở ra một kỷ nguyên mới, cho phép chúng ta nhìn xa hơn và rõ hơn vào không gian.</p>
                    <p>Ngày nay, chúng ta có các <strong>đài quan sát mặt đất</strong> với những chiếc gương khổng lồ và các <strong>kính thiên văn không gian</strong> như Hubble, James Webb, Chandra, Spitzer... bay vòng quanh Trái Đất hoặc xa hơn, thoát khỏi sự cản trở của khí quyển, mang đến những hình ảnh và dữ liệu vô giá. Các <strong>sứ mệnh không gian</strong> đã đưa robot và cả con người đến các thiên thể khác, giúp chúng ta hiểu rõ hơn về nguồn gốc, cấu trúc và sự tiến hóa của vũ trụ, cũng như vị trí của chúng ta trong đó.</p>
                </div>
                <div class="col-md-6 text-center">
                    <img src="https://vcdn1-vnexpress.vnecdn.net/2021/12/24/james-web-9698-1640346896.jpg?w=460&h=0&q=100&dpr=2&fit=crop&s=ji_fCZwY9tTUcKi-urCvVA" class="img-fluid rounded shadow-lg">
                </div>
            </div>
            </div>
        </section>

        <section id="universe-gallery" class="py-5 text-light" style="background-color: #112240;">
            <div class="container">
            <h2 class="section-title text-center">Thư Viện Vũ Trụ Kỳ Diệu</h2>
            <div class="row g-3 justify-content-center">
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <img src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8NHx8bmFzYXxlbnwwfHx8fDE2Nzg0NTQ3MzA&ixlib=rb-4.0.3&q=80&w=300" alt="Trái Đất nhìn từ không gian" class="img-fluid gallery-img" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8NHx8bmFzYXxlbnwwfHx8fDE2Nzg0NTQ3MzA&ixlib=rb-4.0.3&q=80&w=1200" data-image-title="Trái Đất nhìn từ không gian">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <img src="https://photo.znews.vn/w960/Uploaded/tmuizn/2022_10_20/5443e9a0_4fc2_11ed_b9fb_a0cd117f4ea2.cf.jpg" alt="Cột Hình Thành (Pillars of Creation)" class="img-fluid gallery-img" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="https://photo.znews.vn/w960/Uploaded/tmuizn/2022_10_20/5443e9a0_4fc2_11ed_b9fb_a0cd117f4ea2.cf.jpg" data-image-title="Cột Hình Thành (Pillars of Creation) - Tinh vân Đại Bàng">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <img src="https://images.unsplash.com/photo-1502134249126-9f3755a50d78?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8bWlsa3klMjB3YXl8ZW58MHx8fHwxNjc4NDU0ODQ0&ixlib=rb-4.0.3&q=80&w=300" alt="Dải Ngân Hà" class="img-fluid gallery-img" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="https://images.unsplash.com/photo-1502134249126-9f3755a50d78?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8bWlsa3klMjB3YXl8ZW58MHx8fHwxNjc4NDU0ODQ0&ixlib=rb-4.0.3&q=80&w=1200" data-image-title="Dải Ngân Hà - Thiên hà của chúng ta">
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <img src="https://images.unsplash.com/photo-1462331940025-496dfbfc7564?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8c3BhY2UlMjBuZWJ1bGF8ZW58MHx8fHwxNjc4NDU0ODk4&ixlib=rb-4.0.3&q=80&w=300" alt="Một tinh vân tuyệt đẹp" class="img-fluid gallery-img" data-bs-toggle="modal" data-bs-target="#imageModal" data-image-src="https://images.unsplash.com/photo-1462331940025-496dfbfc7564?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8c3BhY2UlMjBuZWJ1bGF8ZW58MHx8fHwxNjc4NDU0ODk4&ixlib=rb-4.0.3&q=80&w=1200" data-image-title="Tinh vân vũ trụ">
                </div>
                 </div>
                 </div>
        </section>

         <section id="featured-highlights" class="featured-highlights-section py-5" style="background-color:#0a192f;">
            <div class="container">
                <h2 class="section-title text-center mb-5 animate-on-scroll">Khám phá nổi bật</h2>
                <div class="row">
                    <div class="col-md-4 mb-4 animate-on-scroll">
                        <div class="card h-100 shadow-sm custom-card">
                            <img src="https://img.freepik.com/free-vector/planets-solar-system-infographic_1308-51094.jpg?semt=ais_hybrid&w=740" class="card-img-top" alt="Hệ Mặt Trời">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Hệ Mặt Trời</h5>
                                <p class="card-text">Du hành qua các hành tinh, mặt trăng và các thiên thể khác trong sân nhà vũ trụ của chúng ta.</p>
                                <a href="solar-system.html" class="btn btn-primary mt-auto">Khám phá</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 animate-on-scroll">
                        <div class="card h-100 shadow-sm custom-card">
                            <img src="https://mega.com.vn/media/news/1706_hinh-nen-phi-hanh-gia133.jpg" class="card-img-top" alt="Vũ trụ">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Vũ Trụ Bao La</h5>
                                <p class="card-text">Tìm hiểu về các thiên hà, tinh vân, lỗ đen và những cấu trúc vĩ đại nhất của vũ trụ.</p>
                                <a href="universe.html" class="btn btn-primary mt-auto">Tìm hiểu</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 animate-on-scroll">
                        <div class="card h-100 shadow-sm custom-card">
                            <img src="https://www.nasa.gov/wp-content/uploads/2015/08/jsc2015e032666_blog.jpg" class="card-img-top" alt="Tin tức vũ trụ">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">Tin Tức Vũ Trụ</h5>
                                <p class="card-text">Cập nhật những phát hiện, sự kiện và sứ mệnh không gian mới nhất từ khắp nơi trên thế giới.</p>
                                <a href="news.html" class="btn btn-primary mt-auto">Xem tin tức</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        </div>
    </main>

    <div class="modal fade" id="galaxyModal" tabindex="-1" aria-labelledby="galaxyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="galaxyModalLabel">Thiên Hà (Galaxies)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 text-center">
                             <img src="https://images.unsplash.com/photo-1504333638930-c8787321eee0?crop=topentropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8YW5kcm9tZWRhJTIwZ2FsYXh5fHwwfHx8fDE2Nzg0NTQxMzA&ixlib=rb-4.0.3&q=80&w=600" class="img-fluid rounded mb-3" alt="Thiên hà Tiên Nữ chi tiết">
                        </div>
                        <div class="col-md-7">
                            <p><strong>Thiên hà</strong> là những hệ thống cực kỳ lớn bao gồm các ngôi sao (từ vài triệu đến hàng nghìn tỷ), tàn dư sao, khí giữa các vì sao, bụi vũ trụ và một thành phần quan trọng nhưng chưa được hiểu rõ gọi là vật chất tối. Tất cả những thành phần này được liên kết với nhau bởi lực hấp dẫn.</p>
                            <h6>Phân loại Thiên hà:</h6>
                            <ul>
                                <li><strong>Thiên hà xoắn ốc (Spiral galaxies):</strong> Có một đĩa phẳng, xoay tròn với các nhánh xoắn ốc nổi bật chứa đầy sao trẻ, nóng và các vùng hình thành sao. Phần trung tâm phình ra (bulge) thường chứa các sao già hơn. Dải Ngân Hà (Milky Way) của chúng ta là một thiên hà xoắn ốc dạng thanh (barred spiral galaxy). Ví dụ điển hình khác: Thiên hà Tiên Nữ (Andromeda Galaxy - M31).</li>
                                <li><strong>Thiên hà elip (Elliptical galaxies):</strong> Có hình dạng từ gần tròn đến rất dẹt (hình elipsoid), chứa chủ yếu các sao già, ít khí và bụi, và ít hoạt động hình thành sao. Chúng được cho là kết quả của sự sáp nhập giữa các thiên hà nhỏ hơn.</li>
                                <li><strong>Thiên hà không đều (Irregular galaxies):</strong> Không có hình dạng đối xứng rõ ràng, thường có vẻ ngoài hỗn loạn. Chúng chứa nhiều khí, bụi và các vùng hình thành sao đang hoạt động. Ví dụ: Đám Mây Magellan Lớn và Nhỏ (vệ tinh của Dải Ngân Hà).</li>
                                <li><strong>Thiên hà dạng thấu kính (Lenticular galaxies):</strong> Là dạng trung gian giữa thiên hà elip và xoắn ốc. Chúng có một đĩa nhưng không có các nhánh xoắn ốc rõ rệt và thường chứa ít sao trẻ hơn so với thiên hà xoắn ốc.</li>
                            </ul>
                            <p>Các thiên hà có kích thước rất đa dạng. Ước tính có khoảng 2 nghìn tỷ (2 x $10^{12}$) thiên hà trong vũ trụ quan sát được. Hầu hết các thiên hà lớn đều có một <strong>lỗ đen siêu khối lượng</strong> ở trung tâm. Lỗ đen ở trung tâm Dải Ngân Hà, được gọi là Sagittarius A*, có khối lượng gấp khoảng 4 triệu lần Mặt Trời.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="starModal" tabindex="-1" aria-labelledby="starModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="starModalLabel">Sao (Stars)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="row">
                        <div class="col-md-5 col-sm-12 text-center">
                             <img src="https://i.pinimg.com/736x/5f/82/fa/5f82faacd6b544ad86d2fc3fe9e8c431.jpg" class="img-fluid rounded mb-3" alt="Chu kỳ sống của sao chi tiết">
                        </div>
                        <div class="col-md-7">
                            <p><strong>Sao</strong> là một thiên thể plasma sáng, hình cầu, tự giữ lấy nhau bởi lực hấp dẫn của chính nó. Ngôi sao gần Trái Đất nhất là Mặt Trời, nguồn năng lượng chính cho sự sống trên hành tinh của chúng ta. Sao tạo ra năng lượng thông qua các <strong>phản ứng tổng hợp hạt nhân</strong> trong lõi của chúng, chủ yếu là biến đổi hydro thành heli.</p>
                            <h6>Chu kỳ sống của một ngôi sao:</h6>
                            <ol>
                                <li><strong>Hình thành:</strong> Sao được sinh ra từ sự sụp đổ hấp dẫn của các đám mây khí và bụi khổng lồ (tinh vân). Khi lõi của đám mây co lại đủ đặc và nóng, phản ứng hạt nhân bắt đầu.</li>
                                <li><strong>Dãy chính (Main Sequence):</strong> Đây là giai đoạn dài nhất và ổn định nhất trong cuộc đời của một ngôi sao (khoảng 90%). Trong giai đoạn này, sao tổng hợp hydro thành heli trong lõi. Kích thước, nhiệt độ và độ sáng của sao phụ thuộc vào khối lượng của nó. Mặt Trời của chúng ta là một sao dãy chính.</li>
                                <li><strong>Giai đoạn cuối (phụ thuộc khối lượng):</strong>
                                    <ul>
                                        <li><strong>Sao khối lượng thấp đến trung bình (như Mặt Trời):</strong> Khi cạn kiệt hydro ở lõi, chúng phình to thành <strong>sao khổng lồ đỏ (Red Giant)</strong>. Sau đó, lớp vỏ ngoài của sao bị thổi bay ra ngoài tạo thành <strong>tinh vân hành tinh (Planetary Nebula)</strong>, để lại phần lõi co lại thành một <strong>sao lùn trắng (White Dwarf)</strong> cực kỳ đặc. Sao lùn trắng nguội dần và cuối cùng (theo lý thuyết) trở thành sao lùn đen.</li>
                                        <li><strong>Sao khối lượng lớn (lớn hơn khoảng 8 lần Mặt Trời):</strong> Chúng trải qua các giai đoạn đốt cháy các nguyên tố nặng hơn sau khi hết hydro, phình to thành <strong>sao siêu khổng lồ đỏ (Red Supergiant)</strong>. Cuộc đời của chúng kết thúc bằng một vụ nổ siêu tân tinh (Supernova) ngoạn mục, giải phóng một lượng năng lượng khổng lồ và phát tán các nguyên tố nặng vào không gian. Tàn dư của vụ nổ có thể là một <strong>sao neutron</strong> (một thiên thể cực kỳ đặc) hoặc, nếu ngôi sao ban đầu rất lớn, một <strong>lỗ đen (Black Hole)</strong>.</li>
                                    </ul>
                                </li>
                            </ol>
                            <p>Màu sắc của một ngôi sao cho biết nhiệt độ bề mặt của nó: sao nóng nhất có màu xanh lam hoặc trắng, sao có nhiệt độ trung bình như Mặt Trời có màu vàng, và sao nguội nhất có màu đỏ.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="nebulaModal" tabindex="-1" aria-labelledby="nebulaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nebulaModalLabel">Tinh Vân (Nebulae)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5 col-sm-12 text-center">
                             <img src="https://media.istockphoto.com/id/1135482189/vi/anh/tinh-v%C3%A2n-rosette-trong-ch%C3%B2m-sao-%C4%91%C6%A1n-s%E1%BA%AFc-h%C3%ACnh-%E1%BA%A3nh-hst.jpg?s=612x612&w=0&k=20&c=G_nyLUwZC-akZ7Qcc1DXsaI8KVLTadUiBxht6yEigMM=" class="img-fluid rounded mb-3" alt="Tinh vân Carina">
                        </div>
                        <div class="col-md-7">
                            <p><strong>Tinh vân</strong> (tiếng Latinh có nghĩa là "đám mây", số nhiều: nebulae) là một đám mây giữa các vì sao gồm bụi, hydro, heli và các loại khí ion hóa khác. Ban đầu, "tinh vân" là một thuật ngữ chung được sử dụng để mô tả bất kỳ thiên thể khuếch tán nào, bao gồm cả các thiên hà bên ngoài Dải Ngân Hà.</p>
                            <h6>Các loại Tinh vân chính:</h6>
                            <ul>
                                <li><strong>Tinh vân phát xạ (Emission Nebulae):</strong> Các đám mây khí bị ion hóa bởi bức xạ tử ngoại từ các ngôi sao trẻ, nóng gần đó, khiến chúng phát sáng với màu sắc đặc trưng (thường là màu đỏ do vạch phát xạ H-alpha của hydro). Chúng thường là các vùng hình thành sao. Ví dụ: Tinh vân Lạp Hộ (Orion Nebula - M42), Tinh vân Carina.</li>
                                <li><strong>Tinh vân phản xạ (Reflection Nebulae):</strong> Các đám mây bụi phản chiếu ánh sáng từ các ngôi sao gần đó. Ánh sáng bị tán xạ bởi các hạt bụi thường có màu xanh lam vì ánh sáng xanh bị tán xạ hiệu quả hơn ánh sáng đỏ. Ví dụ: Tinh vân quanh cụm sao Pleiades.</li>
                                <li><strong>Tinh vân tối (Dark Nebulae):</strong> Các đám mây bụi và khí dày đặc đến mức chúng che khuất ánh sáng từ các ngôi sao hoặc tinh vân phát xạ phía sau. Chúng xuất hiện dưới dạng các vùng tối trên nền trời sao. Ví dụ: Tinh vân Đầu Ngựa (Horsehead Nebula) trong phức hợp Lạp Hộ.</li>
                                <li><strong>Tinh vân hành tinh (Planetary Nebulae):</strong> Là lớp vỏ khí bị đẩy ra từ các ngôi sao già (như sao khổng lồ đỏ) ở giai đoạn cuối của cuộc đời (trước khi trở thành sao lùn trắng). Chúng không liên quan đến hành tinh, tên gọi này xuất phát từ hình dạng giống đĩa hành tinh khi quan sát qua kính thiên văn nhỏ thời xưa. Ví dụ: Tinh vân Chiếc Nhẫn (Ring Nebula - M57).</li>
                                <li><strong>Tàn dư siêu tân tinh (Supernova Remnants - SNR):</strong> Lớp vỏ khí và bụi giãn nở mạnh mẽ do một vụ nổ siêu tân tinh. Chúng chứa các nguyên tố nặng được tạo ra trong vụ nổ và có thể kích hoạt sự hình thành sao mới. Ví dụ: Tinh vân Con Cua (Crab Nebula - M1).</li>
                            </ul>
                            <p>Nhiều tinh vân đóng vai trò là "vườn ươm sao", nơi vật chất sụp đổ để tạo thành các ngôi sao và hệ hành tinh mới. Nghiên cứu tinh vân giúp chúng ta hiểu về chu kỳ vật chất trong vũ trụ.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="darkModal" tabindex="-1" aria-labelledby="darkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="darkModalLabel">Vật Chất Tối & Năng Lượng Tối</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="row">
                        <div class="col-md-5 col-sm-12  text-center">
                             <img src="https://images.unsplash.com/photo-1534796636912-3b95b3ab5986?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwxMjA3fDB8MXxzZWFyY2h8MXx8ZGFyayUyMG1hdHRlciUyMGNvbmNlcHR8ZW58MHx8fHwxNjc4NDU0MzY5&ixlib=rb-4.0.3&q=80&w=500" class="img-fluid rounded mb-3" alt="Khái niệm vũ trụ tối">
                        </div>
                        <div class="col-md-7">
                            <p>Vật chất thông thường mà chúng ta biết (proton, neutron, electron - những thứ tạo nên sao, hành tinh, và chính chúng ta) chỉ chiếm một phần rất nhỏ, khoảng <strong>5%</strong>, tổng khối lượng-năng lượng của vũ trụ. Phần còn lại là hai thành phần bí ẩn và chiếm ưu thế: vật chất tối (khoảng <strong>27%</strong>) và năng lượng tối (khoảng <strong>68%</strong>).</p>

                            <h6>Vật Chất Tối (Dark Matter)</h6>
                            <p><strong>Vật chất tối</strong> là một dạng vật chất giả thuyết không phát ra, hấp thụ hay phản xạ ánh sáng (hoặc bất kỳ bức xạ điện từ nào khác) ở mức độ đáng kể, khiến nó trở nên vô hình đối với các kính thiên văn thông thường. Sự tồn tại và tính chất của nó được suy ra một cách gián tiếp từ các hiệu ứng hấp dẫn mà nó gây ra đối với vật chất nhìn thấy được, bức xạ và cấu trúc quy mô lớn của vũ trụ.
                            <ul>
                                <li><strong>Bằng chứng:</strong> Tốc độ quay bất thường của các thiên hà (các ngôi sao ở rìa quay nhanh hơn dự kiến nếu chỉ có vật chất nhìn thấy), chuyển động của các thiên hà trong cụm thiên hà, hiện tượng thấu kính hấp dẫn (ánh sáng từ các vật thể xa bị bẻ cong bởi trường hấp dẫn của vật chất tối), và các bất đẳng hướng trong bức xạ nền vi sóng vũ trụ (CMB).</li>
                                <li><strong>Bản chất:</strong> Hoàn toàn chưa được biết. Các ứng cử viên hàng đầu bao gồm các hạt WIMPs (Weakly Interacting Massive Particles - Hạt nặng tương tác yếu), axion, hoặc có thể là các lỗ đen nguyên thủy. Các thí nghiệm trên toàn thế giới đang nỗ lực để phát hiện trực tiếp các hạt vật chất tối.</li>
                            </ul>

                            <h6>Năng Lượng Tối (Dark Energy)</h6>
                            <p><strong>Năng lượng tối</strong> là một dạng năng lượng giả thuyết thấm đẫm toàn bộ không gian và có xu hướng làm tăng tốc độ giãn nở của vũ trụ. Nó được đề xuất để giải thích các quan sát từ những năm 1990 (chủ yếu từ các siêu tân tinh loại Ia) cho thấy vũ trụ không chỉ giãn nở mà còn giãn nở với tốc độ ngày càng tăng.</p>
                            <ul>
                                <li><strong>Bản chất:</strong> Cũng hoàn toàn chưa được biết. Một khả năng là nó có thể là một <strong>hằng số vũ trụ</strong> ($\Lambda$), một thuộc tính nội tại của chân không có áp suất âm. Một khả năng khác là nó liên quan đến một trường động lực mới, đôi khi được gọi là "quintessence", có thể thay đổi theo không gian và thời gian.</li>
                                <li><strong>Tác động:</strong> Nếu năng lượng tối tiếp tục chiếm ưu thế và làm vũ trụ giãn nở ngày càng nhanh, nó có thể dẫn đến các kịch bản tương lai như "Big Rip" (Vụ Xé Lớn), nơi mọi cấu trúc bị xé toạc, hoặc một sự giãn nở vĩnh cửu dẫn đến một vũ trụ lạnh lẽo và trống rỗng ("Big Freeze").</li>
                            </ul>
                            <p>Việc tìm hiểu bản chất của vật chất tối và năng lượng tối là một trong những thách thức lớn nhất và thú vị nhất trong vật lý thiên văn và vũ trụ học hiện đại, có khả năng cách mạng hóa hiểu biết của chúng ta về các định luật cơ bản của tự nhiên.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="imageModalLabel">Hình Ảnh Vũ Trụ</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img src="" id="modalImageDisplay" class="img-fluid" alt="Hình ảnh vũ trụ phóng to">
          </div>
        </div>
      </div>
    </div>

    <main class="sun-content-section">
        <?php
            // 1. Xác định đường dẫn đến tệp nội dung dựa trên ngôn ngữ hiện tại
            $content_file = $_SERVER['DOCUMENT_ROOT'] . "/galaxy/content/footer_{$current_lang}.html";

            // 2. Kiểm tra tệp có tồn tại không và đọc toàn bộ nội dung của nó
            if (file_exists($content_file)) {
                echo file_get_contents($content_file);
            } else {
                echo "Nội dung không có sẵn cho ngôn ngữ này.";
            }
        ?>
    </main>

     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="/galaxy/js/vutru.js"></script>
</body>
</html>