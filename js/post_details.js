document.addEventListener('DOMContentLoaded', function() {
    // FIX 1: Toàn bộ code được đặt trong MỘT sự kiện DOMContentLoaded duy nhất để đảm bảo ổn định.

    // --- LOGIC PREVIEW FILE ---
    const setupFilePreview = (formElement) => {
        if (formElement.dataset.previewInitialized) return;
        formElement.dataset.previewInitialized = 'true';
        const fileInput = formElement.querySelector('.comment-media-input');
        const previewContainer = formElement.querySelector('.preview-container');
        if (!fileInput || !previewContainer) return;
        let dataTransfer = new DataTransfer();
        fileInput.addEventListener('change', function() {
            dataTransfer = new DataTransfer();
            for (let file of this.files) { dataTransfer.items.add(file); }
            this.files = dataTransfer.files;
            renderPreview();
        });
        const renderPreview = () => {
            previewContainer.innerHTML = '';
            Array.from(dataTransfer.files).forEach((file, index) => {
                const reader = new FileReader();
                const item = document.createElement('div');
                item.className = 'preview-item';
                const btn = document.createElement('button');
                btn.className = 'remove-btn';
                btn.innerHTML = '&times;';
                btn.type = 'button';
                btn.onclick = () => {
                    const newFiles = new DataTransfer();
                    Array.from(dataTransfer.files).forEach((f, i) => { if (i !== index) newFiles.items.add(f); });
                    dataTransfer = newFiles;
                    fileInput.files = dataTransfer.files;
                    renderPreview();
                };
                reader.onload = e => {
                    let mediaElement = file.type.startsWith('image/') ? document.createElement('img') : document.createElement('video');
                    mediaElement.src = e.target.result;
                    if(mediaElement.tagName === 'VIDEO') mediaElement.muted = true;
                    item.appendChild(mediaElement);
                    item.appendChild(btn);
                    previewContainer.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        };
    };
    document.querySelectorAll('form.comment-submission-form').forEach(setupFilePreview);
    
    // --- LOGIC GỬI COMMENT (AJAX) ---
    document.body.addEventListener('submit', function(event) {
        if (event.target.matches('.comment-submission-form')) {
            event.preventDefault();
            const form = event.target;
            const submitButton = form.querySelector('button[type="submit"]');
            const formData = new FormData(form);
            if(submitButton) submitButton.disabled = true;

            fetch('submit_comment.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const parentId = data.parent_id || '';
                    
                    // FIX 2: Sửa logic tìm đúng vùng chứa cho cả bình luận chính và trả lời.
                    const targetContainer = parentId ? document.getElementById('replies-for-' + parentId) : document.querySelector('.comment-list');
                    
                    if (targetContainer) {
                       targetContainer.insertAdjacentHTML('beforeend', data.comment_html);
                       
                       // FIX 3: Luôn đảm bảo vùng chứa trả lời được hiển thị sau khi thêm.
                       if (parentId) {
                           targetContainer.style.display = 'block';
                       }

                       const newCommentNode = targetContainer.lastElementChild;
                       const newForm = newCommentNode.querySelector('form.comment-submission-form');
                       if(newForm) setupFilePreview(newForm);
                       newCommentNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                       
                       const noCommentMsg = document.getElementById('no-comment-message');
                       if (noCommentMsg) noCommentMsg.remove();
                    }
                    
                    form.reset();
                    const preview = form.querySelector('.preview-container');
                    if (preview) preview.innerHTML = '';
                    
                    const countEl = document.getElementById('comment-count');
                    const commentSection = document.querySelector('.comment-section');
                    const commentPluralText = commentSection.dataset.textCommentPlural || 'Bình luận';
                    let currentCount = parseInt(countEl.innerText) || 0;
                    countEl.innerText = (currentCount + 1) + ' ' + commentPluralText;

                } else {
                    const commentSection = document.querySelector('.comment-section');
                    const errorGenericText = commentSection.dataset.textErrorGeneric || 'Có lỗi xảy ra.';
                    alert(data.message || errorGenericText);
                }
            })
            .catch(error => console.error('Lỗi:', error))
            .finally(() => {
                if(submitButton) submitButton.disabled = false;
            });
        }
    });

    // --- LOGIC CÁC NÚT BẤM KHÁC (REPLY, UPLOAD, VIEW REPLIES) ---
    document.body.addEventListener('click', function(event) {
        const target = event.target;
        const replyBtn = target.closest('.reply-btn');
        const uploadLabel = target.closest('.file-upload-label');
        const viewRepliesBtn = target.closest('.view-replies-btn');

        if (replyBtn) {
            event.preventDefault();
            const commentId = replyBtn.dataset.commentId;
            const replyForm = document.getElementById('reply-form-' + commentId);
            if (replyForm) {
                const isVisible = replyForm.style.display === 'block';
                document.querySelectorAll('.reply-form').forEach(form => form.style.display = 'none');
                replyForm.style.display = isVisible ? 'none' : 'block';
            }
        }
        
        if(uploadLabel) {
            const form = uploadLabel.closest('form');
            if(form) form.querySelector('.comment-media-input').click();
        }

        if(viewRepliesBtn) {
            event.preventDefault();
            const targetId = viewRepliesBtn.dataset.targetReplies;
            const repliesContainer = document.getElementById(targetId);
            if(repliesContainer) {
                const isVisible = repliesContainer.style.display === 'block';
                repliesContainer.style.display = isVisible ? 'none' : 'block';
                
                if (isVisible) {
                    viewRepliesBtn.innerHTML = `<i class="fas fa-arrow-turn-down"></i> ${viewRepliesBtn.dataset.viewText}`;
                } else {
                    viewRepliesBtn.innerHTML = `<i class="fas fa-arrow-turn-up"></i> ${viewRepliesBtn.dataset.hideText}`;
                }
            }
        }
    });

    // --- LOGIC MENU HEADER & CHUYỂN NGÔN NGỮ ---
    const menuToggle = document.getElementById('menu-toggle');
    const mainMenu = document.getElementById('main-menu');

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
    }
    
    const langToggle = document.getElementById('lang-toggle');
    if (langToggle) {
        langToggle.addEventListener('change', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (this.checked) {
                urlParams.set('lang', 'en');
            } else {
                urlParams.set('lang', 'vi');
            }
            window.location.search = urlParams.toString();
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
});