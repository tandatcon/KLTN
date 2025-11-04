<?php



error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Đăng ký dịch vụ - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../controllers/BookingController.php';
require_once __DIR__ . '/../../models/Customer.php';

$bookingController = new BookingController($db);
$customerModel = new Customer($db);

$data = $bookingController->showBookingPage();
$devices = $data['devices'];

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}

// Xử lý tìm kiếm khách hàng
$customerInfo = null;
$searchPerformed = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_customer'])) {
    unset($_SESSION['customer_info']);
    $phone = trim($_POST['customer_phone']);
    $searchPerformed = true;
    
    if (!empty($phone)) {
        $customerInfo = $customerModel->findByPhone($phone);
        
        if ($customerInfo) {
            // Lưu thông tin khách hàng vào session để tự động điền form
            $_SESSION['customer_info'] = $customerInfo;
        }
    }
}

// Xử lý khi có customer_info trong session (sau khi tìm thấy khách hàng)
if (isset($_SESSION['customer_info'])) {
    $customerInfo = $_SESSION['customer_info'];
}

// Hàm helper để tránh lỗi deprecated
function safe_htmlspecialchars($value) {
    return $value !== null ? htmlspecialchars($value) : '';
}
?>

<section class="booking-section">
    <div class="container">
        <div class="booking-header">
            <h1 class="section-title">
                <i class="fas fa-user-plus"></i> Đăng ký dịch vụ cho khách hàng
            </h1>
            <p class="section-subtitle">Tìm kiếm khách hàng và đăng ký dịch vụ sửa chữa</p>
            
            <!-- <div class="employee-notice">
                <div class="notice-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="notice-content">
                    <strong>Chế độ nhân viên</strong>
                    <span>Nhập số điện thoại để tìm hoặc tạo mới khách hàng</span>
                </div>
            </div> -->
        </div>
        <?php
             if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif;
        
        ?>
        <!-- BƯỚC 1: TÌM KIẾM KHÁCH HÀNG -->
        <div class="customer-search-section">
            <div class="search-card">
                <h3><i class="fas fa-search"></i> Tìm kiếm khách hàng</h3>
                
                <form method="POST" class="search-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="customer_phone">Số điện thoại khách hàng *</label>
                            <input type="tel" id="customer_phone" name="customer_phone" 
                                   value="<?php echo isset($_POST['customer_phone']) ? safe_htmlspecialchars($_POST['customer_phone']) : ''; ?>" 
                                   required placeholder="Nhập số điện thoại (10-11 số)"
                                   pattern="[0-9]{10,11}">
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" name="search_customer" class="btn-search">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                        </div>
                    </div>
                </form>

                <!-- HIỂN THỊ KẾT QUẢ TÌM KIẾM -->
                <?php if ($searchPerformed): ?>
                    <?php if ($customerInfo): ?>
                        <!-- KHÁCH HÀNG CŨ -->
                        <div class="customer-found">
                            <div class="customer-header">
                                <i class="fas fa-check-circle success-icon"></i>
                                <h4>Đã tìm thấy khách hàng</h4>
                            </div>
                            <div class="customer-details">
                                <div class="detail-item">
                                    <strong>Họ tên:</strong>
                                    <span><?php echo safe_htmlspecialchars($customerInfo['name']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <strong>SĐT:</strong>
                                    <span><?php echo safe_htmlspecialchars($customerInfo['phone']); ?></span>
                                </div>
                                <?php if (!empty($customerInfo['email'])): ?>
                                <div class="detail-item">
                                    <strong>Email:</strong>
                                    <span><?php echo safe_htmlspecialchars($customerInfo['email']); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="detail-item">
                                    <strong>Địa chỉ:</strong>
                                    <span><?php echo !empty($customerInfo['address']) ? safe_htmlspecialchars($customerInfo['address']) : 'Chưa có địa chỉ'; ?></span>
                                </div>
                            </div>
                            <div class="success-message">
                                <i class="fas fa-info-circle"></i>
                                <span>Thông tin khách hàng đã được tự động điền vào form bên dưới</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- KHÁCH HÀNG MỚI -->
                        <div class="customer-not-found">
                            <div class="customer-header">
                                <i class="fas fa-user-plus info-icon"></i>
                                <h4>Khách hàng mới</h4>
                            </div>
                            <p>Số điện thoại <strong><?php echo safe_htmlspecialchars($_POST['customer_phone']); ?></strong> chưa có trong hệ thống.</p>
                            <div class="action-links">
                                <a href="<?php echo url('employee/themKH?phone=' . urlencode($_POST['customer_phone'])); ?>" class="btn-add-customer">
                                    <i class="fas fa-user-plus"></i> Thêm khách hàng mới
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- BƯỚC 2: FORM ĐĂNG KÝ DỊCH VỤ -->
        <div class="booking-content" id="bookingFormSection" style="<?php echo (!$customerInfo && $searchPerformed) ? 'display: none;' : ''; ?>">
            <div class="booking-form-container">
                <form class="booking-form" id="serviceBookingForm" action="<?php echo url('employee/process_employee_booking'); ?>" method="POST">
                    <!-- THÊM TRƯỜNG ẨN ĐỂ LƯU ID KHÁCH HÀNG -->
                    <input type="hidden" name="customer_id" value="<?php echo $customerInfo ? $customerInfo['id'] : ''; ?>">
                    <input type="hidden" name="customer_phone" value="<?php echo isset($_POST['customer_phone']) ? safe_htmlspecialchars($_POST['customer_phone']) : ''; ?>">

                    <!-- THÔNG TIN KHÁCH HÀNG -->
                    <div class="form-section-group">
                        <h3 class="form-section-title">
                            <i class="fas fa-user"></i> Thông tin khách hàng
                            <?php if ($customerInfo): ?>
                                <span class="badge-existing">ĐÃ TÌM THẤY</span>
                            <?php else: ?>
                                <span class="badge-new">KHÁCH VÃNG LAI</span>
                            <?php endif; ?>
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="customer_name">Họ và tên khách hàng *</label>
                                <input type="text" id="customer_name" name="customer_name" 
                                       value="<?php echo $customerInfo ? safe_htmlspecialchars($customerInfo['name']) : ''; ?>" 
                                       required placeholder="Nhập họ và tên khách hàng"
                                       <?php echo $customerInfo ? 'readonly' : ''; ?>>
                            </div>
                            
                            <div class="form-group">
                                <label for="customer_phone_display">Số điện thoại *</label>
                                <input type="text" id="customer_phone_display" 
                                       value="<?php echo isset($_POST['customer_phone']) ? safe_htmlspecialchars($_POST['customer_phone']) : ''; ?>" 
                                       disabled class="disabled-field">
                                <small>Số điện thoại đã được xác nhận</small>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="customer_email">Email (tuỳ chọn)</label>
                                <input type="email" id="customer_email" name="customer_email" 
                                       value="<?php echo $customerInfo ? safe_htmlspecialchars($customerInfo['email'] ?? '') : ''; ?>" 
                                       placeholder="Nhập email khách hàng"
                                       <?php echo $customerInfo ? 'readonly' : ''; ?>>
                            </div>
                            
                            <div class="form-group">
                                <label for="service_type">Hình thức dịch vụ *</label>
                                <select id="service_type" name="service_type" required>
                                    <option value="1" selected>Tại cửa hàng</option>
                                    
                                </select>
                            </div>
                        </div>
                        
                        <?php if (!$customerInfo): ?>
                        <div class="new-customer-notice">
                            <i class="fas fa-info-circle"></i>
                            <span>Khách hàng chưa có trong hệ thống. Vui lòng thêm khách hàng trước khi đăng ký dịch vụ.</span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- PHẦN THIẾT BỊ VÀ DỊCH VỤ -->
                    <div class="form-section-group">
                        <h3 class="form-section-title">
                            <i class="fas fa-tools"></i> Thiết bị cần sửa chữa
                        </h3>
                        
                        <div class="devices-container">
                            <!-- Thiết bị 1 -->
                            <div class="device-item" data-device-index="1">
                                <div class="device-header">
                                    <h4>Thiết bị 1</h4>
                                    <button type="button" class="btn-remove-device" style="display: none;">
                                        <i class="fas fa-times"></i> Xóa
                                    </button>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="device_type_1">Loại thiết bị *</label>
                                        <select id="device_type_1" name="device_types[]" class="device-type-select" required>
                                            <option value="">Chọn loại thiết bị</option>
                                            <?php foreach ($devices as $device): ?>
                                                <option value="<?php echo $device['maThietBi']; ?>">
                                                    <?php echo safe_htmlspecialchars($device['tenThietBi']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="device_model_1">Thông tin thiết bị</label>
                                        <input type="text" id="device_model_1" name="device_models[]" 
                                               placeholder="Model, hãng sản xuất... (tuỳ chọn)">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="device_problem_1">Mô tả tình trạng hư hỏng *</label>
                                    <textarea id="device_problem_1" name="device_problems[]" required rows="3"
                                              placeholder="Mô tả chi tiết tình trạng hư hỏng của thiết bị..."></textarea>
                                </div>
                            </div>
                            
                            <!-- Các thiết bị khác sẽ được thêm vào đây -->
                            <div id="additional-devices"></div>
                            
                            <!-- Nút thêm thiết bị -->
                            <div class="add-device-section">
                                <button type="button" id="btn-add-device" class="btn-add-device">
                                    <i class="fas fa-plus"></i> Thêm thiết bị khác
                                </button>
                                <p class="form-note">
                                    <i class="fas fa-info-circle"></i> Tối đa 5 thiết bị mỗi đơn
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Thời gian đặt lịch -->
                    <div class="form-section-group">
                        <h3 class="form-section-title">
                            <i class="fas fa-calendar-alt"></i> Thời gian đặt lịch
                        </h3>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="booking_date">Ngày tiếp nhận *</label>
                                <input type="date" id="booking_date" name="booking_date" required
                                       value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="booking_time">Khung giờ *</label>
                                <select id="booking_time" name="booking_time" required>
                                    <option value="">Chọn khung giờ</option>
                                    <option value="sang">Sáng (8:00 - 11:00)</option>
                                    <option value="chieu">Chiều (13:00 - 17:00)</option>
                                    <option value="toi">Tối (18:00 - 21:00)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin bổ sung -->
                    <div class="form-section-group">
                        <h3 class="form-section-title">
                            <i class="fas fa-comment-dots"></i> Thông tin bổ sung
                        </h3>
                        
                        <div class="form-group">
                            <label for="problem_description">Ghi chú thêm (tuỳ chọn)</label>
                            <textarea id="problem_description" name="problem_description" rows="4"
                                      placeholder="Các yêu cầu đặc biệt, ghi chú khác..."></textarea>
                        </div>
                    </div>

                    <!-- Nút đăng ký -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit-booking" <?php echo !$customerInfo ? 'disabled' : ''; ?>>
                            <i class="fas fa-save"></i> 
                            Đăng ký dịch vụ
                        </button>
                        <p class="form-note">
                            <i class="fas fa-info-circle"></i>
                            <?php if (!$customerInfo): ?>
                                Vui lòng thêm khách hàng trước khi đăng ký dịch vụ.
                            <?php else: ?>
                                Thông tin khách hàng đã được xác nhận từ hệ thống.
                            <?php endif; ?>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
/* CSS CHÍNH - ĐÃ TỐI ƯU VÀ SỬA LỖI */
.booking-section {
    padding: 40px 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

.booking-header {
    text-align: center;
    margin-bottom: 30px;
}

.section-title {
    font-size: 2rem;
    color: #2c3e50;
    margin-bottom: 10px;
    font-weight: 700;
}

.section-subtitle {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 20px;
}

.employee-notice {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin: 20px auto;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    max-width: 800px;
}

.notice-icon {
    font-size: 1.5rem;
    opacity: 0.9;
}

.notice-content strong {
    display: block;
    font-size: 1.1rem;
    margin-bottom: 5px;
}

/* PHẦN TÌM KIẾM KHÁCH HÀNG */
.customer-search-section {
    margin-bottom: 30px;
}

.search-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    border: 1px solid #e0e0e0;
    max-width: 800px;
    margin-left: auto;
    margin-right: auto;
}

.search-card h3 {
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.3rem;
}

.search-form .form-row {
    display: flex;
    gap: 15px;
    align-items: flex-end;
    flex-wrap: wrap;
}

.search-form .form-group {
    flex: 1;
    min-width: 250px;
    margin-bottom: 0;
}

.search-form .form-group:last-child {
    flex: 0 0 auto;
    min-width: 140px;
}

.btn-search {
    background: #3498db;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    height: 44px;
    white-space: nowrap;
    width: 100%;
    justify-content: center;
}

.btn-search:hover {
    background: #2980b9;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
}

/* KẾT QUẢ TÌM KIẾM */
.customer-found, .customer-not-found {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
    border-left: 4px solid;
    animation: fadeIn 0.5s ease;
}

.customer-found {
    border-left-color: #28a745;
    background: #f8fff9;
}

.customer-not-found {
    border-left-color: #ffc107;
    background: #fffbf0;
}

.customer-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.customer-header h4 {
    margin: 0;
    color: #2c3e50;
    font-size: 1.1rem;
}

.success-icon {
    color: #28a745;
    font-size: 1.3rem;
}

.info-icon {
    color: #ffc107;
    font-size: 1.3rem;
}

.customer-details {
    display: grid;
    gap: 10px;
    margin-bottom: 15px;
}

.detail-item {
    display: flex;
    gap: 10px;
    align-items: flex-start;
}

.detail-item strong {
    min-width: 80px;
    color: #495057;
    font-weight: 600;
}

.success-message {
    background: #d4edda;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    padding: 12px 15px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #155724;
    font-size: 0.9rem;
}

/* ACTION LINKS */
.action-links {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 15px;
}

.btn-add-customer {
    background: #28a745;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    transition: all 0.3s;
    text-align: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    font-size: 0.95rem;
    width: 100%;
}

.btn-add-customer:hover {
    background: #218838;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

/* FORM ĐĂNG KÝ DỊCH VỤ */
.booking-content {
    max-width: 900px;
    margin: 0 auto;
}

.booking-form-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin-top: 20px;
}

.form-section-group {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
}

.form-section-group:last-child {
    border-bottom: none;
}

.form-section-title {
    font-size: 1.2rem;
    color: #2c3e50;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge-new, .badge-existing {
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 10px;
}

.badge-new {
    background: #ffc107;
    color: #212529;
}

.badge-existing {
    background: #17a2b8;
    color: white;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s;
    box-sizing: border-box;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
    outline: none;
}

.disabled-field {
    background: #f8f9fa !important;
    color: #6c757d !important;
    cursor: not-allowed !important;
    border-color: #dee2e6 !important;
}

.new-customer-notice {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 6px;
    padding: 12px 15px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 15px;
    color: #856404;
    font-size: 0.9rem;
}

/* CSS CHO NÚT THÊM THIẾT BỊ VÀ NÚT ĐẶT DỊCH VỤ */
.devices-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.device-item {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    transition: all 0.3s;
}

.device-item:hover {
    border-color: #3498db;
    box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
}

.device-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #dee2e6;
}

.device-header h4 {
    color: #2c3e50;
    font-weight: 600;
    margin: 0;
    font-size: 1.1rem;
}

.btn-remove-device {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
    font-weight: 500;
}

.btn-remove-device:hover {
    background: #c0392b;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
}

/* PHẦN THÊM THIẾT BỊ */
.add-device-section {
    text-align: center;
    padding: 25px;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    background: #fafbfc;
    margin-top: 10px;
    transition: all 0.3s;
}

.add-device-section:hover {
    border-color: #3498db;
    background: #f8f9fa;
}

.btn-add-device {
    background: linear-gradient(135deg, #2ecc71, #27ae60);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2);
}

.btn-add-device:hover {
    background: linear-gradient(135deg, #27ae60, #219653);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
}

.btn-add-device:active {
    transform: translateY(0);
}

.btn-add-device:disabled {
    background: #95a5a6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-add-device:disabled:hover {
    background: #95a5a6;
    transform: none;
    box-shadow: none;
}

/* NÚT ĐẶT DỊCH VỤ */
.form-actions {
    text-align: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.btn-submit-booking {
    background: linear-gradient(135deg, #3498db, #2980b9);
    color: white;
    border: none;
    padding: 15px 40px;
    border-radius: 50px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-submit-booking:disabled {
    background: #95a5a6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.btn-submit-booking:disabled:hover {
    background: #95a5a6;
    transform: none;
    box-shadow: none;
}

.btn-submit-booking:not(:disabled):hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(52, 152, 219, 0.4);
}

.btn-submit-booking:not(:disabled):active {
    transform: translateY(-1px);
}

/* Ghi chú form */
.form-note {
    margin-top: 15px;
    color: #6c757d;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    line-height: 1.4;
}

.form-note i {
    color: #3498db;
    font-size: 1rem;
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.device-item.new-device {
    animation: slideDown 0.4s ease;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .booking-section {
        padding: 20px 0;
    }
    
    .section-title {
        font-size: 1.6rem;
    }
    
    .search-card,
    .booking-form-container {
        padding: 20px;
        margin: 0 10px;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .search-form .form-row {
        flex-direction: column;
    }
    
    .search-form .form-group {
        min-width: 100%;
    }
    
    .employee-notice {
        flex-direction: column;
        text-align: center;
        gap: 10px;
        margin: 20px 10px;
    }
    
    .detail-item {
        flex-direction: column;
        gap: 2px;
    }
    
    .device-item {
        padding: 15px;
    }
    
    .device-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .btn-remove-device {
        align-self: flex-end;
    }
    
    .btn-add-device,
    .btn-submit-booking {
        width: 100%;
        padding: 12px 20px;
        font-size: 1rem;
        justify-content: center;
    }
    
    .add-device-section {
        padding: 20px 15px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format số điện thoại
    const phoneInput = document.getElementById('customer_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });
    }
    
    // Set min date cho input date
    const today = new Date().toISOString().split('T')[0];
    const bookingDate = document.getElementById('booking_date');
    if (bookingDate) {
        bookingDate.value = today;
    }
});

// Hàm bật chế độ nhập thủ công
function enableManualInput() {
    const bookingSection = document.getElementById('bookingFormSection');
    if (bookingSection) {
        bookingSection.style.display = 'block';
    }
    
    // Bỏ readonly cho các trường input
    const nameInput = document.getElementById('customer_name');
    const emailInput = document.getElementById('customer_email');
    const addressInput = document.getElementById('customer_address');
    
    if (nameInput) nameInput.readOnly = false;
    if (emailInput) emailInput.readOnly = false;
    if (addressInput) addressInput.readOnly = false;
    
    // Thông báo
    alert('Bạn có thể nhập thông tin khách hàng thủ công. Tài khoản sẽ được tạo tự động.');
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let deviceCount = 1;
    const maxDevices = 5;
    
    // Set min date cho input date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('booking_date').min = today;
    
    // Format số điện thoại
    document.getElementById('customer_phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });
    
    // Xử lý thêm thiết bị
    document.getElementById('btn-add-device').addEventListener('click', function() {
        if (deviceCount >= maxDevices) {
            alert(`Bạn chỉ có thể thêm tối đa ${maxDevices} thiết bị mỗi đơn!`);
            return;
        }
        
        deviceCount++;
        addDevice(deviceCount);
        updateAddButtonState();
    });
    
    function addDevice(index) {
        const additionalDevices = document.getElementById('additional-devices');
        
        const newDevice = document.createElement('div');
        newDevice.className = 'device-item new-device';
        newDevice.setAttribute('data-device-index', index);
        
        // Tạo options cho select từ CSDL (sử dụng dữ liệu PHP)
        const deviceOptions = `<?php 
            $options = '';
            foreach ($devices as $device) {
                $options .= '<option value=\"' . $device['maThietBi'] . '\">' . htmlspecialchars($device['tenThietBi']) . '</option>';
            }
            echo $options;
        ?>`;
        
        newDevice.innerHTML = `
            <div class="device-header">
                <h4>Thiết bị ${index}</h4>
                <button type="button" class="btn-remove-device">
                    <i class="fas fa-times"></i> Xóa
                </button>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="device_type_${index}">Loại thiết bị *</label>
                    <select id="device_type_${index}" name="device_types[]" class="device-type-select" required>
                        <option value="">Chọn loại thiết bị</option>
                        ${deviceOptions}
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="device_model_${index}">Thông tin thiết bị</label>
                    <input type="text" id="device_model_${index}" name="device_models[]" 
                           placeholder="Model, hãng sản xuất... (tuỳ chọn)">
                </div>
            </div>
            
            <div class="form-group">
                <label for="device_problem_${index}">Mô tả tình trạng hư hỏng *</label>
                <textarea id="device_problem_${index}" name="device_problems[]" required rows="3"
                          placeholder="Mô tả chi tiết tình trạng hư hỏng của thiết bị..."></textarea>
            </div>
        `;
        
        // Thêm sự kiện xóa
        newDevice.querySelector('.btn-remove-device').addEventListener('click', function() {
            removeDevice(index, newDevice);
        });
        
        additionalDevices.appendChild(newDevice);
        
        // Cuộn đến thiết bị mới
        newDevice.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    function removeDevice(index, deviceElement) {
        deviceElement.style.opacity = '0';
        deviceElement.style.transform = 'translateX(-20px)';
        deviceElement.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            deviceElement.remove();
            deviceCount--;
            updateDeviceNumbers();
            updateAddButtonState();
        }, 300);
    }
    
    function updateDeviceNumbers() {
        const devices = document.querySelectorAll('.device-item');
        devices.forEach((device, index) => {
            const deviceIndex = index + 1;
            device.setAttribute('data-device-index', deviceIndex);
            device.querySelector('h4').textContent = `Thiết bị ${deviceIndex}`;
            
            // Cập nhật IDs và labels
            const typeSelect = device.querySelector('.device-type-select');
            const modelInput = device.querySelector('input[type="text"]');
            const problemTextarea = device.querySelector('textarea');
            
            if (typeSelect) {
                typeSelect.id = `device_type_${deviceIndex}`;
                typeSelect.previousElementSibling.setAttribute('for', `device_type_${deviceIndex}`);
            }
            
            if (modelInput) {
                modelInput.id = `device_model_${deviceIndex}`;
                modelInput.previousElementSibling.setAttribute('for', `device_model_${deviceIndex}`);
            }
            
            if (problemTextarea) {
                problemTextarea.id = `device_problem_${deviceIndex}`;
                problemTextarea.previousElementSibling.setAttribute('for', `device_problem_${deviceIndex}`);
            }
        });
    }
    
    function updateAddButtonState() {
        const addButton = document.getElementById('btn-add-device');
        if (deviceCount >= maxDevices) {
            addButton.disabled = true;
            addButton.innerHTML = '<i class="fas fa-ban"></i> Đã đạt tối đa';
        } else {
            addButton.disabled = false;
            addButton.innerHTML = '<i class="fas fa-plus"></i> Thêm thiết bị khác';
        }
    }
    
    // Xử lý form submit
    document.getElementById('serviceBookingForm').addEventListener('submit', function(e) {
        // Kiểm tra các trường bắt buộc
        const deviceTypes = this.querySelectorAll('select[name="device_types[]"]');
        let isValid = true;
        
        deviceTypes.forEach((select, index) => {
            if (!select.value) {
                isValid = false;
                select.style.borderColor = '#e74c3c';
                select.focus();
            } else {
                select.style.borderColor = '#e9ecef';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng chọn loại thiết bị cho tất cả các thiết bị!');
            return;
        }
        
        // Form sẽ được submit bình thường
    });
    
    // Khởi tạo trạng thái ban đầu
    updateAddButtonState();
});
</script>