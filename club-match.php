<?php
include 'includes/db.php';
$athletes = [];
if (isset($_POST['club'])) {
    $club = $_POST['club'];
    $result = $conn->query("SELECT * FROM athletes WHERE club='$club'");
    while ($row = $result->fetch_assoc()) {
        $athletes[] = $row;
    }
}
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Tổ chức trận đấu nội bộ CLB</h2>
    <form method="post">
        <input type="text" name="club" placeholder="Nhập tên CLB" required>
        <button type="submit">Xem danh sách vận động viên</button>
    </form>
    <?php if ($athletes): ?>
        <form method="post" action="tournament.php">
            <h3>Chọn vận động viên tham gia trận đấu:</h3>
            <?php foreach ($athletes as $a): ?>
                <label>
                    <input type="checkbox" name="athlete_ids[]" value="<?= $a['id'] ?>">
                    <?= $a['name'] ?> (<?= $a['age'] ?> tuổi, <?= $a['weight'] ?>kg)
                </label><br>
            <?php endforeach; ?>
            <button type="submit">Tạo bảng đấu nội bộ</button>
        </form>
    <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>