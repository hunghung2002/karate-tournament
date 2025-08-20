<?php
$page_title = 'Trang chủ - Hệ thống quản lý giải đấu Karate';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero container-fluid py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <h1 class="display-4 fw-bold mb-3 text-white">Hệ thống quản lý giải đấu Karate</h1>
                <p class="lead mb-4">Quản lý vận động viên, trận đấu, bảng xếp hạng và tin tức một cách chuyên nghiệp, hiện đại.</p>
                <a href="register.php" class="btn btn-primary btn-lg">Đăng ký ngay <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
            <div class="col-lg-6 text-center">
                <img src="assets/images/logo.png" alt="Karate Hero" class="img-fluid rounded-4 shadow-lg" />
            </div>
        </div>
    </div>
</section>

<!-- Tính năng -->
<section class="features container my-5">
    <h2 class="text-center mb-5">Các tính năng nổi bật</h2>
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Quản lý vận động viên</h3>
                    <p class="card-text">Đăng ký, cập nhật thông tin, theo dõi thành tích và lịch sử thi đấu của từng vận động viên.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-sitemap fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Bảng đấu & Kết quả</h3>
                    <p class="card-text">Tạo bảng đấu tự động, cập nhật kết quả trận đấu nhanh chóng và xem bracket trực quan.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-trophy fa-3x text-primary mb-3"></i>
                    <h3 class="card-title">Bảng xếp hạng</h3>
                    <p class="card-text">Xem thứ hạng vận động viên, câu lạc bộ, thống kê điểm số và trao giải thưởng.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Giải đấu nổi bật -->
<section class="tournaments container my-5">
    <h2 class="text-center mb-5">Giải đấu nổi bật</h2>
    <div class="row g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Karate Open 2025</h4>
                    <p class="card-text">Quốc gia - 120 VĐV</p>
                    <a href="tournament.php?id=1" class="btn btn-secondary">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">CLB Võ Thuật Quận 1</h4>
                    <p class="card-text">Nội bộ - 32 VĐV</p>
                    <a href="tournament.php?id=2" class="btn btn-secondary">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Karate Youth Cup</h4>
                    <p class="card-text">Thiếu niên - 60 VĐV</p>
                    <a href="tournament.php?id=3" class="btn btn-secondary">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">Karate Summer League</h4>
                    <p class="card-text">Mở rộng - 80 VĐV</p>
                    <a href="tournament.php?id=4" class="btn btn-secondary">Xem chi tiết <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA cuối -->
<section class="cta-banner container-fluid py-5">
    <div class="container text-center">
        <h2 class="mb-4">Tham gia ngay giải đấu Karate!</h2>
        <a href="register.php" class="btn btn-primary btn-lg">Đăng ký vận động viên/CLB <i class="fas fa-arrow-right ms-2"></i></a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
