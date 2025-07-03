document.addEventListener('DOMContentLoaded', function() {
    // --- PHẦN 1: XỬ LÝ MENU CHUNG ---
    const menuToggle = document.getElementById('menu-toggle');
    const mainMenu = document.getElementById('main-menu');

    if (menuToggle && mainMenu) {
        menuToggle.addEventListener('click', function() {
            mainMenu.classList.toggle('is-open');
            this.innerHTML = mainMenu.classList.contains('is-open') ? '✖' : '☰';
        });
    }

    const langToggle = document.getElementById('lang-toggle');
    if (langToggle) {
        langToggle.addEventListener('change', function() {
            window.location.href = this.checked ? '?lang=en' : '?lang=vi';
        });
    }

    const dropdownItems = document.querySelectorAll('#main-menu .dropdown');
    dropdownItems.forEach(function(item) {
        const link = item.querySelector('a');
        link.addEventListener('click', function(event) {
            if (window.getComputedStyle(menuToggle).display !== 'none') {
                event.preventDefault();
                item.classList.toggle('submenu-open');
            }
        });
    });

    // --- PHẦN 2: XỬ LÝ TƯƠNG TÁC CHO KHU VỰC GIỚI THIỆU ĐỘI NGŨ ---

    // Dữ liệu chính xác của các thành viên, không có chức năng
    const teamData = {
        'minh': {
            name: 'Lê Đức Bảo Minh',
            photo: '/galaxy/images-icon/leducbaominh.jpg',
            dob: '01/10/2006',
            studentId: '24IT161',
            gmail: 'minhldb.24it@vku.udn.vn',
            phone: '0777439719'
        },
        'hoang': {
            name: 'Nguyễn Tam Huy Hoàng',
            photo: '/galaxy/images-icon/nguyentamhuyhoang.jpg',
            dob: 'xx/xx/2006',
            studentId: '24IT080',
            gmail: 'hoangnth.24it@vku.udn.vn',
            phone: '0379799593'
        },
        'hien': {
            name: 'Đồng Trần Diệu Hiền',
            photo: '/galaxy/images-icon/dongtrandieuhien.jpg',
            dob: '21/05/2006',
            studentId: '24IT060',
            gmail: 'hiendtd.24it@vku.udn.vn',
            phone: '0703261847'
        },
        'hieu': {
            name: 'Trần Đức Hiếu',
            photo: '/galaxy/images-icon/tranduchieu.jpg',
            dob: 'xx/xx/2006',
            studentId: '24IT069',
            gmail: 'hieutd.24it@vku.udn.vn',
            phone: '0986068501'
        }
    };

    const displayArea = document.querySelector('.team-member-display');
    const thumbnails = document.querySelectorAll('.member-thumbnail');

    // Hàm để hiển thị thông tin thành viên ra màn hình
    function showMemberInfo(memberKey) {
        // Kiểm tra xem khu vực hiển thị có tồn tại không
        if (!displayArea) return;

        const member = teamData[memberKey];
        if (!member) return;

        // Tạo nội dung HTML mới với thông tin cá nhân
        displayArea.innerHTML = `
            <img src="${member.photo}" alt="${member.name}" class="display-photo">
            <h3 class="display-name">${member.name}</h3>
            <div class="display-info-grid">
                <p><i class="fas fa-calendar-alt fa-fw"></i> <span><strong>Ngày sinh:</strong> ${member.dob}</span></p>
                <p><i class="fas fa-id-card fa-fw"></i> <span><strong>Mã sinh viên:</strong> ${member.studentId}</span></p>
                <p><i class="fas fa-envelope fa-fw"></i> <span><strong>Gmail:</strong> ${member.gmail}</span></p>
                <p><i class="fas fa-phone fa-fw"></i> <span><strong>SĐT:</strong> ${member.phone}</span></p>
            </div>
        `;

        // Làm nổi bật thumbnail của thành viên đang được chọn
        thumbnails.forEach(thumb => {
            thumb.classList.toggle('active', thumb.getAttribute('data-member') === memberKey);
        });
    }

    // Gán sự kiện click cho mỗi thumbnail
    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', () => {
            const memberKey = thumb.getAttribute('data-member');
            showMemberInfo(memberKey);
        });
    });

    // Tự động hiển thị thông tin thành viên đầu tiên khi tải trang
    if (thumbnails.length > 0) {
        showMemberInfo(thumbnails[0].getAttribute('data-member'));
    }
});