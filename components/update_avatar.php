<?php
session_start();
include("db.php");

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Đã có lỗi không xác định xảy ra.'];
$webRootPrefix = "/galaxy"; // <<<< QUAN TRỌNG: Tiền tố thư mục gốc web của bạn. Nếu web ở gốc domain, để trống ''

if (!isset($_SESSION['username'])) {
    $response['message'] = 'Chưa đăng nhập';
    echo json_encode($response);
    exit();
}

$username_session = $_SESSION['username'];

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    // Đường dẫn tuyệt đối trên server đến thư mục uploads
    $uploadDirAbsolute = $_SERVER['DOCUMENT_ROOT'] . $webRootPrefix . "/uploads/";

    if (!file_exists($uploadDirAbsolute)) {
        if (!mkdir($uploadDirAbsolute, 0775, true)) {
            $response['message'] = 'Lỗi: Không thể tạo thư mục upload tại: ' . $uploadDirAbsolute;
            echo json_encode($response);
            exit();
        }
    }
    if (!is_writable($uploadDirAbsolute)) {
        $response['message'] = 'Lỗi: Thư mục upload không có quyền ghi: ' . $uploadDirAbsolute;
        echo json_encode($response);
        exit();
    }

    // Xóa ảnh cũ
    $sql_get_old_avatar = "SELECT avatar FROM users WHERE username = ?";
    $stmt_old = $conn->prepare($sql_get_old_avatar);
    if ($stmt_old) {
        $stmt_old->bind_param("s", $username_session);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        if ($old_user_data = $result_old->fetch_assoc()) {
            if (!empty($old_user_data['avatar'])) {
                $oldAvatarPathOnServer = $_SERVER['DOCUMENT_ROOT'] . $old_user_data['avatar']; // Avatar lưu đường dẫn tuyệt đối từ gốc domain
                if (file_exists($oldAvatarPathOnServer) && strpos($old_user_data['avatar'], 'default-avatar.png') === false) {
                    unlink($oldAvatarPathOnServer);
                }
            }
        }
        $stmt_old->close();
    }

    $fileTmpPath = $_FILES['avatar']['tmp_name'];
    $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileExtension, $allowedExtensions)) {
        $response['message'] = 'Định dạng file không hợp lệ. Chỉ chấp nhận JPG, JPEG, PNG, GIF.';
        echo json_encode($response);
        exit();
    }
    if ($_FILES['avatar']['size'] > 5 * 1024 * 1024) { // Max 5MB
        $response['message'] = 'Kích thước file quá lớn. Tối đa 5MB.';
        echo json_encode($response);
        exit();
    }

    $newFileName = uniqid('avatar_', true) . "." . $fileExtension;
    $destinationFileAbsolute = $uploadDirAbsolute . $newFileName;
    
    // Đường dẫn web (tuyệt đối từ gốc domain) để lưu vào CSDL và trả về client
    $webPathToAvatarForDB = $webRootPrefix . "/uploads/" . $newFileName;

    if (move_uploaded_file($fileTmpPath, $destinationFileAbsolute)) {
        $sql_update = "UPDATE users SET avatar = ? WHERE username = ?";
        $stmt_update = $conn->prepare($sql_update);
        if ($stmt_update) {
            $stmt_update->bind_param("ss", $webPathToAvatarForDB, $username_session);
            if ($stmt_update->execute()) {
                $response = ['success' => true, 'newAvatarUrl' => $webPathToAvatarForDB, 'message' => 'Cập nhật ảnh đại diện thành công!'];
            } else {
                $response['message'] = 'Lỗi cập nhật CSDL (execute): ' . $stmt_update->error;
            }
            $stmt_update->close();
        } else {
            $response['message'] = 'Lỗi chuẩn bị SQL (update avatar): ' . $conn->error;
        }
    } else {
        $upload_error_code = $_FILES['avatar']['error'];
        $response['message'] = "Không thể di chuyển file đã tải lên. Mã lỗi PHP: $upload_error_code.";
    }
} else {
    $error_code = isset($_FILES['avatar']['error']) ? $_FILES['avatar']['error'] : 'Không có file';
    $error_message_switch = "Không có ảnh hợp lệ được tải lên.";
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:   $error_message_switch = "File quá lớn (theo cấu hình server)."; break;
        case UPLOAD_ERR_FORM_SIZE:  $error_message_switch = "File quá lớn (theo form HTML)."; break;
        // ... các case lỗi khác ...
        case UPLOAD_ERR_NO_FILE:    $error_message_switch = "Không có file nào được tải lên."; break;
        default: $error_message_switch = "Lỗi không xác định khi upload."; break;
    }
    $response['message'] = $error_message_switch . " (Mã lỗi PHP: $error_code)";
}

echo json_encode($response);
if(isset($conn)) $conn->close();
exit();
?>