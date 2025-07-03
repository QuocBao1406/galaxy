<?php
require_once 'check_admin.php';
include 'header.php';

// Xử lý tìm kiếm
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT p.id, p.content, p.created_at, u.username
        FROM posts p
        JOIN users u ON p.user_id = u.id";

if (!empty($search_term)) {
    // Tìm kiếm trong nội dung bài đăng hoặc tên người dùng
    $sql .= " WHERE p.content LIKE ? OR u.username LIKE ?";
    $search_like = "%" . $search_term . "%";
}

$sql .= " ORDER BY p.created_at DESC";
$stmt = $conn->prepare($sql);

if (!empty($search_term)) {
    $stmt->bind_param("ss", $search_like, $search_like);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Bài đăng Cộng đồng</h1>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="community_management.php" method="GET" class="form-inline">
            <input type="text" name="search" class="form-control mr-2" placeholder="Tìm theo nội dung, username..." value="<?= htmlspecialchars($search_term) ?>">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
</div>


<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Người đăng</th>
                <th>Nội dung</th>
                <th>Ngày đăng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($post = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $post['id'] ?></td>
                <td><?= htmlspecialchars($post['username']) ?></td>
                <td><?= nl2br(htmlspecialchars(substr($post['content'], 0, 150))) ?>...</td>
                <td><?= $post['created_at'] ?></td>
                <td>
                    <a href="view_post.php?id=<?= $post['id'] ?>" class="btn btn-info btn-sm">Xem</a>
                    <a href="delete_community_post.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xóa bài đăng này sẽ xóa toàn bộ dữ liệu liên quan. Bạn chắc chắn?');">Xóa</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php
$stmt->close();
include 'footer.php';
?>