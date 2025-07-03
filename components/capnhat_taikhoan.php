<?php
session_start();
include('db.php');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Đã có lỗi xảy ra.'];
$webRootPrefix = "/galaxy"; // <<<< QUAN TRỌNG: Tiền tố thư mục gốc web của bạn
$defaultAvatarForDisplay = $webRootPrefix . '/assets/images/default-avatar.png'; // <<<< ĐƯỜNG DẪN AVATAR MẶC ĐỊNH CHO WEB

if (!isset($_SESSION['username'])) {
    $response['message'] = 'Chưa đăng nhập';
    echo json_encode($response);
    exit();
}

$current_username_session = $_SESSION['username'];
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $email = isset($data['email']) ? trim($data['email']) : null;
    $fullName = isset($data['fullname']) ? trim($data['fullname']) : null;
    $phoneNumber = isset($data['phone']) ? trim($data['phone']) : null;
    $birthday = isset($data['birthday']) ? $data['birthday'] : null;

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Email không hợp lệ.';
        echo json_encode($response);
        exit();
    }
    if (empty($fullName)) { // Ví dụ validate fullname không rỗng
        $response['message'] = 'Họ và tên không được để trống.';
        echo json_encode($response);
        exit();
    }
    // Thêm validate khác nếu cần

    $sql_check_email = "SELECT id FROM users WHERE email = ? AND username != ?";
    $stmt_check_email = $conn->prepare($sql_check_email);
    if ($stmt_check_email) {
        $stmt_check_email->bind_param("ss", $email, $current_username_session);
        $stmt_check_email->execute();
        $result_check_email = $stmt_check_email->get_result();
        if ($result_check_email->num_rows > 0) {
            $response['message'] = 'Email này đã được sử dụng bởi một tài khoản khác.';
            echo json_encode($response);
            $stmt_check_email->close();
            if(isset($conn)) $conn->close();
            exit();
        }
        $stmt_check_email->close();
    } else {
        $response['message'] = 'Lỗi kiểm tra email: ' . $conn->error;
        echo json_encode($response);
        if(isset($conn)) $conn->close();
        exit();
    }

    $sql = "UPDATE users SET email = ?, fullname = ?, phone = ?, birthday = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $email, $fullName, $phoneNumber, $birthday, $current_username_session);
        if ($stmt->execute()) {
            $sql_select_updated = "SELECT id, username, fullname, email, phone, birthday, avatar FROM users WHERE username = ?";
            $stmt_select = $conn->prepare($sql_select_updated);
            if ($stmt_select) {
                $stmt_select->bind_param("s", $current_username_session);
                $stmt_select->execute();
                $result_updated = $stmt_select->get_result();
                $updated_user_data = $result_updated->fetch_assoc();
                
                // Xử lý avatar trả về: đảm bảo nó là đường dẫn web đúng hoặc ảnh mặc định
                $avatarPathFromServer = $updated_user_data['avatar'];
                $fullServerPathToCheck = $_SERVER['DOCUMENT_ROOT'] . $avatarPathFromServer; // Giả sử avatar đã là /galaxy/uploads...
                if (empty($avatarPathFromServer) || !file_exists($fullServerPathToCheck)) {
                    $updated_user_data['avatar'] = $defaultAvatarForDisplay;
                } else {
                    // Đảm bảo newAvatarUrl từ server đã bao gồm tiền tố thư mục gốc web nếu cần
                     if ($webRootPrefix && strpos($avatarPathFromServer, $webRootPrefix) !== 0) {
                         $updated_user_data['avatar'] = $webRootPrefix . '/' . ltrim($avatarPathFromServer, '/');
                     }
                }

                $response = [
                    'success' => true,
                    'message' => 'Cập nhật thông tin thành công!',
                    'user' => $updated_user_data
                ];
                $stmt_select->close();
            } else {
                 $response['message'] = 'Cập nhật thành công nhưng không thể lấy lại dữ liệu người dùng: ' . $conn->error;
            }
        } else {
            $response['message'] = 'Lỗi khi cập nhật CSDL: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Lỗi chuẩn bị câu lệnh SQL (update user): ' . $conn->error;
    }
} else {
    $response['message'] = 'Dữ liệu không hợp lệ hoặc không có dữ liệu được gửi.';
}

echo json_encode($response);
if(isset($conn)) $conn->close();
exit();
?>