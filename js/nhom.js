document.addEventListener('DOMContentLoaded', function() {

    // --- KHỞI TẠO CÁC CHỨC NĂNG ---
    initMediaPreview();
    initPostOptionsMenus();
    initReactionButtons();
    initMainMenu();
    initLanguageToggle();
    initUserSearch(); // Giả sử bạn có phần tìm kiếm ở đâu đó

    // --- CÁC HÀM XỬ LÝ ---

    /**
     * Chức năng: Xem trước hình ảnh/video khi người dùng chọn file để đăng bài.
     */
    function initMediaPreview() {
        const mediaUpload = document.getElementById('media-upload');
        const previewContainer = document.getElementById('preview-container');

        if (!mediaUpload || !previewContainer) return;

        mediaUpload.addEventListener('change', function(event) {
            previewContainer.innerHTML = ''; // Xóa các preview cũ
            if (!event.target.files.length) return;

            Array.from(event.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const item = document.createElement('div');
                    item.className = 'preview-item';

                    let mediaElement;
                    if (file.type.startsWith('image/')) {
                        mediaElement = document.createElement('img');
                        mediaElement.src = e.target.result;
                        mediaElement.alt = 'Xem trước ảnh';
                    } else if (file.type.startsWith('video/')) {
                        mediaElement = document.createElement('video');
                        mediaElement.src = e.target.result;
                        mediaElement.controls = true;
                    }

                    if (mediaElement) {
                        item.appendChild(mediaElement);
                        previewContainer.appendChild(item);
                    }
                };
                reader.readAsDataURL(file);
            });
        });
    }

    /**
     * Chức năng: Xử lý menu 3 chấm (sửa/xóa bài viết).
     */
    function initPostOptionsMenus() {
        document.body.addEventListener('click', function(e) {
            const optionsBtn = e.target.closest('.options-btn');

            // Ẩn tất cả các dropdown khác trước
            document.querySelectorAll('.options-dropdown.show').forEach(menu => {
                if (!menu.parentElement.contains(e.target)) {
                    menu.classList.remove('show');
                }
            });

            if (optionsBtn) {
                e.preventDefault();
                const menu = optionsBtn.nextElementSibling;
                if (menu) {
                    menu.classList.toggle('show');
                }
            }
        });
    }


    /**
     * Chức năng: Xử lý thả cảm xúc (like, love, haha...).
     */
    function initReactionButtons() {
        document.body.addEventListener('click', function(event) {
            if (!event.target.matches('.reaction-btn')) return;

            event.preventDefault();
            const button = event.target;
            const postId = button.dataset.postId;
            const reactionType = button.dataset.reaction;
            const wasActive = button.classList.contains('active-reaction');

            const reactionBar = document.getElementById('reaction-bar-' + postId);
            if (!reactionBar) return;

            // Xóa trạng thái active của tất cả các nút trong bar
            reactionBar.querySelectorAll('.reaction-btn').forEach(btn => {
                btn.classList.remove('active-reaction', 'like', 'love', 'haha', 'angry');
            });

            // Nếu nút chưa active -> active nó
            if (!wasActive) {
                button.classList.add('active-reaction', reactionType);
            }

            // Gửi yêu cầu lên server
            fetch('handle_reaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ post_id: postId, reaction_type: reactionType, context: "group" })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateReactionsUI(postId, data.counts);
                } else {
                    // Có thể khôi phục lại trạng thái cũ của nút nếu thất bại
                    console.error(data.message || 'Có lỗi xảy ra.');
                }
            })
            .catch(error => console.error('Lỗi Fetch:', error));
        });

        function updateReactionsUI(postId, counts) {
            const countSpan = document.getElementById('reactions-count-' + postId);
            if (!countSpan) return;

            let iconsStr = '';
            let totalCount = 0;
            const reactionMap = { love: '❤️', like: '👍', haha: '😂', angry: '😡' };

            if (counts && Object.keys(counts).length > 0) {
                 // Sắp xếp để icon luôn theo thứ tự nhất quán
                ['like', 'love', 'haha', 'angry'].forEach(type => {
                    if (counts[type] && counts[type] > 0) {
                        iconsStr += reactionMap[type];
                        totalCount += counts[type];
                    }
                });
            }
            
            countSpan.innerHTML = (totalCount > 0) ? `${iconsStr.trim()} <span>${totalCount}</span>` : '';
        }
    }

    /**
     * Chức năng: Menu chính trên mobile (hamburger menu).
     */
    function initMainMenu() {
        const menuToggle = document.getElementById('menu-toggle');
        const mainMenu = document.getElementById('main-menu');

        if (!menuToggle || !mainMenu) return;

        menuToggle.addEventListener('click', function() {
            mainMenu.classList.toggle('is-open');
            this.innerHTML = mainMenu.classList.contains('is-open') ? '✖' : '☰';
            this.setAttribute('aria-label', mainMenu.classList.contains('is-open') ? 'Đóng menu' : 'Mở menu');
        });

        const dropdownItems = mainMenu.querySelectorAll('.dropdown, .dropdown2');
        dropdownItems.forEach(item => {
            const link = item.querySelector('a');
            link.addEventListener('click', function(event) {
                if (window.getComputedStyle(menuToggle).display !== 'none') {
                    event.preventDefault();
                    item.classList.toggle('submenu-open');
                }
            });
        });
    }

    /**
     * Chức năng: Chuyển đổi ngôn ngữ.
     */
    function initLanguageToggle() {
        const langToggle = document.getElementById('lang-toggle');
        if (langToggle) {
            langToggle.addEventListener('change', function() {
                const lang = this.checked ? 'en' : 'vi';
                window.location.href = `?lang=${lang}`;
            });
        }
    }
    
    /**
     * Chức năng: Tìm kiếm người dùng (ví dụ).
     */
    function initUserSearch() {
        const input = document.getElementById('search-user');
        const suggestions = document.getElementById('suggestions');
        
        if (!input || !suggestions) return;

        input.addEventListener('input', function () {
            const query = this.value.trim();
            if (query.length < 2) { // Chỉ tìm khi có ít nhất 2 ký tự
                suggestions.style.display = 'none';
                return;
            }

            fetch(`/galaxy/search_user.php?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(user => {
                            const li = document.createElement('li');
                            li.innerHTML = `<a href="trangcanhan.php?user_id=${user.id}">${user.username}</a>`;
                            suggestions.appendChild(li);
                        });
                        suggestions.style.display = 'block';
                    } else {
                         suggestions.style.display = 'none';
                    }
                });
        });

        document.addEventListener('click', function (e) {
            if (!suggestions.contains(e.target) && !input.contains(e.target)) {
                suggestions.style.display = 'none';
            }
        });
    }

document.querySelectorAll('.btn-edit-group').forEach(button => {
  button.addEventListener('click', function () {
    document.getElementById('edit-group-id').value = this.dataset.id;
    document.getElementById('edit-group-name').value = this.dataset.name;
    document.getElementById('edit-group-description').value = this.dataset.description;
    document.getElementById('edit-group-privacy').value = this.dataset.privacy;
  });
});

document.getElementById('goto-members-tab')?.addEventListener('click', function(e) {
    e.preventDefault();
    const tabTrigger = new bootstrap.Tab(document.getElementById('members-tab'));
    tabTrigger.show();
});

});