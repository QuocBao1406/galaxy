<?php
require_once 'check_admin.php';

if (isset($_POST['save_news'])) {
    // Lấy dữ liệu từ các trường song ngữ
    $title_vi = $_POST['title_vi'];
    $category_vi = $_POST['category_vi'];
    $excerpt_vi = $_POST['excerpt_vi'];
    $full_content_vi = $_POST['full_content_vi'];
    $title_en = $_POST['title_en'];
    $category_en = $_POST['category_en'];
    $excerpt_en = $_POST['excerpt_en'];
    $full_content_en = $_POST['full_content_en'];

    // Trường chung
    $image_url = $_POST['current_image'] ?? ''; 

    // Xử lý upload ảnh (giữ nguyên)
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir_physical = '../uploads/news/'; 
        if (!is_dir($upload_dir_physical)) mkdir($upload_dir_physical, 0775, true);
        if (!empty($image_url) && file_exists('..' . $image_url)) unlink('..' . $image_url);
        $file_name = time() . '_' . basename($_FILES['featured_image']['name']);
        $target_file_physical = $upload_dir_physical . $file_name;
        if (move_uploaded_file($_FILES['featured_image']['tmp_name'], $target_file_physical)) {
            $image_url = '/galaxy/uploads/news/' . $file_name; 
        }
    }

    if (isset($_POST['id'])) { // SỬA
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("UPDATE news SET title_vi=?, category_vi=?, excerpt_vi=?, full_content_vi=?, title_en=?, category_en=?, excerpt_en=?, full_content_en=?, image_url=? WHERE id=?");
        $stmt->bind_param("sssssssssi", $title_vi, $category_vi, $excerpt_vi, $full_content_vi, $title_en, $category_en, $excerpt_en, $full_content_en, $image_url, $id);
    } else { // THÊM
        $stmt = $conn->prepare("INSERT INTO news (title_vi, category_vi, excerpt_vi, full_content_vi, title_en, category_en, excerpt_en, full_content_en, image_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $title_vi, $category_vi, $excerpt_vi, $full_content_vi, $title_en, $category_en, $excerpt_en, $full_content_en, $image_url);
    }

    $stmt->execute();
    header("Location: news_management.php");
    exit();
}
?>