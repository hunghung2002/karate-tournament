<?php
include 'includes/db.php';
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validate input
    if (empty($username)) $errors[] = "Tên đăng nhập không được để trống";
    if (empty($password)) $errors[] = "Mật khẩu không được để trống";
    if ($password !== $confirm_password) $errors[] = "Mật khẩu xác nhận không khớp";

    // Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $errors[] = "Tên đăng nhập đã tồn tại";
    }

    // Insert new user
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = "Lỗi khi đăng ký: " . $stmt->error;
        }
    }
}

$page_title = "Đăng ký tài khoản";
include 'includes/header.php';
?>

<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-4 p-lg-5">
                    <h2 class="card-title text-center mb-4">Đăng ký tài khoản</h2>
                    
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        Đăng ký thành công! <a href="login.php">Đăng nhập ngay</a>
                    </div>
                    <?php else: ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2">Đăng ký</button>
                            <a href="login.php" class="btn btn-outline-secondary">Đã có tài khoản? Đăng nhập</a>
                        </div>
                    </form>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
<?php