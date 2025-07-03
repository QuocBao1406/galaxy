<?php
require_once 'check_admin.php';

if (isset($_POST['save_event'])) {
    // Lấy dữ liệu từ các trường song ngữ
    $title_vi = $_POST['title_vi'];
    $content_vi = $_POST['content_vi'];
    $title_en = $_POST['title_en'];
    $content_en = $_POST['content_en'];
    
    // Các trường chung
    $event_date = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
    $image_url = $_POST['current_image'] ?? '';

    // Xử lý upload ảnh (giữ nguyên, không thay đổi)
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir_physical = '../uploads/events/';
        if (!is_dir($upload_dir_physical)) {
            mkdir($upload_dir_physical, 0775, true);
        }
        
        if (!empty($image_url) && file_exists('..' . $image_url)) {
            unlink('..' . $image_url);
        }
        
        $file_name = time() . '_' . basename($_FILES['featured_image']['name']);
        $target_file_physical = $upload_dir_physical . $file_name;
        
        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file_physical)) {
            $image_url = '/galaxy/uploads/events/' . $file_name;
        }
    }

    if (isset($_POST['id'])) { // SỬA
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE events SET title_vi=?, content_vi=?, title_en=?, content_en=?, event_date=?, image_url=? WHERE id=?");
        $stmt->bind_param("ssssssi", $title_vi, $content_vi, $title_en, $content_en, $event_date, $image_url, $id);
    } else { // THÊM
        $stmt = $conn->prepare("INSERT INTO events (title_vi, content_vi, title_en, content_en, event_date, image_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $title_vi, $content_vi, $title_en, $content_en, $event_date, $image_url);
    }
    
    if ($stmt->execute()) {
        // Thành công
    } else {
        // Xử lý lỗi
        echo "Lỗi: " . $stmt->error;
        exit();
    }
    
    header("Location: event_management.php");
    exit();
}
?>