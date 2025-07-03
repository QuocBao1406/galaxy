<?php
require_once 'check_admin.php';
include 'header.php';

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
// Lấy tiêu đề tiếng Việt để hiển thị trong danh sách
$sql = "SELECT id, title_vi, category_vi, image_url FROM news";
if (!empty($search_term)) {
    // Tìm kiếm trên cả hai cột tiêu đề
    $sql .= " WHERE title_vi LIKE ? OR title_en LIKE ?";
    $search_like = "%" . $search_term . "%";
}
$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);
if (!empty($search_term)) {
    $stmt->bind_param("ss", $search_like, $search_like);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Tin tức</h1>
    <a href="news_form.php" class="btn btn-success">Thêm Mới</a>
</div>

<form method="GET" class="form-inline mb-3">
    <input type="text" name="search" class="form-control mr-2" placeholder="Tìm theo tiêu đề (Việt hoặc Anh)..." value="<?= htmlspecialchars($search_term) ?>">
    <button type="submit" class="btn btn-primary">Tìm</button>
</form>

<table class="table table-hover">
    <thead class="thead-light">
        <tr><th>Ảnh</th><th>Tiêu đề (Tiếng Việt)</th><th>Thể loại (Tiếng Việt)</th><th>Hành động</th></tr>
    </thead>
    <tbody>
    <?php while($row = $result->fetch_assoc()):
    // Dùng trực tiếp đường dẫn từ database, không thêm ../
    $image_path = !empty($row['image_url']) ? $row['image_url'] : '/galaxy/assets/images/default-news.jpg';
?>
        <tr>
            <td><img src="<?= htmlspecialchars($image_path) ?>" height="50" width="80" style="object-fit: cover;"></td>
            <td><?= htmlspecialchars($row['title_vi']) ?></td>
            <td><?= htmlspecialchars($row['category_vi']) ?></td>
            <td>
                <a href="news_form.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Sửa</a>
                <a href="delete_news.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Chắc chắn xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>