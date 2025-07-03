<?php
require_once 'check_admin.php';
include 'header.php';

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
// Lấy cả 2 tiêu đề, nhưng chỉ dùng title_vi để hiển thị trong danh sách
$sql = "SELECT id, title_vi, event_date, image_url FROM events"; 
if (!empty($search_term)) {
    // Tìm kiếm trên cả hai cột tiêu đề tiếng Việt và Anh
    $sql .= " WHERE title_vi LIKE ? OR title_en LIKE ?"; 
    $search_like = "%" . $search_term . "%";
}
$sql .= " ORDER BY event_date DESC";
$stmt = $conn->prepare($sql);
if (!empty($search_term)) {
    $stmt->bind_param("ss", $search_like, $search_like); 
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Sự kiện</h1>
    <a href="event_form.php" class="btn btn-success">Thêm Sự kiện Mới</a>
</div>

<form method="GET" class="form-inline mb-3">
    <input type="text" name="search" class="form-control mr-2" placeholder="Tìm theo tiêu đề (Việt hoặc Anh)..." value="<?= htmlspecialchars($search_term) ?>">
    <button type="submit" class="btn btn-primary">Tìm</button>
</form>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>Ảnh</th>
                <th>Tiêu đề (Tiếng Việt)</th>
                <th>Ngày diễn ra</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()):
    // Dùng trực tiếp đường dẫn từ database, không thêm ../
    $image_path = !empty($row['image_url']) ? $row['image_url'] : '/galaxy/assets/images/default-event.png';
?>
            <tr>
                <td><img src="<?= htmlspecialchars($image_path) ?>" height="50" width="80" style="object-fit: cover;" alt="Event Image"></td>
                <td><?= htmlspecialchars($row['title_vi']) ?></td>
                <td><?= htmlspecialchars($row['event_date'] ? date("d/m/Y", strtotime($row['event_date'])) : 'Chưa xác định') ?></td>
                <td>
                    <a href="event_form.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Sửa</a>
                    <a href="delete_event.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa sự kiện này?');">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>