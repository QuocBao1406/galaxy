<?php
// Import các lớp của PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Nạp các file cần thiết
require '../vendor/autoload.php';
require_once '../db.php';

// <<< SỬA LỖI: Đặt múi giờ cho PHP >>>
date_default_timezone_set('Asia/Ho_Chi_Minh');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id, fullname FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_fullname = $user['fullname'];
        
        // Bây giờ PHP sẽ tạo thời gian hết hạn theo đúng múi giờ Việt Nam
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", time() + 3600); // Hết hạn sau 1 giờ

        $update_stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
        $update_stmt->bind_param("sss", $token, $expires, $email);
        $update_stmt->execute();

        // Gửi mail bằng PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'baominh011006@gmail.com'; // << THAY EMAIL CỦA BẠN
            $mail->Password   = 'fufn qfzd ssgp nbfu'; // << THAY MẬT KHẨU ỨNG DỤNG
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('baominh011006@gmail.com', 'Galaxy Project');
            $mail->addAddress($email, $user_fullname);

            $mail->isHTML(true);
            $mail->Subject = 'Yeu Cau Khoi Phuc Mat Khau - Galaxy';
            $reset_link = "http://localhost/galaxy/TAIKHOAN/reset_password.php?token=" . $token;
            $mail->Body    = "Xin chao <b>$user_fullname</b>,<br><br>Vui long nhan vao link duoi day de dat lai mat khau moi:<br><a href='$reset_link' style='padding:10px 15px; background-color:#007bff; color:white; text-decoration:none; border-radius:5px; display:inline-block; margin-top:10px;'>Dat Lai Mat Khau</a><br><br>Link nay se het han sau 1 gio.<br>Neu ban khong yeu cau dieu nay, vui long bo qua email nay.";
            $mail->send();
        } catch (Exception $e) {
            // Lỗi gửi mail, không làm gì cả
        }
    }
    echo "Nếu email của bạn tồn tại trong hệ thống, một hướng dẫn khôi phục mật khẩu đã được gửi đi.";
    echo "<br><a href='login-register.html'>Quay lại</a>";
}
?>