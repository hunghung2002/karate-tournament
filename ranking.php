<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['can_view_ranking']) || $_SESSION['can_view_ranking'] != 1) {
    $_SESSION['message'] = "Bạn không có quyền truy cập trang này";
    header("Location: login.php");
    exit();
}

include 'includes/db.php';

// Lấy dữ liệu xếp hạng
$ranking = $conn->query("
    SELECT a.name, a.club, COUNT(m.id) as matches, SUM(CASE WHEN m.result = 'win' THEN 1 ELSE 0 END) as wins,
    SUM(IFNULL(p.points, 0)) as points
    FROM athletes a
    LEFT JOIN matches m ON a.id = m.athlete_id
    LEFT JOIN (
        SELECT athlete_id, SUM(points) as points FROM performance GROUP BY athlete_id
    ) p ON a.id = p.athlete_id
    GROUP BY a.id
    ORDER BY points DESC
");

$page_title = "Bảng xếp hạng";
include 'includes/header.php';
?>

<main class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Bảng xếp hạng vận động viên</h2>
        <div>
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-1"></i> In bảng
            </button>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Tên</th>
                            <th>CLB</th>
                            <th>Số trận</th>
                            <th>Thắng</th>
                            <th>Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($ranking->num_rows > 0): ?>
                            <?php $i = 1; while ($row = $ranking->fetch_assoc()): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['club']) ?></td>
                                <td><?= $row['matches'] ?></td>
                                <td><?= $row['wins'] ?></td>
                                <td><strong><?= $row['points'] ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-4">Chưa có dữ liệu xếp hạng</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="mt-3 text-muted">
        <small>Cập nhật lần cuối: <?= date('d/m/Y H:i') ?></small>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
