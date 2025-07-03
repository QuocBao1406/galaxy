<?php
session_start();
session_unset();  // Xoá tất cả biến session
session_destroy(); // Huỷ phiên làm việc

header("Location: ./TAIKHOAN/login-register.html");
exit();
?>
