<?php
include 'includes/db.php';
$result = $conn->query("SELECT title, content, created_at FROM news ORDER BY created_at DESC");
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Tin tức & Thông báo</h2>
    <?php while ($row = $result->fetch_assoc()): ?>
        <article>
            <h3><?= $row['title'] ?></h3>
            <p><?= $row['content'] ?></p>
            <small><?= $row['created_at'] ?></small>
        </article>
        <hr>
    <?php endwhile; ?>
</main>
<?php include 'includes/footer.php'; ?>