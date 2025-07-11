document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const mainMenu = document.getElementById('main-menu');

        // --- Code cũ: Xử lý đóng/mở menu chính ---
        if (menuToggle && mainMenu) {
            menuToggle.addEventListener('click', function() {
                mainMenu.classList.toggle('is-open');

                if (mainMenu.classList.contains('is-open')) {
                    this.innerHTML = '✖';
                    this.setAttribute('aria-label', 'Đóng menu');
                } else {
                    this.innerHTML = '☰';
                    this.setAttribute('aria-label', 'Mở menu');
                }
            });
             // === CODE MỚI CHO CÔNG TẮC NGÔN NGỮ ===
    const langToggle = document.getElementById('lang-toggle');

    if (langToggle) {
        langToggle.addEventListener('change', function() {
            if (this.checked) {
                // Nếu công tắc được BẬT, chuyển sang Tiếng Anh
                window.location.href = '?lang=en';
            } else {
                // Nếu công tắc được TẮT, chuyển về Tiếng Việt
                window.location.href = '?lang=vi';
            }
        });
    }
    // ===================================
        }

        // --- Code MỚI: Xử lý đóng/mở các menu con ---
        const dropdownItems = document.querySelectorAll('#main-menu .dropdown, #main-menu .dropdown2');

        dropdownItems.forEach(function(item) {
            // Lấy thẻ <a> là con trực tiếp của li.dropdown
            const link = item.querySelector('a');

            link.addEventListener('click', function(event) {
                // Chỉ hoạt động khi nút 3 gạch đang hiển thị (tức là trên di động)
                if (window.getComputedStyle(menuToggle).display !== 'none') {
                    // Ngăn không cho trình duyệt nhảy trang khi bấm vào mục cha
                    event.preventDefault();
                    // Thêm/xóa class để đóng/mở menu con
                    item.classList.toggle('submenu-open');
                }
            });
        });

    // 1. KHỞI TẠO AOS (ANIMATE ON SCROLL)
    // Hiệu ứng này sẽ kích hoạt cho các phần tử có thuộc tính `data-aos`
    AOS.init({
        once: true, // Hiệu ứng chỉ chạy một lần
        disable: 'mobile' // Tắt hiệu ứng trên điện thoại để tăng hiệu suất
    });


    // 2. KHỞI TẠO SWIPER JS
    // Chỉ khởi tạo slider nếu phần tử .mySwiper tồn tại trên trang
    if (document.querySelector('.mySwiper')) {
        const swiper = new Swiper('.mySwiper', {
            // Tùy chọn cho slider
            loop: true, // Lặp vô tận
            autoplay: {
                delay: 4000, // Tự động chuyển slide sau 4 giây
                disableOnInteraction: false, // Không dừng khi người dùng tương tác
            },

            slidesPerView: 1, // 👈 Chỉ hiện 1 slide
            spaceBetween: 0, // 👈 Không có khoảng trống giữa slide
            
            // Hiển thị các nút điều hướng
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // Hiển thị dấu chấm phân trang
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },

            // Hiệu ứng chuyển slide (có thể thử 'fade', 'cube', 'coverflow', 'flip')
            effect: 'fade', 
        });
    }
});