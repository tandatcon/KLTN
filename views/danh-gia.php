<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ob_start();
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Đánh Giá Dịch Vụ - TechCare";
include VIEWS_PATH . '/header.php';

require_once __DIR__ . '/../function/donhang.php';
$donHangService = new DonHangService($db);

$orderId = $_GET['order_id'] ?? 0;
$maKH = $_SESSION['user_id'] ?? null;

if (!$maKH) {
    header('Location: ' . url('login'));
    exit();
}

if (!$orderId) {
    $_SESSION['error'] = "Đơn hàng không tồn tại!";
    header('Location: ' . url('don-cua-toi'));
    exit();
}

// Lấy thông tin đơn hàng
$order = $donHangService->getOrderDetail($orderId, $maKH);
if (!$order) {
    $_SESSION['error'] = "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!";
    header('Location: ' . url('don-cua-toi'));
    exit();
}

// Kiểm tra đơn hàng đã hoàn thành chưa
if ($order['trangThai'] != 4) {
    $_SESSION['error'] = "Chỉ có thể đánh giá đơn hàng đã hoàn thành!";
    header('Location: ' . url('don-cua-toi'));
    exit();
}

// Kiểm tra đã đánh giá chưa
$existingRating = $donHangService->getRatingByOrder($orderId);
if ($existingRating) {
    $_SESSION['info'] = "Bạn đã đánh giá đơn hàng này trước đó!";
    header('Location: ' . url('don-cua-toi'));
    exit();
}

// Lấy thông tin KTV
$technicianInfo = $donHangService->getTechnicianInfo($order['maKTV']);

// Xử lý form đánh giá
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_rating'])) {
    $diemDanhGia = intval($_POST['rating'] ?? 0);
    $noiDungDanhGia = trim($_POST['comment'] ?? '');
    $tieuChi = $_POST['criteria'] ?? [];
    
    // Validate
    if ($diemDanhGia < 1 || $diemDanhGia > 5) {
        $_SESSION['error'] = "Vui lòng chọn số sao đánh giá từ 1-5!";
    } else {
        // Lưu đánh giá
        $result = $donHangService->saveRating(
            $orderId,
            $maKH,
            $order['maKTV'],
            $diemDanhGia,
            $noiDungDanhGia,
            $tieuChi
        );
        
        if ($result) {
            $_SESSION['success'] = "Cảm ơn bạn đã đánh giá dịch vụ! Đánh giá của bạn đã được ghi nhận.";
            header('Location: ' . url('don-cua-toi'));
            exit();
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi lưu đánh giá. Vui lòng thử lại!";
        }
    }
}

// Xác định các tiêu chí đã được chọn (nếu có) từ POST
$selectedCriteria = $_POST['criteria'] ?? [];

// Tiêu chí đánh giá
$criteriaList = [
    'chuyen_mon' => [
        'icon' => 'fa-graduation-cap',
        'color' => 'primary',
        'title' => 'Chuyên môn tốt',
        'description' => 'Kiến thức chuyên môn vững, giải thích dễ hiểu'
    ],
    'thai_do' => [
        'icon' => 'fa-smile', 
        'color' => 'success',
        'title' => 'Thái độ tốt',
        'description' => 'Thân thiện, nhiệt tình, lịch sự'
    ],
    'dung_gio' => [
        'icon' => 'fa-clock',
        'color' => 'info', 
        'title' => 'Đúng giờ',
        'description' => 'Đến đúng giờ hẹn, hoàn thành nhanh chóng'
    ],
    'hieu_qua' => [
        'icon' => 'fa-bolt',
        'color' => 'warning',
        'title' => 'Hiệu quả cao', 
        'description' => 'Khắc phục triệt để vấn đề, tư vấn hữu ích'
    ]
];
?>

<style>
.rating-section {
    background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
    border-radius: 15px;
    padding: 30px;
    border: 2px solid #ffc107;
}

.star-rating {
    display: inline-block;
}

.star-icon {
    font-size: 3rem;
    cursor: pointer;
    margin: 0 5px;
    transition: all 0.2s ease;
}

.star-icon:hover {
    transform: scale(1.2);
}

.star-icon.active {
    color: #ffc107 !important;
}

.criteria-item {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}

.criteria-item:hover {
    border-color: #ffc107;
    transform: translateY(-2px);
}

.criteria-item.selected {
    border-color: #28a745;
    background: #f8fff9;
}

