document.addEventListener('DOMContentLoaded', function() {
               // cho trang vũ trụ 

        // JavaScript cho Image Modal trong thư viện
        const imageModal = document.getElementById('imageModal');
        if (imageModal) {
            imageModal.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget; // Nút đã kích hoạt modal
                const imageSrc = button.getAttribute('data-image-src'); // Lấy link ảnh lớn
                const imageTitle = button.getAttribute('data-image-title') || button.getAttribute('alt') || 'Hình ảnh vũ trụ'; // Lấy tiêu đề/alt

                const modalImage = imageModal.querySelector('#modalImageDisplay');
                const modalTitle = imageModal.querySelector('#imageModalLabel');

                modalImage.src = imageSrc;
                modalImage.alt = imageTitle + " - Phóng to";
                modalTitle.textContent = imageTitle;
            });
        }

          // === CODE CHO HIỆU ỨNG CUỘN ===
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