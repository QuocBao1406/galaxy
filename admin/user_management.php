<?php
require_once 'check_admin.php';
include 'header.php';

// Xử lý tìm kiếm
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
// Lấy TẤT CẢ các cột cần thiết, bao gồm cả cột is_verified mới
$sql = "SELECT id, username, fullname, email, phone, birthday, avatar, role, is_verified FROM users";

if (!empty($search_term)) {
    // Thêm điều kiện WHERE để tìm kiếm
    $sql .= " WHERE username LIKE ? OR fullname LIKE ? OR email LIKE ?";
    $search_like = "%" . $search_term . "%";
}

$sql .= " ORDER BY id DESC";
$stmt = $conn->prepare($sql);

if (!empty($search_term)) {
    // Gắn tham số cho câu lệnh tìm kiếm
    $stmt->bind_param("sss", $search_like, $search_like, $search_like);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Quản lý Người dùng (<a href="index.php">Về Dashboard</a>)</h2>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="user_management.php" method="GET" class="form-inline">
            <input type="text" name="search" class="form-control mr-2" placeholder="Tìm kiếm theo tên, email..." value="<?= htmlspecialchars($search_term) ?>">
            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Ảnh đại diện</th>
                <th>Tên đăng nhập</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Điện thoại</th>
                <th>Ngày sinh</th>
                <th>Quyền</th>
                <th>Tích xanh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()):
                $avatar_path = $user['avatar'] ? $user['avatar'] : '/galaxy/assets/images/default-avatar.png';
            ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td>
                    <img src="<?= htmlspecialchars($avatar_path) ?>" alt="Avatar" width="50" height="50" style="object-fit: cover; border-radius: 50%;">
                </td>
                <td>
                    <?= htmlspecialchars($user['username']) ?>
                    <?php if ($user['is_verified']): ?>
                        <i class="fas fa-check-circle text-primary" title="Tài khoản đã xác minh"></i>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($user['fullname']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['phone']) ?></td>
                <td><?= htmlspecialchars($user['birthday'] ? date("d/m/Y", strtotime($user['birthday'])) : '') ?></td>
                <td><?= htmlspecialchars($user['role']) ?></td>
                <td>
                    <?php if ($user['is_verified']): ?>
                        <a href="toggle_verification.php?id=<?= $user['id'] ?>&action=revoke" class="btn btn-warning btn-sm">Thu hồi</a>
                    <?php else: ?>
                        <a href="toggle_verification.php?id=<?= $user['id'] ?>&action=grant" class="btn btn-info btn-sm">Cấp</a>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn chắc chắn muốn xóa người dùng này?');">Xóa</a>
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