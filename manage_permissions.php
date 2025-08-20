<?php
include 'includes/db.php';
session_start();

// Chỉ cho phép admin truy cập trang này (ví dụ: admin có username 'admin')
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['permission'])) {
    $user_id = $_POST['user_id'];
    $permission = $_POST['permission']; // 0 hoặc 1

    $stmt = $conn->prepare("UPDATE users SET can_view_ranking = ? WHERE id = ?");
    $stmt->bind_param("ii", $permission, $user_id);

    if ($stmt->execute()) {
        $msg = "Cập nhật quyền thành công!";
    } else {
        $msg = "Lỗi: " . $conn->error;
    }
    $stmt->close();
}

$users = $conn->query("SELECT id, username, can_view_ranking FROM users WHERE username != 'admin'"); // Không hiển thị admin
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Quản lý quyền xem bảng xếp hạng</h2>
    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
    <table border="1">
        <tr>
            <th>Tên đăng nhập</th>
            <th>Quyền xem bảng xếp hạng</th>
            <th>Hành động</th>
        </tr>
        <?php while ($row = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $row['username'] ?></td>
            <td><?= $row['can_view_ranking'] == 1 ? 'Được phép' : 'Không được phép' ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <?php if ($row['can_view_ranking'] == 0): ?>
                        <input type="hidden" name="permission" value="1">
                        <button type="submit">Cấp quyền</button>
                    <?php else: ?>
                        <input type="hidden" name="permission" value="0">
                        <button type="submit">Thu hồi quyền</button>
                    <?php endif; ?>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="dashboard.php">Quay lại Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
