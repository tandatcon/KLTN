<?php
session_start();

if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}
$pageTitle = "Đặt dịch vụ - TechCare";

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để đặt lịch!";
    header('Location: ' . url('dang-nhap'));
    exit();
}
include VIEWS_PATH . '/header.php';

// Include class DichVuService
require_once __DIR__ . '/../function/dichvu.php';
require_once __DIR__ . '/../function/khachhang.php';

// Khởi tạo đối tượng DichVuService
$dichVuService = new DichVuService($db);
$khachhang = new khachhang($db);

date_default_timezone_set('Asia/Ho_Chi_Minh');

// Lấy ngày hiện tại
$currentDate = date('Y-m-d');
$currentHour = date('H');

// Lấy dữ liệu
$devices = $dichVuService->layDanhSachThietBi();
$danhSachKhungGio = $dichVuService->layDanhSachKhungGio();

$maKH = $_SESSION['user_id'] ?? [];
$userInfo = $khachhang->layKHByID($maKH);

// Kiểm tra và xử lý địa chỉ từ CSDL
$userAddress = isset($userInfo['diaChi']) ? htmlspecialchars($userInfo['diaChi']) : '';

// KIỂM TRA ĐỊA CHỈ CÓ PHẢI TP HCM KHÔNG
$isHCMAddress = false;
$addressError = '';

if (!empty($userAddress)) {
    // Chuẩn hóa địa chỉ để kiểm tra
    $normalizedAddress = mb_strtolower(trim($userAddress), 'UTF-8');
    
    // Các từ khóa xác định TP HCM
    $hcmKeywords = [
        'hồ chí minh', 'ho chi minh', 'hcm', 'tp.hcm', 'tp hcm', 
        'tphcm', 'sài gòn', 'sai gon', 'sg', 'tphcm',
        // Các quận TP HCM
        'quận 1', 'quận 2', 'quận 3', 'quận 4', 'quận 5', 'quận 6', 'quận 7', 'quận 8', 'quận 9', 'quận 10',
        'quận 11', 'quận 12', 'quận bình thạnh', 'quận gò vấp', 'quận phú nhuận', 'quận tân bình',
        'quận tân phú', 'quận bình tân', 'quận thủ đức', 'quận bình chánh', 'huyện bình chánh',
        'quận củ chi', 'huyện củ chi', 'quận hóc môn', 'huyện hóc môn', 'quận nhà bè', 'huyện nhà bè',
        'quận cần giờ', 'huyện cần giờ'
    ];
    
    foreach ($hcmKeywords as $keyword) {
        if (strpos($normalizedAddress, $keyword) !== false) {
            $isHCMAddress = true;
            break;
        }
    }
    
    if (!$isHCMAddress) {
        $addressError = 'Địa chỉ của bạn không thuộc khu vực TP Hồ Chí Minh. Hiện tại chúng tôi chỉ hỗ trợ dịch vụ trong TP HCM.';
    }
}

// Kiểm tra điều kiện đặt lịch
$canBook = !empty($userInfo['diaChi']) && 
           !empty($userInfo['sdt']) && 
           $isHCMAddress;
?>

