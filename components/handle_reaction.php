<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/db.php';

header('Content-Type: application/json');

// --- KIỂM TRA ĐĂNG NHẬP ---
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn phải đăng nhập.']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$post_id = isset($input['post_id']) ? (int)$input['post_id'] : 0;
$reaction_type = isset($input['reaction_type']) ? $input['reaction_type'] : '';
$user_id = $_SESSION['user_id'];

if ($post_id === 0 || empty($reaction_type)) {
    echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ.']);
    exit();
}

// --- XỬ LÝ DATABASE ---
$conn->begin_transaction();
try {
    // 1. Kiểm tra xem người dùng đã thả cảm xúc cho bài này chưa
    $stmt_check = $conn->prepare("SELECT reaction_type FROM reactions WHERE user_id = ? AND post_id = ?");
    $stmt_check->bind_param("ii", $user_id, $post_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    
    if ($result_check->num_rows > 0) {
        $existing_reaction = $result_check->fetch_assoc();
        if ($existing_reaction['reaction_type'] === $reaction_type) {
            // Giống -> Xóa (bỏ thích)
            $stmt_delete = $conn->prepare("DELETE FROM reactions WHERE user_id = ? AND post_id = ?");
            $stmt_delete->bind_param("ii", $user_id, $post_id);
            $stmt_delete->execute();
        } else {
            // Khác -> Cập nhật
            $stmt_update = $conn->prepare("UPDATE reactions SET reaction_type = ? WHERE user_id = ? AND post_id = ?");
            $stmt_update->bind_param("sii", $reaction_type, $user_id, $post_id);
            $stmt_update->execute();
        }
    } else {
        // Chưa có -> Thêm mới
        $stmt_insert = $conn->prepare("INSERT INTO reactions (user_id, post_id, reaction_type) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("iis", $user_id, $post_id, $reaction_type);
        $stmt_insert->execute();
    }

    // 2. Đếm lại tổng số cảm xúc cho bài viết
    $sql_count = "SELECT reaction_type, COUNT(id) as count FROM reactions WHERE post_id = ? GROUP BY reaction_type";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bind_param("i", $post_id);
    $stmt_count->execute();
    $result_count = $stmt_count->get_result();
    
    $counts = [];
    while($row = $result_count->fetch_assoc()){
        $counts[$row['reaction_type']] = $row['count'];
    }

    $conn->commit();
    
    // 3. Trả về kết quả thành công
    echo json_encode(['success' => true, 'counts' => $counts]);

} catch (mysqli_sql_exception $exception) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => 'Lỗi database: ' . $exception->getMessage()]);
}

$conn->close();
?>