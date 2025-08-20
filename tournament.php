<?php
<?php
include 'includes/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['athlete_ids'])) {
    $ids = $_POST['athlete_ids'];
    // Tạo bảng đấu nội bộ (ví dụ: lưu vào bảng matches)
    foreach ($ids as $id) {
        $conn->query("INSERT INTO matches (athlete_id, type) VALUES ('$id', 'club')");
    }
    $msg = "Đã tạo bảng đấu nội bộ cho CLB!";
}
$result = $conn->query("SELECT * FROM matches ORDER BY id DESC");
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Quản lý giải đấu & bảng đấu</h2>
    <?php if (isset($msg)) echo "<p>$msg</p>"; ?>
    <table border="1">
        <tr>
            <th>ID trận</th>
            <th>ID vận động viên</th>
            <th>Loại trận</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['athlete_id'] ?></td>
            <td><?= $row['type'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</main>
<?php include 'includes/footer.php'; ?>