.order-info-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
}

.btn-submit-rating {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    border: none;
    color: white;
    font-weight: bold;
    padding: 12px 30px;
    font-size: 1.1rem;
    border-radius: 25px;
    transition: all 0.3s ease;
}

.btn-submit-rating:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 152, 0, 0.4);
}

.rating-text {
    font-size: 1.2rem;
    font-weight: 500;
    min-height: 30px;
}
</style>

<main class="bg-light min-vh-100 py-4">
    <div class="container">
        <!-- Header -->
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-lg-10">
                <div class="text-center">
                    <a href="<?php echo url('don-cua-toi'); ?>" class="btn btn-secondary mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <h1 class="display-5 fw-bold text-primary mb-3">
                        <i class="fas fa-star me-3"></i>Đánh Giá Dịch Vụ
                    </h1>
                    <p class="lead text-muted">Chia sẻ trải nghiệm của bạn để chúng tôi có thể cải thiện dịch vụ tốt hơn</p>
                </div>
            </div>
        </div>

        <!-- Thông báo -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="row justify-content-center mb-4">
                <div class="col-12 col-lg-8">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <!-- Thông tin đơn hàng -->
                <div class="card order-info-card shadow-lg border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-2">Đơn hàng #<?php echo $orderId; ?></h4>
                                <p class="text-white mb-1">
                                    <i class="fas fa-calendar me-2"></i>
                                    <?php echo date('d/m/Y', strtotime($order['ngayDat'])); ?>
                                </p>
                                <?php if (!empty($technicianInfo)): ?>
                                <p class="text-white mb-0">
                                    <i class="fas fa-user-cog me-2"></i>
                                    KTV: <strong><?php echo htmlspecialchars($technicianInfo['hoTen']); ?></strong>
                                </p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="bg-white bg-opacity-20 rounded-pill px-3 py-2 d-inline-block">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span class="fw-bold">Đã hoàn thành</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form đánh giá -->
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="" id="ratingForm">
                            <!-- Đánh giá sao - Dùng JavaScript -->
                            <div class="rating-section mb-4">
                                <h4 class="text-center mb-4">
                                    <i class="fas fa-star me-2 text-warning"></i>
                                    Bạn hài lòng với dịch vụ như thế nào?
                                </h4>
                                
                                <div class="text-center mb-3">
                                    <div class="star-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star star-icon" 
                                               data-rating="<?php echo $i; ?>"
                                               onclick="selectRating(<?php echo $i; ?>)"
                                               onmouseover="hoverRating(<?php echo $i; ?>)"
                                               onmouseout="resetRating()"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <input type="hidden" id="ratingValue" name="rating" value="0" required>
                                    <div class="rating-text mt-3">
                                        <span id="ratingMessage" class="text-muted">Chọn số sao để đánh giá</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tiêu chí đánh giá - Dùng PHP -->
                            <div class="mb-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    Theo bạn, KTV có những ưu điểm nào?
                                </h5>
                                <div class="row g-3">
                                    <?php foreach ($criteriaList as $key => $criteria): ?>
                                        <div class="col-md-6">
                                            <div class="criteria-item <?php echo in_array($key, $selectedCriteria) ? 'selected' : ''; ?>">
                                                <div class="form-check mb-0">
                                                    <input type="checkbox" class="form-check-input" 
                                                           name="criteria[]" 
                                                           value="<?php echo $key; ?>" 
                                                           id="criteria_<?php echo $key; ?>"
                                                           <?php echo in_array($key, $selectedCriteria) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label fw-bold mb-2 d-block" for="criteria_<?php echo $key; ?>">
                                                        <i class="fas <?php echo $criteria['icon']; ?> me-2 text-<?php echo $criteria['color']; ?>"></i>
                                                        <?php echo $criteria['title']; ?>
                                                    </label>
                                                    <small class="text-muted"><?php echo $criteria['description']; ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Nhận xét -->
                            <div class="mb-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-comment-dots me-2 text-primary"></i>
                                    Nhận xét chi tiết của bạn
                                </h5>
                                <textarea class="form-control" name="comment" rows="4" 
                                          placeholder="Hãy chia sẻ chi tiết trải nghiệm của bạn với dịch vụ... (Không bắt buộc)"><?php echo htmlspecialchars($_POST['comment'] ?? ''); ?></textarea>
                                <div class="form-text">
                                    Nhận xét của bạn sẽ giúp KTV cải thiện và giúp khách hàng khác có lựa chọn tốt hơn
                                </div>
                            </div>

                            <!-- Nút gửi -->
                            <div class="text-center">
                                <button type="submit" name="submit_rating" class="btn btn-submit-rating btn-lg" id="submitBtn">
                                    <i class="fas fa-paper-plane me-2"></i>GỬI ĐÁNH GIÁ
                                </button>
                                <a href="<?php echo url('don-cua-toi'); ?>" class="btn btn-outline-secondary btn-lg ms-2">
                                    <i class="fas fa-times me-2"></i>HỦY
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Lưu ý -->
                <div class="alert alert-info mt-4">
                    <h6><i class="fas fa-info-circle me-2"></i>Lưu ý:</h6>
                    <ul class="mb-0">
                        <li>Đánh giá của bạn sẽ được hiển thị công khai trên trang thông tin KTV</li>
                        <li>Mỗi đơn hàng chỉ được đánh giá một lần duy nhất</li>
                        <li>Đánh giá giúp KTV cải thiện chất lượng dịch vụ</li>
                        <li>Bạn có thể chọn nhiều ưu điểm của KTV</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let currentRating = 0;
