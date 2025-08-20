<?php
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Chào mừng quản trị viên <?= $_SESSION['username'] ?></h2>
    <ul>
        <li><a href="tournament.php">Quản lý giải đấu</a></li>
        <li><a href="match-result.php">Cập nhật kết quả trận đấu</a></li>
        <li><a href="ranking.php">Xem bảng xếp hạng</a></li>
        <li><a href="news.php">Quản lý tin tức</a></li>
        <li><a href="logout.php">Đăng xuất</a></li>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>