<?php
require_once 'check_admin.php';
$id = (int)$_GET['id'];

// Lấy đường dẫn ảnh để xóa file
$stmt = $conn->prepare("SELECT image_url FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($row = $result->fetch_assoc()){
    if(!empty($row['image_url']) && file_exists('..' . $row['image_url'])) {
        unlink('..' . $row['image_url']);
    }
}
$stmt->close();

// Xóa bản ghi trong CSDL
$stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
header("Location: news_management.php");
?>