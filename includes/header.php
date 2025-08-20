    <?php
    // Bắt đầu session nếu chưa được bắt đầu
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Khai báo biến active_page để xác định trang hiện tại
    $active_page = basename($_SERVER['PHP_SELF']);
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo isset($page_title) ? $page_title : 'Quản lý giải đấu Karate'; ?></title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
        <!-- Google Fonts (Montserrat for headings, Roboto for body) -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,500&display=swap" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/logo.png" alt="Logo Karate" height="50">
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?= ($active_page == 'index.php' || $active_page == 'index.html') ? 'active' : '' ?>" href="index.php">
                                <i class="fas fa-home me-1"></i> Trang chủ
                            </a>
                        </li>
                        <?php if (isset($_SESSION['username'])): // Nếu đã đăng nhập ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle me-1"></i> <?= htmlspecialchars($_SESSION['username']) ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                                </ul>
                            </li>
                        <?php else: // Nếu chưa đăng nhập ?>
                            <li class="nav-item">
                                <a class="nav-link <?= ($active_page == 'login.php') ? 'active' : '' ?>" href="login.php">
                                    <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($active_page == 'register.php') ? 'active' : '' ?>" href="register.php">
                                    <i class="fas fa-user-plus me-1"></i> Đăng ký
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($active_page == 'club-match.php') ? 'active' : '' ?>" href="club-match.php">
                                <i class="fas fa-users me-1"></i> Trận đấu CLB
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($active_page == 'ranking.php') ? 'active' : '' ?>" href="ranking.php">
                                <i class="fas fa-medal me-1"></i> Bảng xếp hạng
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($active_page == 'news.php') ? 'active' : '' ?>" href="news.php">
                                <i class="fas fa-newspaper me-1"></i> Tin tức
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div style="padding-top: 80px;"></div> <!-- Khoảng trống để tránh navbar che nội dung -->
        <div class="container">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($_SESSION['message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị ?>
            <?php endif; ?>
        </div>