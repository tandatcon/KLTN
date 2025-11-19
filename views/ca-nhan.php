<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../helpers.php';

$pageTitle = "Thông tin cá nhân - TechCare";
include VIEWS_PATH . '/header.php';

require_once __DIR__ . '/../function/khachhang.php';
require_once __DIR__ . '/../models/Customer.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('login'));
    exit();
}

require_once __DIR__ . '/../controllers/OrderController.php';
$orderController = new OrderController($db);
$data = $orderController->showOrders();

$khachhang = new KhachHang($db);

// Lấy thông tin khách hàng
$customerInfo = $khachhang->layKHByID($_SESSION['user_id']);

// Xử lý cập nhật thông tin
$updateSuccess = false;
$updateError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    
    // Xử lý địa chỉ: nếu địa chỉ cập nhật trống thì giữ địa chỉ cũ, ngược lại dùng địa chỉ mới
    $newAddress = trim($_POST['customer_address'] ?? '');
    $currentAddress = $customerInfo['diaChi'] ?? '';
    $address = empty($newAddress) ? $currentAddress : $newAddress;

    // Server-side validation
    if (empty($name)) {
        $updateError = "Họ tên không được để trống!";
    } elseif (empty($phone)) {
        $updateError = "Số điện thoại không được để trống!";
    } elseif (!preg_match('/^(0[3|5|7|8|9])+([0-9]{8})\b/', $phone)) {
        $updateError = "Số điện thoại không hợp lệ! Vui lòng nhập đúng định dạng Việt Nam (10 số, bắt đầu bằng 03,05,07,08,09).";
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $updateError = "Email không đúng định dạng!";
    } else {
        try {
            // THÊM KIỂM TRA TRÙNG SỐ ĐIỆN THOẠI (trừ chính khách hàng này)
            $kiemTraSDT = $khachhang->kiemTraSDTTonTai($phone, $_SESSION['user_id']);
            if ($kiemTraSDT) {
                $updateError = "Số điện thoại này đã được sử dụng bởi tài khoản khác!";
            } else {
                $result = $khachhang->capNhatKH(
                    $_SESSION['user_id'],
                    $name,
                    $phone,
                    $email ?: null,
                    $address ?: null
                );

                if ($result) {
                    $updateSuccess = true;
                    $customerInfo = $khachhang->layKHByID($_SESSION['user_id']);
                    $_SESSION['user_name'] = $name; // Cập nhật tên trong session
                } else {
                    $updateError = "Cập nhật thông tin thất bại! Vui lòng thử lại.";
                }
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

// Lấy địa chỉ hiện tại từ CSDL
$currentAddress = $customerInfo['diaChi'] ?? '';
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

                <!-- THÔNG BÁO KHU VỰC HỖ TRỢ -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle me-2 fs-5 text-primary"></i>
                        <div>
                            <strong class="d-block">Lưu ý quan trọng:</strong>
                            Hiện tại chúng tôi chỉ hỗ trợ sửa chữa tại khu vực Thành phố Hồ Chí Minh
                        </div>
                    </div>
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
                                <form method="POST" action="" id="profileForm">
                                    <input type="hidden" name="update_profile" value="1">
                                    
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
                                                    value="<?php echo safe_htmlspecialchars($customerInfo['hoTen'] ?? ''); ?>" 
                                                    required placeholder="Nhập họ và tên">
                                                <?php if (empty($customerInfo['hoTen'])): ?>
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
                                                    value="<?php echo safe_htmlspecialchars($customerInfo['sdt'] ?? ''); ?>" 
                                                    required placeholder="Nhập số điện thoại">
                                                <?php if (empty($customerInfo['sdt'])): ?>
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

                                            <!-- Địa chỉ hiện tại -->
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Địa chỉ hiện tại</label>
                                                <div class="p-3 border border-gray rounded bg-light">
                                                    <div class="small">
                                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                        <span id="current_address_text">
                                                            <?php echo !empty($currentAddress) ? $currentAddress : 'Chưa có địa chỉ'; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Cập nhật địa chỉ mới -->
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">Cập nhật địa chỉ mới</label>
                                                <div class="address-select-container mb-3">
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <select class="form-select input-gray" id="province" name="province">
                                                                <option value="79">TP Hồ Chí Minh</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select class="form-select input-gray" id="district" name="district">
                                                                <option value="">Quận/Huyện</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select class="form-select input-gray" id="ward" name="ward" disabled>
                                                                <option value="">Phường/Xã</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row g-2 mt-2">
                                                        <div class="col-12">
                                                            Nhập số nhà:
                                                            <input type="text" class="form-control input-gray" id="street_address"
                                                                name="street_address" placeholder="Số nhà, tên đường">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Hiển thị địa chỉ mới -->
                                                <div class="p-2 border border-gray rounded bg-light">
                                                    <div id="full_address_display" class="small">
                                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                        <span id="address_text">Chưa chọn địa chỉ mới</span>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="customer_address" name="customer_address" value="">
                                                
                                                <div class="form-text text-info mt-2">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Nếu không chọn địa chỉ mới, hệ thống sẽ giữ nguyên địa chỉ hiện tại.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-3 pt-4 border-top">
                                        <button type="button" onclick="confirmUpdateProfile()" class="btn btn-primary btn-lg px-4">
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
                                        <span class="fw-bold text-dark">KHSC<?php echo $customerInfo['maND'] ?? ''; ?></span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/footer.php'; ?>

<style>
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

.alert-info {
    border-left: 4px solid #17a2b8;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>

<script>
// KHỞI TẠO KHI TRANG LOAD
document.addEventListener('DOMContentLoaded', function () {
    console.log("🚀 DOM Content Loaded - Khởi tạo trang cá nhân");
    initAddressAPI();
    initFormValidation();
});

// QUẢN LÝ ĐỊA CHỈ VỚI API - CHỈ TP HCM
function initAddressAPI() {
    console.log("📍 Khởi tạo Address API - Toàn bộ TP HCM");
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');
    const streetInput = document.getElementById('street_address');

    const baseURL = 'https://provinces.open-api.vn/api/';

    // KHỞI TẠO ĐỊA CHỈ
    initializeAddress();

    async function initializeAddress() {
        try {
            // SET TP HCM MẶC ĐỊNH
            provinceSelect.innerHTML = '<option value="">Thành phố</option>';
            const hcmOption = document.createElement('option');
            hcmOption.value = '79';
            hcmOption.textContent = 'TP Hồ Chí Minh';
            provinceSelect.appendChild(hcmOption);
            provinceSelect.value = '79';

            // LOAD TẤT CẢ QUẬN/HUYỆN TP HCM
            districtSelect.disabled = false;
            await loadAllDistricts();

            // THIẾT LẬP EVENT LISTENERS
            setupEventListeners();

        } catch (error) {
            console.error('Lỗi khởi tạo địa chỉ:', error);
        }
    }

    // LOAD TẤT CẢ QUẬN/HUYỆN TP HCM
    async function loadAllDistricts() {
        try {
            districtSelect.innerHTML = '<option value="">Đang tải...</option>';
            districtSelect.disabled = true;

            const response = await fetch(`${baseURL}p/79?depth=2`);
            if (!response.ok) throw new Error('Lỗi kết nối API');

            const data = await response.json();

            // LẤY TẤT CẢ QUẬN/HUYỆN KHÔNG GIỚI HẠN
            const allDistricts = data.districts || [];

            // CẬP NHẬT DROPDOWN QUẬN
            districtSelect.innerHTML = '<option value="">Quận/Huyện</option>';
            allDistricts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.code;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });

            districtSelect.disabled = false;

        } catch (error) {
            console.error('Lỗi load quận/huyện:', error);
            // NẾU LỖI, VẪN HIỂN THỊ CÁC QUẬN CHÍNH
            districtSelect.innerHTML = '<option value="">Quận/Huyện</option>';
            const districts = [
                { code: '760', name: 'Quận 1' }, { code: '761', name: 'Quận 3' }, { code: '762', name: 'Quận 4' },
                { code: '763', name: 'Quận 5' }, { code: '764', name: 'Quận 6' }, { code: '765', name: 'Quận 7' },
                { code: '766', name: 'Quận 8' }, { code: '767', name: 'Quận 10' }, { code: '768', name: 'Quận 11' },
                { code: '769', name: 'Quận 12' }, { code: '770', name: 'Quận Bình Thạnh' }, { code: '771', name: 'Quận Gò Vấp' },
                { code: '772', name: 'Quận Phú Nhuận' }, { code: '773', name: 'Quận Tân Bình' }, { code: '774', name: 'Quận Tân Phú' },
                { code: '775', name: 'Quận Bình Tân' }, { code: '776', name: 'Quận Thủ Đức' }, { code: '777', name: 'Huyện Bình Chánh' },
                { code: '778', name: 'Huyện Củ Chi' }, { code: '783', name: 'Huyện Hóc Môn' }, { code: '784', name: 'Huyện Nhà Bè' },
                { code: '785', name: 'Huyện Cần Giờ' }
            ];

            districts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.code;
                option.textContent = district.name;
                districtSelect.appendChild(option);
            });
            districtSelect.disabled = false;
        }
    }

    // LOAD PHƯỜNG/XÃ KHI CHỌN QUẬN
    async function loadWardsByDistrict(districtCode) {
        if (!districtCode) {
            resetWardSelect();
            return;
        }

        try {
            wardSelect.innerHTML = '<option value="">Đang tải...</option>';
            wardSelect.disabled = true;

            const response = await fetch(`${baseURL}d/${districtCode}?depth=2`);
            if (!response.ok) throw new Error('Lỗi kết nối API');

            const data = await response.json();
            const wards = data.wards || [];

            // CẬP NHẬT DROPDOWN PHƯỜNG/XÃ
            wardSelect.innerHTML = '<option value="">Phường/Xã</option>';

            if (wards.length > 0) {
                wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
                wardSelect.disabled = false;
            } else {
                wardSelect.innerHTML = '<option value="">Không có dữ liệu</option>';
                wardSelect.disabled = true;
            }

        } catch (error) {
            console.error('Lỗi load phường/xã:', error);
            wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
            wardSelect.disabled = true;
        }
    }

    function resetWardSelect() {
        wardSelect.innerHTML = '<option value="">Phường/Xã</option>';
        wardSelect.disabled = true;
    }

    // THIẾT LẬP EVENT LISTENERS
    function setupEventListeners() {
        // KHI CHỌN QUẬN
        districtSelect.addEventListener('change', function () {
            loadWardsByDistrict(this.value);
            updateAddress();
        });

        // KHI CHỌN PHƯỜNG/XÃ
        wardSelect.addEventListener('change', updateAddress);

        // KHI NHẬP ĐỊA CHỈ ĐƯỜNG
        streetInput.addEventListener('input', updateAddress);
    }

    // CẬP NHẬT ĐỊA CHỈ HOÀN CHỈNH - CHỈ 1 TP HCM
    function updateAddress() {
        const province = document.getElementById('province');
        const district = document.getElementById('district');
        const ward = document.getElementById('ward');
        const street = document.getElementById('street_address');
        const addressDisplay = document.getElementById('full_address_display');
        const addressInput = document.getElementById('customer_address');

        if (!province || !district || !street || !addressDisplay || !addressInput) return;

        let addressParts = [];
        
        // Chỉ thêm các phần địa chỉ nếu có giá trị
        if (street.value) addressParts.push(street.value);
        if (ward && ward.selectedIndex > 0) addressParts.push(ward.options[ward.selectedIndex].textContent);
        if (district.selectedIndex > 0) addressParts.push(district.options[district.selectedIndex].textContent);
        
    const fullAddress = addressParts.length > 0 ? addressParts.join(', ') + ', TP Hồ Chí Minh'
    : '';

        if (fullAddress) {
            addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt me-2 text-primary"></i><span>${fullAddress}</span>`;
            addressInput.value = fullAddress;
        } else {
            addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt me-2 text-primary"></i><span>Chưa chọn địa chỉ mới</span>`;
            addressInput.value = '';
        }
    }
}