const ratingMessages = {
    1: 'Rất không hài lòng - Dịch vụ cần cải thiện nhiều',
    2: 'Không hài lòng - Có một số vấn đề cần khắc phục',
    3: 'Bình thường - Dịch vụ đạt yêu cầu cơ bản', 
    4: 'Hài lòng - Dịch vụ tốt, đáp ứng mong đợi',
    5: 'Rất hài lòng - Dịch vụ xuất sắc, vượt mong đợi'
};

function selectRating(rating) {
    currentRating = rating;
    document.getElementById('ratingValue').value = rating;
    
    // Cập nhật hiển thị sao
    const stars = document.querySelectorAll('.star-icon');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('active');
            star.style.color = '#ffc107';
        } else {
            star.classList.remove('active');
            star.style.color = '#ddd';
        }
    });
    
    // Cập nhật message
    const messageElement = document.getElementById('ratingMessage');
    messageElement.textContent = ratingMessages[rating];
    messageElement.className = rating >= 4 ? 'text-success fw-bold' : 
                              rating >= 3 ? 'text-warning fw-bold' : 'text-danger fw-bold';
    
    // Cập nhật nút submit
    updateSubmitButton();
}

function hoverRating(rating) {
    const stars = document.querySelectorAll('.star-icon');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.style.color = '#ffc107';
        } else {
            star.style.color = '#ddd';
        }
    });
}

function resetRating() {
    const stars = document.querySelectorAll('.star-icon');
    stars.forEach((star, index) => {
        if (index < currentRating) {
            star.style.color = '#ffc107';
        } else {
            star.style.color = '#ddd';
        }
    });
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    if (currentRating === 0) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>VUI LÒNG CHỌN SỐ SAO';
        submitBtn.style.opacity = '0.6';
    } else {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>GỬI ĐÁNH GIÁ';
        submitBtn.style.opacity = '1';
    }
}

// Xử lý tiêu chí với JavaScript để có trải nghiệm tốt hơn
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo nút submit
    updateSubmitButton();
    
    // Xử lý click tiêu chí
    const criteriaItems = document.querySelectorAll('.criteria-item');
    
    criteriaItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // Ngăn sự kiện click lan đến checkbox
            if (e.target.type !== 'checkbox') {
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
                this.classList.toggle('selected', checkbox.checked);
            }
        });
        
        // Hiệu ứng hover
        item.addEventListener('mouseenter', function() {
            if (!this.classList.contains('selected')) {
                this.style.borderColor = '#ffc107';
                this.style.transform = 'translateY(-2px)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (!this.classList.contains('selected')) {
                this.style.borderColor = '#e9ecef';
                this.style.transform = 'translateY(0)';
            }
        });
    });
    
    // Validate form trước khi gửi
    document.getElementById('ratingForm').addEventListener('submit', function(e) {
        const rating = document.getElementById('ratingValue').value;
        if (rating === '0') {
            e.preventDefault();
            alert('Vui lòng chọn số sao đánh giá trước khi gửi!');
            return false;
        }
    });
});
</script>

<?php include VIEWS_PATH . '/footer.php'; ?>