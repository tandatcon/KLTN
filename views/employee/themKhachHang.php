<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

session_start();
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../config.php';
    require_once __DIR__ . '/../../helpers.php';
}

$pageTitle = "Thêm khách hàng - TechCare";

// Khởi tạo service
require_once __DIR__ . '/../../function/khachhang.php';
$khachhang = new KhachHang($db);

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}

// Lấy số điện thoại từ URL nếu có
$phoneFromURL = $_GET['sdt'] ?? '';

// Xử lý thêm khách hàng
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['customer_address'] ?? '');

    // Validate dữ liệu
    if (empty($fullname) || empty($phone) || empty($address)) {
        $error = 'Vui lòng điền đầy đủ thông tin bắt buộc.';
    } elseif (!preg_match('/^(0[3|5|7|8|9])+([0-9]{8})$/', $phone)) {
        $error = 'Số điện thoại không hợp lệ.';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email không hợp lệ.';
    } else {
        // Kiểm tra số điện thoại đã tồn tại chưa
        $existingCustomer = $khachhang->layKHBySDT($phone);
        if ($existingCustomer) {
            $error = 'Số điện thoại này đã được sử dụng bởi khách hàng khác.';
        } else {
            // Thêm khách hàng mới
            $result = $khachhang->themKhachHang($fullname, $phone, $email, $address);
            
            if ($result) {
                $success = "🎉 Thêm khách hàng thành công!";
                
                // Chuyển hướng về trang đăng ký dịch vụ sau 2 giây
                //header('Refresh: 2; URL=' . url('employee/dang-ky-dich-vu') . '?sdt=' . urlencode($phone));
            } else {
                $error = 'Thêm khách hàng thất bại. Vui lòng thử lại.';
            }
        }
    }
}

// Include header
include __DIR__ . '/../header.php';
?>

<!-- Register Form -->
<section class="min-vh-100 d-flex align-items-center bg-light py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-body p-5">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <h2 class="text-primary fw-bold">
                                <i class="fas fa-user-plus me-2"></i>THÊM KHÁCH HÀNG MỚI
                            </h2>
                            <p class="text-muted">Thêm thông tin khách hàng mới vào hệ thống</p>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $success; ?>
                                <br>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" id="registerForm">
                            <!-- Full Name Input -->
                            <div class="mb-3">
                                <label for="fullname" class="form-label fw-semibold">Họ và tên *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" id="fullname" name="fullname"
                                        value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                        required placeholder="Nhập họ và tên khách hàng">
                                </div>
                            </div>

                            <!-- Phone Input -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại *</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-phone text-muted"></i>
                                    </span>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : $phoneFromURL; ?>"
                                        required placeholder="Nhập số điện thoại" pattern="(0[3|5|7|8|9])+([0-9]{8})">
                                </div>
                                <div class="form-text">Định dạng: 09xxxxxxxx hoặc 03xxxxxxxx</div>
                            </div>

                            <!-- Email Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                        placeholder="Nhập email (không bắt buộc)">
                                </div>
                                <div class="form-text">Email giúp khách hàng nhận thông báo</div>
                            </div>

                            <!-- Address Input -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Địa chỉ *</label>
                                <div class="address-select-container mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select class="form-select input-gray" id="province" name="province">
                                                <option value="">Thành phố</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <select class="form-select input-gray" id="district" name="district" disabled>
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
                                            <input type="text" class="form-control input-gray" id="street_address"
                                                name="street_address" placeholder="Số nhà, tên đường"
                                                value="<?php echo isset($_POST['street_address']) ? htmlspecialchars($_POST['street_address']) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 border border-gray rounded bg-light">
                                    <div id="full_address_display" class="small">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                        <span>Chưa có địa chỉ</span>
                                    </div>
                                </div>
                                <input type="hidden" id="customer_address" name="customer_address" value="<?php echo isset($_POST['customer_address']) ? htmlspecialchars($_POST['customer_address']) : ''; ?>">
                                <div class="form-text">Vui lòng chọn địa chỉ thuộc TP Hồ Chí Minh</div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg py-2 fw-semibold">
                                    <i class="fas fa-save me-2"></i> Thêm khách hàng
                                </button>
                                <a href="<?php echo url('employee/dang-ky-dich-vu'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Quay lại đăng ký dịch vụ
                                </a>
                            </div>
                        </form>

                        <!-- Hướng dẫn -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Thông tin hệ thống
                            </h6>
                            <ul class="list-unstyled text-muted small">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Khách hàng sẽ được tạo tài khoản tự động</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Username: Số điện thoại</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Password mặc định: 123456</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Sau khi thêm thành công, hệ thống sẽ tự động chuyển về trang đăng ký dịch vụ</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include __DIR__ . '/../footer.php';
?>

<style>
    .min-vh-100 {
        min-height: 100vh;
    }

    .card {
        border: none;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0dcaf0);
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
    }

    .input-group-text {
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: #ced4da;
        box-shadow: none;
    }

    .input-group:focus-within .input-group-text {
        border-color: #0d6efd;
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

    .alert-info {
        border-left: 4px solid #17a2b8;
    }

    /* Responsive */
    @media (max-width: 576px) {
        .card-body {
            padding: 2rem 1.5rem !important;
        }
    }
</style>

<script>
    // KHỞI TẠO KHI TRANG LOAD
    document.addEventListener('DOMContentLoaded', function () {
        console.log("🚀 DOM Content Loaded - Khởi tạo trang thêm khách hàng");
        initAddressAPI();
        initFormValidation();
    });

    // QUẢN LÝ ĐỊA CHỈ VỚI API - HỖ TRỢ TOÀN BỘ TP HCM
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

                // CẬP NHẬT ĐỊA CHỈ BAN ĐẦU
                updateAddress();

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

        // CẬP NHẬT ĐỊA CHỈ HOÀN CHỈNH
        function updateAddress() {
            const province = document.getElementById('province');
            const district = document.getElementById('district');
            const ward = document.getElementById('ward');
            const street = document.getElementById('street_address');
            const addressDisplay = document.getElementById('full_address_display');
            const addressInput = document.getElementById('customer_address');

            if (!province || !district || !street || !addressDisplay || !addressInput) return;

            let addressParts = [];
            if (street.value) addressParts.push(street.value);
            if (ward && ward.selectedIndex > 0) addressParts.push(ward.options[ward.selectedIndex].textContent);
            if (district.selectedIndex > 0) addressParts.push(district.options[district.selectedIndex].textContent);
            if (province.selectedIndex > 0) addressParts.push(province.options[province.selectedIndex].textContent);

            const fullAddress = addressParts.join(', ');

            if (fullAddress) {
                addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt me-2 text-primary"></i><span>${fullAddress}</span>`;
                addressInput.value = fullAddress;
            } else {
                addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt me-2 text-primary"></i><span>Chưa có địa chỉ</span>`;
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
        const form = document.getElementById('registerForm');

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
            const fullname = document.getElementById('fullname');
            if (fullname.value.trim() === '') {
                showError(fullname, 'Vui lòng nhập họ và tên khách hàng');
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

            // Validate address
            const customerAddress = document.getElementById('customer_address');
            if (!customerAddress.value || customerAddress.value.trim() === '') {
                showError(customerAddress, 'Vui lòng chọn địa chỉ');
                hasError = true;
            }

            if (!hasError) {
                if (confirm('Bạn có chắc chắn muốn thêm khách hàng này?')) {
                    form.submit();
                }
            }
        });
    }
</script>