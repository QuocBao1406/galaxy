document.addEventListener('DOMContentLoaded', function() {
        const mediaUpload = document.getElementById('media-upload');
        const previewContainer = document.getElementById('preview-container');
        if (mediaUpload && previewContainer) {
            let dataTransfer = new DataTransfer();
            mediaUpload.addEventListener('change', function(event) {
                previewContainer.innerHTML = '';
                dataTransfer = new DataTransfer();
                for(const file of event.target.files) {
                    dataTransfer.items.add(file);
                }
                this.files = dataTransfer.files;

                Array.from(this.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const item = document.createElement('div');
                        item.className = 'preview-item';
                        let mediaElement;
                        if(file.type.startsWith('image/')) {
                            mediaElement = document.createElement('img');
                        } else {
                            mediaElement = document.createElement('video');
                            mediaElement.controls = true;
                        }
                        mediaElement.src = e.target.result;
                        item.appendChild(mediaElement);
                        previewContainer.appendChild(item);
                    }
                    reader.readAsDataURL(file);
                });
            });
        }
        
        document.body.addEventListener('click', function(event) {
            if (event.target.matches('.reaction-btn')) {
                event.preventDefault();
                const button = event.target;
                const postId = button.dataset.postId;
                const reactionType = button.dataset.reaction;
                const wasActive = button.classList.contains('active-reaction');
                
                const reactionBar = document.getElementById('reaction-bar-' + postId);
                reactionBar.querySelectorAll('.reaction-btn').forEach(btn => {
                    btn.classList.remove('active-reaction', 'like', 'love', 'haha', 'angry');
                });

                if (!wasActive) {
                    button.classList.add('active-reaction', reactionType);
                }

                fetch('handle_reaction.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ post_id: postId, reaction_type: reactionType })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const countSpan = document.getElementById('reactions-count-' + postId);
                        let iconsStr = '';
                        let totalCount = 0;
                        if (data.counts && Object.keys(data.counts).length > 0) {
                            const reactionMap = { love: '❤️', like: '👍', haha: '😂', angry: '😡' };
                            for (const type in reactionMap) {
                                if (data.counts[type] && data.counts[type] > 0) {
                                    iconsStr += reactionMap[type];
                                    totalCount += data.counts[type];
                                }
                            }
                        }
                        countSpan.textContent = (totalCount > 0) ? `${iconsStr.trim()} ${totalCount}` : '';
                    } else {
                        alert(data.message || 'Có lỗi xảy ra.');
                    }
                })
                .catch(error => console.error('Lỗi Fetch:', error));
            }
        });
    });

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