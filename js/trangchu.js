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
    });
    // === CODE CHO HIỆU ỨNG CUỘN ===
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ thực hiện khi có các phần tử cần animate
    const elementsToAnimate = document.querySelectorAll('.animate-on-scroll');
    if (elementsToAnimate.length === 0) return;

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, {
        threshold: 0.1
    });

    elementsToAnimate.forEach(element => {
        observer.observe(element);
    });
});

// === HIỆU ỨNG ĐẾM SỐ TỰ ĐỘNG ===
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200; // Tốc độ, có thể thay đổi

    counters.forEach(counter => {
        const updateCount = () => {
            const target = +counter.getAttribute('data-target');
            const count = +counter.innerText;
            const inc = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + inc);
                setTimeout(updateCount, 1);
            } else {
                counter.innerText = target;
            }
        };
        updateCount();
    });
}

// Dùng Intersection Observer để chỉ chạy hiệu ứng khi người dùng nhìn thấy
const statsSectionObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            observer.unobserve(entry.target); // Chỉ chạy 1 lần
        }
    });
}, { threshold: 0.5 });

const statsSection = document.querySelector('.stats-section');
if (statsSection) {
    statsSectionObserver.observe(statsSection);
}
