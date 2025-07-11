<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /galaxy/TAIKHOAN/login-register.php");
    exit();
}
$current_user_id = $_SESSION['user_id'];
$conversation_id = isset($_GET['conversation_id']) ? intval($_GET['conversation_id']) : null;

$stmt = $conn->prepare("
    SELECT 
        c.id, u.id as partner_id, u.username, u.avatar,
        (SELECT message FROM messages m WHERE m.conversation_id = c.id ORDER BY m.created_at DESC LIMIT 1) as last_message
    FROM conversations c
    JOIN users u ON u.id = IF(c.user1_id = ?, c.user2_id, c.user1_id)
    WHERE c.user1_id = ? OR c.user2_id = ?
    ORDER BY c.updated_at DESC
");
$stmt->bind_param("iii", $current_user_id, $current_user_id, $current_user_id);
$stmt->execute();
$conversations = $stmt->get_result();
$stmt->close();

$messages = null;
$partner = null;
$shared_images = null;

if ($conversation_id) {
    $stmt = $conn->prepare("
        SELECT u.id, u.username, u.avatar 
        FROM conversations c
        JOIN users u ON u.id = IF(c.user1_id = ?, c.user2_id, c.user1_id)
        WHERE c.id = ? AND (c.user1_id = ? OR c.user2_id = ?)
    ");
    $stmt->bind_param("iiii", $current_user_id, $conversation_id, $current_user_id, $current_user_id);
    $stmt->execute();
    $partner = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("SELECT * FROM messages WHERE conversation_id = ? ORDER BY created_at ASC");
    $stmt->bind_param("i", $conversation_id);
    $stmt->execute();
    $messages = $stmt->get_result();
    $stmt->close();

    $stmt = $conn->prepare("SELECT image_path FROM messages WHERE conversation_id = ? AND image_path IS NOT NULL AND image_path != '' ORDER BY created_at DESC");
    $stmt->bind_param("i", $conversation_id);
    $stmt->execute();
    $shared_images = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hộp Thư</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/galaxy/css/chat.css">
</head>

<body>

    <div class="chat-container">
        <div class="sidebar-left">
            <div class="sidebar-left-header d-flex justify-content-between align-items-center">
                <h4>Hộp thư</h4>
                <a href="/galaxy/pages/congdong.php" title="Quay lại Cộng đồng">
                    <i class="bi bi-arrow-left-circle-fill fs-4"></i>
                </a>
            </div>
            <ul class="list-unstyled conversations-list">
                <?php while ($conv = $conversations->fetch_assoc()): ?>
                    <li class="conversation-item <?= ($conv['id'] == $conversation_id) ? 'active' : '' ?>">
                        <a href="?conversation_id=<?= $conv['id'] ?>">
                            <img src="<?= htmlspecialchars($conv['avatar'] ?: 'default_avatar.png') ?>" width="50"
                                height="50" class="rounded-circle">
                            <div class="conversation-info">
                                <span class="username"><?= htmlspecialchars($conv['username']) ?></span>
                                <span
                                    class="last-message"><?= htmlspecialchars($conv['last_message'] ?: 'Chưa có tin nhắn nào.') ?></span>
                            </div>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>

        <div class="chat-box">
            <?php if ($conversation_id && $partner): ?>
                <div class="chat-header">
                    <?= htmlspecialchars($partner['username']) ?>
                </div>
                <div class="messages-area" id="messages-area">
                    <?php while ($msg = $messages->fetch_assoc()): ?>
                        <div class="message <?= $msg['sender_id'] == $current_user_id ? 'sent' : 'received' ?>">
                            <div class="message-content">
                                <?php if ($msg['message']): ?>
                                    <p class="mb-0"><?= htmlspecialchars($msg['message']) ?></p>
                                <?php endif; ?>
                                <?php if ($msg['image_path']): ?>
                                    <img src="<?= htmlspecialchars($msg['image_path']) ?>" class="message-image zoomable-image">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="message-form">
                    <form action="send_message.php" method="POST" enctype="multipart/form-data" class="d-flex">
                        <input type="hidden" name="conversation_id" value="<?= $conversation_id ?>">
                        <textarea name="message" class="form-control me-2" rows="1"
                            placeholder="Nhập tin nhắn..."></textarea>
                        <input type="file" name="image" id="imageInput" class="d-none">
                        <label for="imageInput" class="btn btn-secondary me-2" title="Gửi ảnh"><i
                                class="bi bi-image"></i></label>
                        <button type="submit" class="btn btn-primary">Gửi</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="placeholder-text">
                    <p>Chọn một cuộc trò chuyện để bắt đầu nhắn tin.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($conversation_id && $partner): ?>
            <div class="sidebar-right">
                <a href="trangcanhan.php?user_id=<?= $partner['id'] ?>" class="partner-info" style="text-decoration: none;">
                    <img src="<?= htmlspecialchars($partner['avatar'] ?: 'default_avatar.png') ?>" class="rounded-circle">
                    <h5 class="username mt-2"><?= htmlspecialchars($partner['username']) ?></h5>
                </a>
                <hr>
                <div class="shared-media">
                    <h6>Ảnh đã chia sẻ</h6>
                    <?php if ($shared_images && $shared_images->num_rows > 0): ?>
                        <div class="media-grid">
                            <?php while ($image = $shared_images->fetch_assoc()): ?>
                                <img src="<?= htmlspecialchars($image['image_path']) ?>" alt="Shared Image" class="zoomable-image">
                            <?php endwhile; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted small">Chưa có ảnh nào được chia sẻ.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div id="image-lightbox" class="lightbox">
        <span class="lightbox-close">&times;</span>
        <img class="lightbox-content" id="lightbox-img">
    </div>


    <script>
        // Tự động cuộn xuống tin nhắn cuối cùng
        const messagesArea = document.getElementById('messages-area');
        if (messagesArea) {
            messagesArea.scrollTop = messagesArea.scrollHeight;
        }

        // --- MỚI: JAVASCRIPT CHO LIGHTBOX PHÓNG TO ẢNH ---
        document.addEventListener('DOMContentLoaded', function () {
            const lightbox = document.getElementById('image-lightbox');
            const lightboxImg = document.getElementById('lightbox-img');
            const closeBtn = document.querySelector('.lightbox-close');

            // Tìm tất cả ảnh có thể phóng to và thêm sự kiện click
            const zoomableImages = document.querySelectorAll('.zoomable-image');
            zoomableImages.forEach(image => {
                image.addEventListener('click', function () {
                    lightbox.classList.add('show');
                    lightboxImg.src = this.src;
                });
            });

            // Hàm để đóng lightbox
            function closeLightbox() {
                lightbox.classList.remove('show');
            }

            // Đóng khi nhấn nút 'x'
            if (closeBtn) {
                closeBtn.addEventListener('click', closeLightbox);
            }

            // Đóng khi nhấn ra ngoài ảnh
            if (lightbox) {
                lightbox.addEventListener('click', function (e) {
                    if (e.target !== lightboxImg) {
                        closeLightbox();
                    }
                });
            }
        });
    </script>
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script>
        const socket = io("http://localhost:3000"); // nếu deploy, đổi thành domain thực tế
        const current_user_id = <?= $current_user_id ?>;
        const partner_id = <?= $partner ? $partner['id'] : 'null' ?>;
        const conversation_id = <?= $conversation_id ?? 'null' ?>;

        if (current_user_id) {
            socket.emit("register", current_user_id);
        }

        const form = document.querySelector('.message-form form');
        if (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                const message = this.message.value.trim();
                const imageFile = this.image.files[0];

                let image_path = null;

                // Nếu có ảnh, upload ảnh trước
                if (imageFile) {
                    const formData = new FormData();
                    formData.append("image", imageFile);

                    const uploadRes = await fetch("upload_image.php", {
                        method: "POST",
                        body: formData
                    });

                    const result = await uploadRes.json();
                    if (result.success) {
                        image_path = result.image_path;
                    } else {
                        alert("Gửi ảnh thất bại!");
                        return;
                    }
                }

                // Gửi WebSocket
                const messageData = {
                    from_user_id: current_user_id,
                    to_user_id: partner_id,
                    conversation_id,
                    message,
                    image_path
                };

                socket.emit("send_message", messageData);

                // Lưu vào DB
                fetch("save_message.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(messageData)
                });

                this.message.value = '';
                this.image.value = '';
            });

        }

        // Nhận tin nhắn
        socket.on("receive_message", function (data) {
            if (data.conversation_id != conversation_id) return;

            const messagesArea = document.getElementById("messages-area");
            const msgDiv = document.createElement("div");
            msgDiv.classList.add("message", data.from_user_id == current_user_id ? "sent" : "received");
            msgDiv.innerHTML = `
            <div class="message-content">
                ${data.message ? `<p class="mb-0">${data.message}</p>` : ''}
                ${data.image_path ? `<img src="${data.image_path}" class="message-image zoomable-image">` : ''}
            </div>
        `;
            messagesArea.appendChild(msgDiv);
            messagesArea.scrollTop = messagesArea.scrollHeight;
        });
    </script>

</body>

</html>