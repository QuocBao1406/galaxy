<?php
include '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'] ?? null; // Dùng ?? null để không bị lỗi nếu người dùng không nhập
    $birthday = $_POST['birthday'] ?? null;
    $password = $_POST['password'];

    // Kiểm tra các trường bắt buộc
    if (empty($username) || empty($fullname) || empty($email) || empty($password)) {
        die("Tên đăng nhập, họ tên, email và mật khẩu là các trường bắt buộc.");
    }

    // Mã hóa mật khẩu
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra tên đăng nhập đã tồn tại chưa
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "Tên đăng nhập đã tồn tại. Vui lòng chọn tên khác. <a href='login-register.html'>Thử lại</a>";
    } else {
        // Chèn dữ liệu vào bảng
        $stmt = $conn->prepare("INSERT INTO users (username, fullname, email, phone, birthday, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $username, $fullname, $email, $phone, $birthday, $hashed_password);

        if ($stmt->execute()) {
            // Sửa lại link để trỏ về file mới
            echo "Đăng ký thành công. <a href='login-register.html'>Đăng nhập ngay</a>";
        } else {
            echo "Đã xảy ra lỗi: " . $stmt->error;
        }
        $stmt->close();
    }
    $check->close();
    $conn->close();
}
?>