<?php
ob_start();
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}
$pageTitle = "Đánh Giá Của Tôi - TechCare";
include VIEWS_PATH . '/header.php';
require_once __DIR__ . '/../function/donhang.php';
$donHangService = new DonHangService($db);

$maKH = $_SESSION['user_id'] ?? null;
if (!$maKH) {
    header('Location: ' . url('login'));
    exit();
}
$myRatings = $donHangService->getMyRatings($maKH);
?>

<main class="bg-light min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">

                <!-- Tiêu đề + nút quay lại -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <a href="<?php echo url('don-cua-toi'); ?>" class="btn btn-outline-secondary">
                        Quay lại
                    </a>
                    <h3 class="mb-0">
                        Đánh Giá Của Tôi
                    </h3>
                    <div></div>
                </div>

                <!-- Nếu có đánh giá -->
                <?php if (!empty($myRatings)): ?>
                    <?php foreach ($myRatings as $rating): ?>
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">

                                <div class="row">
                                    <!-- Cột trái: nội dung đánh giá -->
                                    <div class="col-md-8">

                                        <!-- Mã đơn + ngày -->
                                        <div class="d-flex justify-content-between mb-2">
                                            <strong class="text-primary">Đơn #<?php echo $rating['maDon']; ?></strong>
                                            <small class="text-muted">
                                                <?php echo date('d/m/Y', strtotime($rating['thoiGianDanhGia'])); ?>
                                            </small>
                                        </div>

                                        <!-- Sao -->
                                        <div class="mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i
                                                    class="fas fa-star <?php echo $i <= $rating['diemDanhGia'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                            <span class="ms-2 fw-bold"><?php echo $rating['diemDanhGia']; ?>/5</span>
                                        </div>

                                        <!-- Nhận xét -->
                                        <?php if (!empty($rating['noiDungDanhGia'])): ?>
                                            <p class="mb-3 text-dark">
                                                "<?php echo nl2br(htmlspecialchars($rating['noiDungDanhGia'])); ?>"
                                            </p>
                                        <?php endif; ?>

                                        <!-- Tiêu chí -->
                                        <?php
                                        $criteria = [];
                                        if ($rating['chuyenMon'] == 1)
                                            $criteria[] = 'Chuyên môn tốt';
                                        if ($rating['thaiDo'] == 1)
                                            $criteria[] = 'Thái độ tốt';
                                        if ($rating['dungGio'] == 1)
                                            $criteria[] = 'Đúng giờ';
                                        if ($rating['hieuQua'] == 1)
                                            $criteria[] = 'Hiệu quả cao';
                                        ?>
                                        <?php if (!empty($criteria)): ?>
                                            <div class="mb-3">
                                                <?php foreach ($criteria as $c): ?>
                                                    <span class="badge bg-success me-1 mb-1"><?php echo $c; ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Thời gian đánh giá -->
                                        <small class="text-muted">
                                            Đánh giá lúc
                                            <?php echo date('H:i, d/m/Y', strtotime($rating['thoiGianDanhGia'])); ?>
                                        </small>

                                    </div>

                                    <!-- Cột phải: thông tin KTV -->
                                    <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                                        <?php if (!empty($rating['hoTenKTV'])): ?>
                                            <div class="avatar bg-primary text-white d-inline-flex align-items-center justify-content-center rounded-circle mb-2"
                                                style="width: 56px; height: 56px; font-size: 1.4rem;">
                                                <?php echo strtoupper(substr($rating['hoTenKTV'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($rating['hoTenKTV']); ?></div>
                                                <small class="text-muted">Kỹ thuật viên</small>
                                            </div>
                                        <?php else: ?>
                                            <small class="text-muted">Chưa có KTV</small>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php else: ?>
                    <!-- Không có đánh giá -->
                    <div class="text-center py-5">
                        <i class="fas fa-star fa-4x text-warning mb-4 opacity-25"></i>
                        <h4 class="text-dark mb-3">Bạn chưa có đánh giá nào</h4>
                        <p class="text-muted mb-4">Khi hoàn tất dịch vụ, bạn có thể để lại đánh giá ở đây</p>
                        <a href="<?php echo url('don-cua-toi'); ?>" class="btn btn-primary">
                            Xem đơn hàng
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</main>

<?php include VIEWS_PATH . '/footer.php'; ?>