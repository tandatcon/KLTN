<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

$pageTitle = "Thông tin cá nhân - TechCare";
include VIEWS_PATH . '/header.php';

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Customer.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('login'));
    exit();
}

require_once __DIR__ . '/../controllers/OrderController.php';
$orderController = new OrderController($db);
$data = $orderController->showOrders();

$userModel = new User($db);
$customerModel = new Customer($db);

// Lấy thông tin khách hàng
$customerInfo = $customerModel->findById($_SESSION['user_id']);

// Xử lý cập nhật thông tin
$updateSuccess = false;
$updateError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validate
    if (empty($name)) {
        $updateError = "Họ tên không được để trống!";
    } elseif (empty($phone)) {
        $updateError = "Số điện thoại không được để trống!";
    } else {
        try {
            $result = $customerModel->updateCustomerProfile(
                $_SESSION['user_id'],
                $name,
                $phone,
                $email,
                $address
            );

            if ($result) {
                $updateSuccess = true;
                $customerInfo = $customerModel->findById($_SESSION['user_id']);
                // Cập nhật session name nếu cần
                $_SESSION['user_name'] = $name;
            } else {
                $updateError = "Cập nhật thông tin thất bại!";
            }
        } catch (Exception $e) {
            $updateError = "Lỗi hệ thống: " . $e->getMessage();
        }
    }
}

// Hàm helper để tránh lỗi deprecated
function safe_htmlspecialchars($value)
{
    return $value !== null ? htmlspecialchars($value) : '';
}
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-primary mb-3">
                        <i class="fas fa-user-circle me-2"></i>Thông tin cá nhân
                    </h1>
                    <p class="lead text-muted">Quản lý và cập nhật thông tin tài khoản</p>
                </div>

                <!-- THÔNG BÁO -->
                <?php if ($updateSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Thành công!</strong> Cập nhật thông tin thành công!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($updateError): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Lỗi!</strong> <?php echo safe_htmlspecialchars($updateError); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row g-4">
                    <!-- MAIN CONTENT -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <form method="POST">
                                    <!-- Thông tin cơ bản -->
                                    <div class="mb-5">
                                        <h4 class="text-primary mb-4">
                                            <i class="fas fa-id-card me-2"></i>Thông tin cơ bản
                                        </h4>

                                        <!-- Avatar Section -->
                                        <div class="text-center mb-4 p-4 bg-light rounded">
                                            <div class="d-inline-block position-relative">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                                     style="width: 100px; height: 100px;">
                                                    <span class="text-white display-4">👤</span>
                                                </div>
                                                <p class="text-muted mb-0">Avatar</p>
                                            </div>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="name" class="form-label fw-semibold">
                                                    Họ và tên <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control form-control-lg" id="name" name="name"
                                                    value="<?php echo safe_htmlspecialchars($customerInfo['name'] ?? ''); ?>" 
                                                    required placeholder="Nhập họ và tên">
                                                <?php if (empty($customerInfo['name'])): ?>
                                                    <div class="form-text text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Thông tin cần được cập nhật
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="phone" class="form-label fw-semibold">
                                                    Số điện thoại <span class="text-danger">*</span>
                                                </label>
                                                <input type="tel" class="form-control form-control-lg" id="phone" name="phone"
                                                    value="<?php echo safe_htmlspecialchars($customerInfo['phone'] ?? ''); ?>" 
                                                    required placeholder="Nhập số điện thoại">
                                                <?php if (empty($customerInfo['phone'])): ?>
                                                    <div class="form-text text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Thông tin cần được cập nhật
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thông tin liên hệ -->
                                    <div class="mb-4">
                                        <h4 class="text-primary mb-4">
                                            <i class="fas fa-envelope me-2"></i>Thông tin liên hệ
                                        </h4>

                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="email" class="form-label fw-semibold">Email</label>
                                                <input type="email" class="form-control form-control-lg" id="email" name="email"
                                                    value="<?php echo safe_htmlspecialchars($customerInfo['email'] ?? ''); ?>"
                                                    placeholder="Nhập địa chỉ email">
                                                <?php if (empty($customerInfo['email'])): ?>
                                                    <div class="form-text text-info">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Thêm email để nhận thông báo
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-12">
                                                <label for="address" class="form-label fw-semibold">Địa chỉ</label>
                                                <textarea class="form-control" id="address" name="address" rows="3"
                                                    placeholder="Nhập địa chỉ cụ thể (số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố)"><?php echo safe_htmlspecialchars($customerInfo['address'] ?? ''); ?></textarea>
                                                <?php if (empty($customerInfo['address'])): ?>
                                                    <div class="form-text text-info">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Thêm địa chỉ để thuận tiện cho dịch vụ tại nhà
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-3 pt-4 border-top">
                                        <button type="submit" name="update_profile" class="btn btn-primary btn-lg px-4">
                                            <i class="fas fa-save me-2"></i>Cập nhật thông tin
                                        </button>
                                        <a href="<?php echo url('home'); ?>" class="btn btn-outline-secondary btn-lg px-4">
                                            <i class="fas fa-times me-2"></i>Hủy bỏ
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- SIDEBAR -->
                    <div class="col-lg-4">
                        <!-- Thông tin tài khoản -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-4">
                                    <i class="fas fa-info-circle me-2"></i>Thông tin tài khoản
                                </h5>
                                <div class="space-y-3">
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <strong class="text-muted">Mã khách hàng:</strong>
                                        <span class="fw-bold text-dark">KH<?php echo str_pad($customerInfo['id'] ?? '000', 4, '0', STR_PAD_LEFT); ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <strong class="text-muted">Ngày tham gia:</strong>
                                        <span class="text-dark"><?php echo !empty($customerInfo['created_at']) ? date('d/m/Y', strtotime($customerInfo['created_at'])) : 'Chưa cập nhật'; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <strong class="text-muted">Trạng thái:</strong>
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Thống kê -->
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title text-primary mb-4">
                                    <i class="fas fa-chart-bar me-2"></i>Thống kê
                                </h5>
                                <div class="space-y-3">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-clipboard-list text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark">Đơn dịch vụ</strong>
                                            <span class="text-muted"><?php $orders = $data['orders']; echo count($orders); ?> Đã đặt</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Có thể thêm các thống kê khác sau này -->
                                    <!--
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark">Đã hoàn thành</strong>
                                            <span class="text-muted">12 đơn</span>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-clock text-white"></i>
                                        </div>
                                        <div>
                                            <strong class="d-block text-dark">Đang xử lý</strong>
                                            <span class="text-muted">3 đơn</span>
                                        </div>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
    /* Custom styles để bổ sung cho Bootstrap */
    .bg-light {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
    }
    
    .card {
        border-radius: 15px;
        border: none;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #2980b9, #2471a3);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
    }
    
    .btn-outline-secondary:hover {
        transform: translateY(-2px);
    }
    
    .space-y-3 > * + * {
        margin-top: 1rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .d-flex.gap-3 {
            flex-direction: column;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>