<section class="py-4">
    <div class="container">
        <!-- Header -->
        <div class="card border-0 shadow mb-4">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="">
                    <h1 class="display-5 fw-bold mb-3 text-primary">
                        <i class="fas fa-tools me-3"></i>ĐẶT DỊCH VỤ SỬA CHỮA THIẾT BỊ
                    </h1>
                    <p class="lead text-muted mb-0">Dịch vụ chuyên nghiệp - Hỗ trợ tận tâm</p>
                </div>
            </div>
        </div>

        <!-- FORM CHÍNH -->
        <form id="serviceBookingForm" action="<?php echo url('quy-trinh-don'); ?>" method="POST" novalidate>
            <input type="hidden" id="booking_date" name="booking_date" value="<?php echo $currentDate; ?>">
            <input type="hidden" name="id_khachhang" value="<?php echo $userInfo['maND'] ?? ''; ?>">

            <div class="row">
                <!-- Cột trái -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-gray mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Thông tin khách hàng
                            </h5>
                            
                            <!-- Lưu ý về khu vực hỗ trợ -->
                            <div class="alert alert-info mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2 fs-5 text-primary"></i>
                                    <div>
                                        <strong class="d-block">Lưu ý quan trọng:</strong>
                                        Hiện tại chúng tôi chỉ hỗ trợ sửa chữa tại khu vực Thành phố Hồ Chí Minh
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hiển thị thông tin khách hàng từ CSDL -->
                            <div class="customer-info-display mb-4 p-3 border border-gray rounded bg-light">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <strong><i class="fas fa-user me-2"></i>Họ và tên:</strong>
                                        <span id="display_customer_name"><?php echo isset($userInfo['hoTen']) ? htmlspecialchars($userInfo['hoTen']) : ''; ?></span>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <strong><i class="fas fa-phone me-2"></i>Số điện thoại:</strong>
                                        <span id="display_customer_phone"><?php echo isset($userInfo['sdt']) ? htmlspecialchars($userInfo['sdt']) : ''; ?></span>
                                    </div>
                                    <div class="col-12 mb-2">
                                        <strong><i class="fas fa-envelope me-2"></i>Email:</strong>
                                        <span id="display_customer_email"><?php echo isset($userInfo['email']) ? htmlspecialchars($userInfo['email']) : 'Chưa có email'; ?></span>
                                    </div>
                                    <div class="col-12">
                                        <strong><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ:</strong>
                                        <span id="display_customer_address"><?php echo $userAddress ?: 'Chưa có địa chỉ'; ?></span>
                                        
                                        <!-- Hiển thị thông báo lỗi địa chỉ nếu không phải TP HCM -->
                                        <?php if (!empty($userAddress) && !$isHCMAddress): ?>
                                            <div class="alert alert-danger mt-2 mb-0 p-2">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <?php echo $addressError; ?>
                                            </div>
                                        <?php elseif ($isHCMAddress): ?>
                                            <div class="alert alert-success mt-2 mb-0 p-2">
                                                <i class="fas fa-check-circle me-2"></i>
                                                Địa chỉ thuộc khu vực hỗ trợ của chúng tôi
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Liên kết thay đổi thông tin cá nhân -->
                                <div class="text-end mt-3">
                                    <a href="<?php echo url('ca-nhan'); ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit me-1"></i>Thay đổi thông tin cá nhân
                                    </a>
                                </div>
                            </div>

                            <!-- Các trường ẩn để gửi dữ liệu -->
                            <input type="hidden" id="customer_name" name="customer_name"
                                value="<?php echo isset($userInfo['hoTen']) ? htmlspecialchars($userInfo['hoTen']) : ''; ?>">
                            <input type="hidden" id="customer_phone" name="customer_phone"
                                value="<?php echo isset($userInfo['sdt']) ? htmlspecialchars($userInfo['sdt']) : ''; ?>">
                            <input type="hidden" id="customer_email" name="customer_email"
                                value="<?php echo isset($userInfo['email']) ? htmlspecialchars($userInfo['email']) : ''; ?>">
                            <input type="hidden" id="customer_address" name="customer_address" value="<?php echo $userAddress; ?>">

                            <!-- Thông báo nếu thiếu thông tin -->
                            <?php if (empty($userInfo['diaChi']) || empty($userInfo['sdt'])): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Vui lòng cập nhật đầy đủ thông tin cá nhân (số điện thoại, địa chỉ) để đặt lịch.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Mô tả sự cố -->
                    <div class="card border-gray">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-tools me-2"></i>Thông tin thiết bị cần sửa
                            </h5>
                            <div class="devices-container">
                                <div class="device-item mb-4 p-4 border rounded bg-light" data-index="1">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-primary mb-0">Thiết bị 1</h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger d-none btn-remove-device">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </div>

                                    <div class="row g-3">
                                        <!-- 1. Chọn Thiết bị -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Loại thiết bị <span class="text-danger">*</span></label>
                                            <select class="form-select device-type-select" name="device_types[]" required>
                                                <option value="">-- Chọn thiết bị --</option>
                                                <?php foreach ($devices as $d): ?>
                                                    <option value="<?= $d['maThietBi'] ?>"><?= htmlspecialchars($d['tenThietBi']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <!-- 2. Chọn Hãng (load bằng AJAX) -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Hãng sản xuất <span class="text-danger">*</span></label>
                                            <select class="form-select device-brand-select" name="device_brands[]" disabled required>
                                                <option value="">-- Chọn hãng --</option>
                                            </select>
                                        </div>

                                        <!-- 3. Chọn Mẫu (load bằng AJAX) -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Mẫu sản phẩm <span class="text-danger">*</span></label>
                                            <select class="form-select device-model-select" name="device_models[]" disabled required>
                                                <option value="">-- Chọn mẫu --</option>
                                            </select>
                                        </div>

                                        <!-- 4. Mô tả sự cố -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Mô tả tình trạng hư hỏng <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="device_problems[]" rows="3" 
                                                    placeholder="Ví dụ: Máy lạnh không mát, có tiếng kêu lạ từ dàn nóng..." required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nơi thêm thiết bị mới -->
                                <div id="additional-devices"></div>

                                <div class="text-center mt-3">
                                    <button type="button" id="btn-add-device" class="btn btn-outline-success">
                                        <i class="fas fa-plus me-2"></i>Thêm thiết bị khác (tối đa 3)
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-gray">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3"><i class="fas fa-clock me-2"></i>Thời gian đặt lịch
                            </h5>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>Chọn ngày</h6>
                                <div class="row g-2" id="date-grid"></div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-clock me-2"></i>Chọn khung giờ</h6>
                                <div class="row g-2" id="time-slots-container"></div>
                            </div>

                            <div class="mb-4">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-comments me-2"></i>Ghi chú thêm
                                </h5>
                                <textarea class="form-control input-gray" id="problem_description"
                                    name="problem_description" rows="3"
                                    placeholder="Ghi chú của bạn dành cho chúng tôi..."></textarea>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold" 
                                    <?php echo !$canBook ? 'disabled' : ''; ?>>
                                    <i class="fas fa-bolt me-2"></i>
                                    <?php 
                                    if (empty($userInfo['diaChi']) || empty($userInfo['sdt'])) {
                                        echo 'VUI LÒNG CẬP NHẬT THÔNG TIN';
                                    } elseif (!$isHCMAddress) {
                                        echo 'KHÔNG HỖ TRỢ KHU VỰC NÀY';
                                    } else {
                                        echo 'ĐẶT LỊCH NGAY';
                                    }
                                    ?>
                                </button>
                                
                                <?php if (!$canBook): ?>
                                    <div class="mt-2">
                                        <small class="text-danger">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <?php 
                                            if (empty($userInfo['diaChi']) || empty($userInfo['sdt'])) {
                                                echo 'Cần cập nhật số điện thoại và địa chỉ để đặt lịch';
                                            } elseif (!$isHCMAddress) {
                                                echo 'Chúng tôi chưa hỗ trợ dịch vụ tại khu vực của bạn';
                                            }
                                            ?>
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="banner-image-container text-center mt-4">
                                <img src="<?php echo asset('images/waitting.jpg'); ?>" alt="TechCare Banner" class="banner-image" style="max-width: 100%; height: 300px; object-fit: cover;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Nút chỉ đường -->
        <div class="text-center mt-4">
            <?php
            $address = "Bệnh viện Chợ Rẫy, Quận 5, TP.HCM";
            ?>
            <a href="https://www.google.com/maps/dir/?api=1&destination=<?php echo urlencode($address); ?>"
                target="_blank" class="btn btn-primary">
                <i class="fas fa-map-marker-alt me-2"></i>Chỉ đường đến đây
            </a>
        </div>
    </div>
</section>

<style>
    .error-message {
        font-size: 0.9rem;
        color: #dc3545;
        margin-top: 4px;
        display: block;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .border-gray {
        border-color: #dee2e6 !important;
    }

    .input-gray {
        background-color: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
        border-radius: 4px !important;
        transition: all 0.3s ease;
    }

    .input-gray:focus {
        background-color: #ffffff !important;
        border-color: #495057 !important;
        box-shadow: 0 0 0 0.2rem rgba(73, 80, 87, 0.1) !important;
    }

    .input-gray:hover {
        background-color: #e9ecef !important;
        border-color: #adb5bd !important;
    }

    .input-gray::placeholder {
        color: #adb5bd !important;
        opacity: 1;
        font-weight: 400;
    }

    .banner-image {
        max-width: 100%;
        height: auto;
        object-fit: contain;
        margin-bottom: 20px;
    }

    .date-btn {
        width: 100%;
        padding: 8px 4px;
        font-size: 0.85rem;
    }

    .time-slot-disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .slot-info {
        font-size: 0.8em;
    }

    .customer-info-display {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }

    .alert-info {
        border-left: 4px solid #17a2b8;
    }

    @media (min-width: 992px) {
        .banner-image {
            max-width: 60%;
            height: 150px;
        }
    }

    @media (max-width: 991.98px) and (min-width: 768px) {
        .banner-image {
            max-width: 80%;
            height: 130px;
        }
    }

    @media (max-width: 767.98px) {
        .banner-image {
            max-width: 95%;
            height: 110px;
        }

        .date-btn {
            font-size: 0.8rem;
            padding: 6px 2px;
        }
    }
</style>

<script>
    // BIẾN TOÀN CỤC
    let currentSelectedDate = '<?php echo $currentDate; ?>';
    let deviceCount = 1;
    const maxDevices = 3;

    // KHỞI TẠO KHI TRANG LOAD
    document.addEventListener('DOMContentLoaded', function () {
        console.log("🚀 DOM Content Loaded - Khởi tạo trang đặt dịch vụ");

        generateDateGrid();
        loadSlotsForDate(currentSelectedDate);
        initDeviceManagement();
        initFormValidation();
        
        // Gắn sự kiện cho thiết bị đầu tiên
        const firstDevice = document.querySelector('.device-item');
        if (firstDevice) {
            attachDeviceEvents(firstDevice);
            toggleRemoveButtons();
        }
    });

    // GẮN SỰ KIỆN AJAX CHO THIẾT BỊ
    function attachDeviceEvents(block) {
        const typeSelect = block.querySelector('.device-type-select');
        const brandSelect = block.querySelector('.device-brand-select');
        const modelSelect = block.querySelector('.device-model-select');

        typeSelect.addEventListener('change', function () {
            const maThietBi = this.value;
            brandSelect.innerHTML = '<option value="">-- Đang tải hãng... --</option>';
            brandSelect.disabled = true;
            modelSelect.innerHTML = '<option value="">-- Chọn mẫu --</option>';
            modelSelect.disabled = true;

            if (!maThietBi) return;

            fetch('<?= url("ajax-device") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=get_brands&maThietBi=' + maThietBi
            })
            .then(r => r.json())
            .then(data => {
                brandSelect.innerHTML = '<option value="">-- Chọn hãng --</option>';
                if (data.success && data.brands.length > 0) {
                    data.brands.forEach(b => {
                        brandSelect.innerHTML += `<option value="${b.maHang}">${b.tenHang}</option>`;
                    });
                }
                brandSelect.disabled = false;
            })
            .catch(error => {
                console.error('Lỗi tải hãng:', error);
                brandSelect.innerHTML = '<option value="">-- Lỗi tải hãng --</option>';
            });
        });

        brandSelect.addEventListener('change', function () {
            const maHang = this.value;
            modelSelect.innerHTML = '<option value="">-- Đang tải mẫu... --</option>';
            modelSelect.disabled = true;

            if (!maHang) return;

            fetch('<?= url("ajax-device") ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=get_models&maHang=' + maHang
            })
            .then(r => r.json())
            .then(data => {
                modelSelect.innerHTML = '<option value="">-- Chọn mẫu --</option>';
                if (data.success && data.models.length > 0) {
                    data.models.forEach(m => {
                        const label = m.tenMau === 'Mẫu khác' ? 'Mẫu khác (dòng cũ)' : m.tenMau;
                        modelSelect.innerHTML += `<option value="${m.maMau}">${label}</option>`;
                    });
                }
                modelSelect.disabled = false;
            })
            .catch(error => {
                console.error('Lỗi tải mẫu:', error);
                modelSelect.innerHTML = '<option value="">-- Lỗi tải mẫu --</option>';
            });
        });
    }

    // ==============================
    // 🔧 VALIDATION HELPER FUNCTIONS
    // ==============================
    function showError(input, message) {
        const oldError = input.parentElement.querySelector('.error-message');
        if (oldError) oldError.remove();

        const error = document.createElement('small');
        error.className = 'error-message text-danger d-block mt-1';
        error.textContent = message;
        input.insertAdjacentElement('afterend', error);
    }

    function clearAllErrors() {
        document.querySelectorAll('.error-message').forEach(e => e.remove());
    }

    function isValidPhone(phone) {
        const regex = /^(0|\+84)[0-9]{9,10}$/;
        return regex.test(phone);
    }

    // ==============================
    // 🚀 INIT FORM VALIDATION
    // ==============================
    function initFormValidation() {
        const form = document.getElementById('serviceBookingForm');

        if (!form) return;

        // Khi người dùng nhập hoặc thay đổi -> ẩn lỗi tương ứng
        form.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', () => {
                const error = field.parentElement.querySelector('.error-message');
                if (error) error.remove();
            });
            field.addEventListener('change', () => {
                const error = field.parentElement.querySelector('.error-message');
                if (error) error.remove();
            });
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearAllErrors();
            let hasError = false;

            // Kiểm tra thông tin khách hàng từ CSDL
            const customerPhone = document.getElementById('customer_phone').value;
            const customerAddress = document.getElementById('customer_address').value;

            if (!customerPhone || customerPhone.trim() === '') {
                showCustomerInfoError('Vui lòng cập nhật số điện thoại trong thông tin cá nhân');
                hasError = true;
            } else if (!isValidPhone(customerPhone.trim())) {
                showCustomerInfoError('Số điện thoại trong thông tin cá nhân không hợp lệ');
                hasError = true;
            }

            if (!customerAddress || customerAddress.trim() === '') {
                showCustomerInfoError('Vui lòng cập nhật địa chỉ trong thông tin cá nhân');
                hasError = true;
            }

            // --- Ngày đặt lịch ---
            const bookingDate = document.querySelector('input[name="booking_date"]:checked');
            if (!bookingDate) {
                const dateGrid = document.getElementById('date-grid');
                showError(dateGrid, 'Vui lòng chọn ngày đặt lịch');
                hasError = true;
            }

            // --- Khung giờ ---
            const bookingTime = document.querySelector('input[name="maKhungGio"]:checked');
            if (!bookingTime) {
                const timeContainer = document.getElementById('time-slots-container');
                showError(timeContainer, 'Vui lòng chọn khung giờ đặt lịch');
                hasError = true;
            }

            // --- Kiểm tra các thiết bị ---
            const deviceBlocks = document.querySelectorAll('.device-item');
            deviceBlocks.forEach((block, index) => {
                const deviceType = block.querySelector('select[name="device_types[]"]');
                const deviceBrand = block.querySelector('select[name="device_brands[]"]');
                const deviceModel = block.querySelector('select[name="device_models[]"]');
                const problem = block.querySelector('textarea[name="device_problems[]"]');

                if (deviceType && deviceType.value === '') {
                    showError(deviceType, `Vui lòng chọn loại thiết bị ${index + 1}`);
                    hasError = true;
                }

                if (deviceBrand && deviceBrand.value === '') {
                    showError(deviceBrand, `Vui lòng chọn hãng sản xuất cho thiết bị ${index + 1}`);
                    hasError = true;
                }

                if (deviceModel && deviceModel.value === '') {
                    showError(deviceModel, `Vui lòng chọn mẫu sản phẩm cho thiết bị ${index + 1}`);
                    hasError = true;
                }

                if (problem && problem.value.trim() === '') {
                    showError(problem, `Vui lòng mô tả tình trạng của thiết bị ${index + 1}`);
                    hasError = true;
                }
            });

            if (hasError) {
                showConfirm(
                    'Vui lòng nhập đầy đủ thông tin trước khi xác nhận đặt lịch!',
                    'Thiếu thông tin'
                );
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            showBookingConfirmation();
        });
    }

    function showCustomerInfoError(message) {
        const customerInfoDisplay = document.querySelector('.customer-info-display');
        if (customerInfoDisplay) {
            const existingError = customerInfoDisplay.querySelector('.customer-error');
            if (existingError) existingError.remove();

            const errorDiv = document.createElement('div');
            errorDiv.className = 'customer-error alert alert-danger mt-3';
            errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
            customerInfoDisplay.appendChild(errorDiv);
        }
    }

    // XÁC NHẬN ĐẶT LỊCH
    function showBookingConfirmation() {
        showConfirm(
            'Bạn xác nhận muốn đặt lịch sửa chữa?',
            'Xác nhận đặt lịch',
            () => {
                document.getElementById('serviceBookingForm').submit();
            },
            () => {
                console.log('Đã hủy đặt lịch');
            }
        );
    }

    // TẠO LƯỚI NGÀY
    function generateDateGrid() {
        const dateGrid = document.getElementById('date-grid');
        if (!dateGrid) {
            console.error("❌ Không tìm thấy date-grid");
            return;
        }

        console.log("📅 Tạo lưới ngày");
        const phpDate = '<?php echo $currentDate; ?>';
        const [year, month, day] = phpDate.split('-').map(Number);

        const baseDate = new Date(year, month - 1, day, 12, 0, 0);

        for (let i = 0; i < 8; i++) {
            const currentDate = new Date(baseDate);
            currentDate.setDate(baseDate.getDate() + i);

            const year = currentDate.getFullYear();
            const month = String(currentDate.getMonth() + 1).padStart(2, '0');
            const day = String(currentDate.getDate()).padStart(2, '0');
            const dateString = `${year}-${month}-${day}`;

            const dayName = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'][currentDate.getDay()];
            const displayDay = currentDate.getDate();
            const displayMonth = currentDate.getMonth() + 1;

            const isToday = i === 0;

            const dateElement = document.createElement('div');
            dateElement.className = 'col-4 col-sm-3 col-md-3';
            dateElement.innerHTML = `
            <input type="radio" class="btn-check date-radio" name="booking_date" 
                   id="date_${i}" value="${dateString}" ${isToday ? 'checked' : ''}>
            <label class="btn btn-outline-secondary date-btn w-100 ${isToday ? 'active' : ''}" 
                   for="date_${i}">
                <div class="fw-bold">${dayName}</div>
                <div class="small">${displayDay}/${displayMonth}</div>
                ${isToday ? '<div class="very-small text-primary">(Hôm nay)</div>' : ''}
            </label>
        `;

            dateGrid.appendChild(dateElement);
        }

        document.querySelectorAll('.date-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    currentSelectedDate = this.value;
                    document.getElementById('booking_date').value = currentSelectedDate;
                    console.log("📅 Ngày được chọn:", currentSelectedDate);
                    loadSlotsForDate(currentSelectedDate);

                    document.querySelectorAll('.date-btn').forEach(btn => {
                        btn.classList.remove('active');
                    });
                    this.nextElementSibling.classList.add('active');
                }
            });
        });
    }

    // LOAD SLOTS THEO NGÀY
    async function loadSlotsForDate(date) {
        console.log("Bắt đầu load slots cho ngày:", date);

        const timeContainer = document.getElementById('time-slots-container');
        if (!timeContainer) {
            console.error("Không tìm thấy time-slots-container");
            return;
        }

        timeContainer.innerHTML = `
            <div class="col-12 text-center p-4">
                <div class="spinner-border text-primary mb-2"></div>
                <p class="text-muted">Đang tải khung giờ...</p>
            </div>
        `;

        try {
            const formData = new FormData();
            formData.append('action', 'get_slots');
            formData.append('date', date);

            const selectedDate = new Date(date);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            selectedDate.setHours(0, 0, 0, 0);

            let currentHour;
            if (selectedDate.getTime() === today.getTime()) {
                currentHour = new Date().getHours();
            } else {
                currentHour = 0;
            }

            formData.append('current_hour', currentHour);

            const response = await fetch('<?php echo url("ajax-booking"); ?>', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);

            const result = await response.json();

            if (result.success) {
                updateSlotsDisplay(result.slots, date);
            } else {
                showSlotError(result.error || 'Lỗi server');
            }
        } catch (error) {
            console.error("Lỗi fetch:", error);
            showSlotError('Lỗi kết nối: ' + error.message);
        }
    }

    function showSlotError(message) {
        const timeContainer = document.getElementById('time-slots-container');
        if (timeContainer) {
            timeContainer.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Lỗi:</strong> ${message}
                </div>
                <div class="text-center">
                    <button class="btn btn-primary btn-sm" onclick="loadSlotsForDate(currentSelectedDate)">
                        <i class="fas fa-redo me-1"></i>Thử lại
                    </button>
                </div>
            </div>
        `;
        }
    }

    // CẬP NHẬT HIỂN THỊ SLOTS
    function updateSlotsDisplay(slots, date) {
        const timeContainer = document.getElementById('time-slots-container');

        if (!timeContainer) {
            console.error("❌ Không tìm thấy time-slots-container");
            return;
        }

        console.log("🎯 Cập nhật hiển thị slots:", slots?.length || 0, "slots");

        if (!slots || slots.length === 0) {
            timeContainer.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Không có khung giờ nào khả dụng cho ngày ${date}
                </div>
            </div>
        `;
            return;
        }

        timeContainer.innerHTML = '';

        slots.forEach(slot => {
            const slotElement = document.createElement('div');
            slotElement.className = 'col-md-6 col-lg-4 mb-3';

            const isAvailable = slot.kha_dung_bool !== undefined ? slot.kha_dung_bool : (slot.kha_dung > 0);
            const reason = slot.ly_do || (isAvailable ? 'Có thể đặt' : 'Không khả dụng');

            slotElement.innerHTML = `
            <div class="time-slot-group text-center">
                <input type="radio" class="btn-check time-slot-radio" name="maKhungGio" 
                       id="time_${slot.maKhungGio}" value="${slot.maKhungGio}" 
                       ${!isAvailable ? 'disabled' : ''}>
                <label class="btn btn-outline-primary w-100 py-3 time-slot-label ${!isAvailable ? 'time-slot-disabled' : ''}" 
                       for="time_${slot.maKhungGio}">
                    <div class="fw-bold">${slot.pham_vi || slot.khoangGio || 'N/A'}</div>
                    <div class="small text-muted">${slot.gioBatDau || '?'} - ${slot.gioKetThuc || '?'}</div>
                    ${!isAvailable ? 
                        `<div class="slot-info mt-1"><small class="text-danger">${reason}</small></div>` : 
                        ''}
                </label>
            </div>
        `;
            timeContainer.appendChild(slotElement);
        });
    }

    // QUẢN LÝ THIẾT BỊ
    function initDeviceManagement() {
        const addButton = document.getElementById('btn-add-device');
        if (!addButton) {
            console.error("❌ Không tìm thấy btn-add-device");
            return;
        }

        addButton.addEventListener('click', function () {
            if (deviceCount >= maxDevices) {
                alert('Chỉ có thể thêm tối đa ' + maxDevices + ' thiết bị');
                return;
            }
            deviceCount++;
            addDevice(deviceCount);
        });
    }

    function addDevice(index) {
        const additionalDevices = document.getElementById('additional-devices');
        if (!additionalDevices) return;

        const newDevice = document.createElement('div');
        newDevice.className = 'device-item mb-4 p-4 border rounded bg-light';
        newDevice.setAttribute('data-index', index);

        const deviceOptions = `<?php
        $options = '';
        foreach ($devices as $device) {
            $options .= '<option value=\"' . $device['maThietBi'] . '\">' . htmlspecialchars($device['tenThietBi']) . '</option>';
        }
        echo $options;
        ?>`;

        newDevice.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="text-primary mb-0">Thiết bị ${index}</h6>
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-device">
                <i class="fas fa-trash"></i> Xóa
            </button>
        </div>
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label fw-bold">Loại thiết bị <span class="text-danger">*</span></label>
                <select class="form-select device-type-select" name="device_types[]" required>
                    <option value="">-- Chọn thiết bị --</option>
                    ${deviceOptions}
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Hãng sản xuất <span class="text-danger">*</span></label>
                <select class="form-select device-brand-select" name="device_brands[]" disabled required>
                    <option value="">-- Chọn hãng --</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Mẫu sản phẩm <span class="text-danger">*</span></label>
                <select class="form-select device-model-select" name="device_models[]" disabled required>
                    <option value="">-- Chọn mẫu --</option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label fw-bold">Mô tả tình trạng hư hỏng <span class="text-danger">*</span></label>
                <textarea class="form-control" name="device_problems[]" rows="3" 
                          placeholder="Ví dụ: Máy lạnh không mát, có tiếng kêu lạ từ dàn nóng..." required></textarea>
            </div>
        </div>
    `;

        // Gắn sự kiện xóa
        newDevice.querySelector('.btn-remove-device').addEventListener('click', function () {
            newDevice.remove();
            deviceCount--;
            updateDeviceNumbers();
            toggleRemoveButtons();
        });

        additionalDevices.appendChild(newDevice);
        
        // Gắn sự kiện AJAX cho thiết bị mới
        attachDeviceEvents(newDevice);
        
        // Hiển thị nút xóa nếu có nhiều hơn 1 thiết bị
        toggleRemoveButtons();
    }

    // Hiển thị/ẩn nút xóa
    function toggleRemoveButtons() {
        const removeButtons = document.querySelectorAll('.btn-remove-device');
        const firstRemoveButton = document.querySelector('.device-item:first-child .btn-remove-device');
        
        if (deviceCount > 1) {
            removeButtons.forEach(btn => btn.classList.remove('d-none'));
        } else {
            if (firstRemoveButton) firstRemoveButton.classList.add('d-none');
        }
    }

    function updateDeviceNumbers() {
        const deviceItems = document.querySelectorAll('.device-item');
        deviceItems.forEach((item, index) => {
            const title = item.querySelector('h6');
            if (title) {
                title.textContent = `Thiết bị ${index + 1}`;
            }
            item.setAttribute('data-index', index + 1);
        });
    }
</script>

<?php include VIEWS_PATH . '/footer.php'; ?>