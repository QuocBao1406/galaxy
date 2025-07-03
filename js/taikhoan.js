document.addEventListener('DOMContentLoaded', function () {
    // CẤU TRÚC ĐÚNG: Chỉ một DOMContentLoaded duy nhất cho toàn bộ code

    const webRootPrefix = '/galaxy';

    // --- LOGIC CHO TRANG TÀI KHOẢN ---
    const profileAvatarImg = document.getElementById('profile-avatar-img');
    const avatarUploadInput = document.getElementById('avatar-upload-input');
    const editButton = document.getElementById('profile-edit-btn');
    const saveButton = document.getElementById('profile-save-btn');
    const cancelButton = document.getElementById('profile-cancel-btn');
    const logoutButton = document.getElementById('profile-logout-btn');
    const infoForm = document.getElementById('profile-info-form');
    const passwordForm = document.getElementById('change-password-form');
    let originalValues = {};

    if (avatarUploadInput) {
        avatarUploadInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            const oldAvatarSrc = profileAvatarImg.src;
            reader.onload = e => { profileAvatarImg.src = e.target.result; };
            reader.readAsDataURL(file);
            const formData = new FormData();
            formData.append('avatar', file);
            fetch(webRootPrefix + '/update_avatar.php', { method: 'POST', body: formData })
            .then(response => response.ok ? response.json() : response.text().then(text => Promise.reject(new Error(text))))
            .then(data => {
                if (data.success && data.newAvatarUrl) {
                    profileAvatarImg.src = data.newAvatarUrl + '?t=' + new Date().getTime();
                    alert(data.message || "Cập nhật ảnh đại diện thành công!");
                } else {
                    profileAvatarImg.src = oldAvatarSrc;
                    alert("Lỗi: " + (data.message || "Không thể cập nhật ảnh."));
                }
            }).catch(error => {
                profileAvatarImg.src = oldAvatarSrc;
                alert("Lỗi nghiêm trọng khi tải ảnh lên: " + error.message);
            });
        });
    }

    if (editButton) {
        editButton.addEventListener('click', function() {
            originalValues = {};
            infoForm.querySelectorAll('.info-value-v2.editable').forEach(span => {
                const fieldName = span.dataset.field;
                originalValues[fieldName] = span.textContent;
                span.style.display = "none";
            });
            infoForm.querySelectorAll('.edit-input-v2').forEach(input => {
                const fieldName = input.dataset.fieldInput;
                const span = infoForm.querySelector(`.info-value-v2.editable[data-field="${fieldName}"]`);
                if(span) {
                    let originalText = originalValues[fieldName] || span.textContent;
                    if(originalText.trim() === 'Chưa cập nhật') originalText = '';
                    if (input.type === 'date' && originalText) {
                        const parts = originalText.split('/');
                        input.value = (parts.length === 3) ? `${parts[2]}-${parts[1]}-${parts[0]}` : '';
                    } else {
                        input.value = originalText;
                    }
                }
                input.style.display = "block";
            });
            editButton.style.display = "none";
            saveButton.style.display = "inline-flex";
            cancelButton.style.display = "inline-flex";
        });
    }
    
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            infoForm.querySelectorAll('.info-value-v2.editable').forEach(span => { span.style.display = "block"; });
            infoForm.querySelectorAll('.edit-input-v2').forEach(input => { input.style.display = "none"; });
            editButton.style.display = "inline-flex";
            saveButton.style.display = "none";
            cancelButton.style.display = "none";
        });
    }
    
    if (saveButton) {
        saveButton.addEventListener("click", function() {
            const updatedUserData = {};
            let isValid = true;
            infoForm.querySelectorAll('.edit-input-v2').forEach(input => {
                updatedUserData[input.name] = input.value.trim();
            });
             if (updatedUserData['email'] && !/^\S+@\S+\.\S+$/.test(updatedUserData['email'])) {
                alert('Định dạng email không hợp lệ!');
                isValid = false;
            }
            if (!isValid) return;

            fetch(webRootPrefix + '/capnhat_taikhoan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(updatedUserData)
            })
            .then(response => response.ok ? response.json() : response.text().then(text => Promise.reject(new Error(text))))
            .then(data => {
                if (data.success && data.user) {
                    alert(data.message || "Cập nhật thành công!");
                    const serverUser = data.user;
                    infoForm.querySelectorAll('.info-value-v2.editable').forEach(span => {
                        const fieldName = span.dataset.field;
                        if (serverUser.hasOwnProperty(fieldName)) {
                            let displayValue = serverUser[fieldName];
                            if (fieldName === 'birthday' && displayValue) {
                                const parts = displayValue.split('-');
                                displayValue = (parts.length === 3) ? `${parts[2]}/${parts[1]}/${parts[0]}` : 'Chưa cập nhật';
                            }
                            span.textContent = displayValue || 'Chưa cập nhật';
                        }
                    });
                    document.getElementById('profile-fullname-display').textContent = serverUser.fullname || serverUser.username;
                    cancelButton.click();
                } else {
                    alert("Lỗi: " + (data.message || "Không thể cập nhật."));
                }
            }).catch(error => {
                alert("Lỗi nghiêm trọng khi gửi dữ liệu: " + error.message);
            });
        });
    }

    if (logoutButton) {
    logoutButton.addEventListener("click", function() {
        if (confirm(logoutMessage)) {
            window.location.href = webRootPrefix + "/logout.php";
        }
    });
}

    const navLinks = document.querySelectorAll('.dashboard-nav a');
    const contentPanels = document.querySelectorAll('.dashboard-content');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            const targetId = this.dataset.tab;
            contentPanels.forEach(panel => {
                panel.style.display = (panel.id === targetId) ? 'block' : 'none';
            });
        });
    });
    
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            fetch('change_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                alert(result.message);
                if (result.success) {
                    this.reset();
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi không mong muốn.');
            });
        });
    }

    // --- LOGIC CHUNG CHO HEADER MENU ---
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
            const url = new URL(window.location.href);
            url.searchParams.set('lang', this.checked ? 'en' : 'vi');
            window.location.href = url.toString();
        });
    }

    const dropdownItems = document.querySelectorAll('#main-menu .dropdown');
    dropdownItems.forEach(function(item) {
        const link = item.querySelector('a');
        if (link) {
            // SỬA LỖI: Bỏ điều kiện if, cho phép bấm vào menu con trên mọi màn hình
            link.addEventListener('click', function(event) {
                // Ngăn trang nhảy nếu href="#"
                if(link.getAttribute('href') === '#') {
                    event.preventDefault();
                }
                
                // Nếu là trên di động thì mới toggle, trên desktop sẽ hoạt động bằng CSS :hover
                // Nhưng để nhất quán, ta sẽ cho phép toggle trên cả desktop
                let parentLi = event.currentTarget.closest('.dropdown');
                if (parentLi) {
                    parentLi.classList.toggle('submenu-open');
                }
            });
        }
    });
});