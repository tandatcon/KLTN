<?php
$pageTitle = '404 - Page Not Found';
include __DIR__ . '/header.php';
?>

<main class="page-not-found">
    <div class="container">
        <div class="not-found-content">
            <div class="error-animation">
                <div class="error-number">4<span>0</span>4</div>
                <div class="error-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
            
            <h1 class="error-title">Oops! Page Not Found</h1>
            
            <p class="error-message">
            Xin lỗi, chúng tôi không thể tìm thấy trang bạn yêu cầu. <br>
            Có thể đường dẫn đã thay đổi, bị xóa hoặc chưa từng tồn tại.
            </p>
            
            <div class="error-actions">
                <a href="<?php echo url('home'); ?>" class="btn-primary">
                    <i class="fas fa-home"></i> Back to Homepage
                </a>
                
                <button onclick="history.back()" class="btn-outline">
                    <i class="fas fa-arrow-left"></i> Go Back
                </button>
            </div>
            
            
            
            <div class="error-stats">
                <div class="stat-item">
                    <i class="fas fa-clock"></i>
                    <span>Error 404</span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-calendar"></i>
                    <span><?php echo date('F d, Y'); ?></span>
                </div>
                <div class="stat-item">
                    <i class="fas fa-user"></i>
                    <span>Page Not Found</span>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/footer.php'; ?>