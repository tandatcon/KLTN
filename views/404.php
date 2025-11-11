<?php
$pageTitle = '404 - Page Not Found';
include __DIR__ . '/header.php';
?>

<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 50vh; background: #f8f9fa;">
    <div class="text-center p-4">
        <!-- Error Icon -->
        <div class="mb-3">
            <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
        </div>
        
        <!-- Error Code -->
        <h1 class="fw-bold text-secondary mb-2" style="font-size: 5rem;">404</h1>
        
        <!-- Error Title -->
        <h2 class="h4 text-muted mb-3">Không tìm thấy trang</h2>
        
        <!-- Error Message -->
        <p class="text-muted mb-4">
            Trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển.
        </p>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?php echo url('home'); ?>" class="btn btn-primary px-4">
                <i class="fas fa-home me-1"></i>Trang chủ
            </a>
            <button onclick="history.back()" class="btn btn-outline-secondary px-4">
                <i class="fas fa-arrow-left me-1"></i>Quay lại
            </button>
        </div>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>