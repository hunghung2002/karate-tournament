<?php
include 'includes/db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $match_id = $_POST['match_id'];
    $result = $_POST['result'];
    $conn->query("UPDATE matches SET result='$result' WHERE id='$match_id'");
    $msg = "Đã cập nhật kết quả!";
}
$matches = $conn->query("SELECT * FROM matches");
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Cập nhật kết quả trận đấu</h2>
    <?php if (isset($msg)) echo "<p>$msg</p>"; ?>
    <form method="post">
        <select name="match_id" required>
            <?php while ($row = $matches->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>">Trận <?= $row['id'] ?> - VĐV <?= $row['athlete_id'] ?></option>
            <?php endwhile; ?>
        </select>
        <input type="text" name="result" placeholder="Kết quả" required>
        <button type="submit">Cập nhật</button>
    </form>
</main>
<?php include 'includes/footer.php'; ?>