// VALIDATION FUNCTIONS
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
    const regex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
    return regex.test(phone);
}

function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// INIT FORM VALIDATION
function initFormValidation() {
    const form = document.getElementById('profileForm');

    if (!form) return;

    // Real-time phone number validation
    document.getElementById('phone').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });

    // Form submission validation
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        clearAllErrors();
        let hasError = false;

        // Validate full name
        const fullname = document.getElementById('name');
        if (fullname.value.trim() === '') {
            showError(fullname, 'Vui lòng nhập họ và tên');
            hasError = true;
        }

        // Validate phone
        const phone = document.getElementById('phone');
        if (phone.value.trim() === '') {
            showError(phone, 'Vui lòng nhập số điện thoại');
            hasError = true;
        } else if (!isValidPhone(phone.value.trim())) {
            showError(phone, 'Số điện thoại không hợp lệ');
            hasError = true;
        }

        // Validate email (optional)
        const email = document.getElementById('email');
        if (email.value.trim() !== '' && !isValidEmail(email.value.trim())) {
            showError(email, 'Email không hợp lệ');
            hasError = true;
        }

        if (!hasError) {
            form.submit();
        }
    });
}

function confirmUpdateProfile() {
    showConfirm(
        `Bạn có chắc chắn muốn cập nhật thông tin?`,
        'Xác nhận cập nhật thông tin',
        function() {
            document.querySelector('form').submit();
        }
    );
}
</script>