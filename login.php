<?php
include 'includes/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Lấy thông tin người dùng
        $_SESSION['username'] = $username;
        $_SESSION['can_view_ranking'] = $user['can_view_ranking']; // Lưu quyền vào session
        header("Location: dashboard.php");
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>
<?php include 'includes/header.php'; ?>
<main>
    <h2>Đăng nhập</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng nhập</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</main>
<?php include 'includes/footer.php'; ?>