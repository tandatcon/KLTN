<?php
session_start();
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}
// ================= DEBUG CHECK =================
error_log("=== DEBUG DATDICHVU.PHP ===");
error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'NULL'));
error_log("REQUEST METHOD: " . $_SERVER['REQUEST_METHOD']);
error_log("REQUEST URI: " . ($_SERVER['REQUEST_URI'] ?? ''));
// ================= END DEBUG =================
$pageTitle = "Đặt dịch vụ - TechCare";
include VIEWS_PATH . '/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để đặt lịch!";
    header('Location: ' . url('login'));
    exit();
}

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
$currentHour = 12;

// Lấy dữ liệu
$devices = $dichVuService->layDanhSachThietBi();
$danhSachKhungGio = $dichVuService->layDanhSachKhungGio();

$maKH = $_SESSION['user_id'] ?? [];
$userInfo = $khachhang->layKHByID($maKH)
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
        <form id="serviceBookingForm" action="<?php echo url('process_booking'); ?>" method="POST" novalidate>
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
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Họ và tên *</label>
                                    <input type="text" class="form-control input-gray" id="customer_name"
                                        name="customer_name"
                                        value="<?php echo isset($userInfo['hoTen']) ? htmlspecialchars($userInfo['hoTen']) : ''; ?>"
                                        placeholder="Nhập họ và tên">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Số điện thoại *</label>
                                    <input type="tel" class="form-control input-gray" id="customer_phone"
                                        name="customer_phone"
                                        value="<?php echo isset($userInfo['sdt']) ? htmlspecialchars($userInfo['sdt']) : ''; ?>"
                                        placeholder="Nhập số điện thoại">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Địa chỉ *</label>
                                    <div class="address-select-container mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <select class="form-select input-gray" id="province" name="province">
                                                    <option value="">Thành phố</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-select input-gray" id="district" name="district"
                                                    disabled>
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
                                                    name="street_address" placeholder="Số nhà, tên đường">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-2 border border-gray rounded bg-light">
                                        <div id="full_address_display" class="small">
                                            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                            <span>Chưa có địa chỉ</span>
                                        </div>
                                    </div>
                                    <input type="hidden" id="customer_address" name="customer_address">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả sự cố -->
                    <div class="card border-gray">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-tools me-2"></i>Mô tả dòng máy, sự cố
                            </h5>
                            <div class="devices-container">
                                <div class="device-item mb-3">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <h6 class="mb-0 text-primary">Thiết bị 1</h6>
                                            <label class="form-label">Loại thiết bị *</label>
                                            <select class="form-select input-gray device-type-select"
                                                name="device_types[]">
                                                <option value="">Chọn loại thiết bị</option>
                                                <?php foreach ($devices as $device): ?>
                                                    <option value="<?php echo $device['maThietBi']; ?>">
                                                        <?php echo htmlspecialchars($device['tenThietBi']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Thông tin phiên bản / thương hiệu</label>
                                            <input type="text" class="form-control input-gray" name="device_models[]"
                                                placeholder="VD: Panasonic Inverter 1HP CU/CS-PU9AKH-8 ...">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Mô tả tình trạng *</label>
                                            <textarea class="form-control input-gray" name="device_problems[]" rows="3"
                                                placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="additional-devices"></div>
                                <div class="text-center mt-3">
                                    <button type="button" id="btn-add-device" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-plus me-1"></i>Thêm thiết bị khác
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
                                <div class="mt-3 p-3 bg-light rounded small" id="slot-info-display">
                                    <p class="text-muted mb-0">Chọn ngày để xem thông tin phân bổ KTV</p>
                                </div>
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
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                                    <i class="fas fa-bolt me-2"></i>ĐẶT LỊCH NGAY
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Banner waiting image -->
        <div class="banner-image-container text-center mt-4">
            <img src="<?php echo asset('images/waitting.jpg'); ?>" alt="TechCare Banner" class="banner-image">
        </div>

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

    // DANH SÁCH QUẬN ĐƯỢC PHÉP
    const allowedDistricts = ['764', '761', '765', '766', '768', '784'];

    // KHỞI TẠO KHI TRANG LOAD
    document.addEventListener('DOMContentLoaded', function () {
        console.log("🚀 DOM Content Loaded - Khởi tạo trang đặt dịch vụ");

        initAddressAPI();
        generateDateGrid();
        loadSlotsForDate(currentSelectedDate);
        initDeviceManagement();
        initFormValidation();
    });

    // ==============================
    // 🔧 VALIDATION HELPER FUNCTIONS
    // ==============================
    function showError(input, message) {
        // Xóa lỗi cũ (nếu có)
        const oldError = input.parentElement.querySelector('.error-message');
        if (oldError) oldError.remove();

        // Thêm lỗi mới
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

            // --- Họ tên ---
            const name = document.getElementById('customer_name');
            if (name.value.trim() === '') {
                showError(name, 'Vui lòng nhập họ và tên');
                hasError = true;
            }

            // --- Số điện thoại ---
            const phone = document.getElementById('customer_phone');
            if (phone.value.trim() === '') {
                showError(phone, 'Vui lòng nhập số điện thoại');
                hasError = true;
            } else if (!isValidPhone(phone.value.trim())) {
                showError(phone, 'Số điện thoại không hợp lệ');
                hasError = true;
            }

            // --- Địa chỉ ---
            const province = document.getElementById('province');
            const district = document.getElementById('district');
            const ward = document.getElementById('ward');
            const street = document.getElementById('street_address');

            if (province.value === '') {
                showError(province, 'Vui lòng chọn thành phố');
                hasError = true;
            }
            if (district.value === '') {
                showError(district, 'Vui lòng chọn quận/huyện');
                hasError = true;
            }
            if (ward.value === '') {
                showError(ward, 'Vui lòng chọn phường/xã');
                hasError = true;
            }
            if (street.value.trim() === '') {
                showError(street, 'Vui lòng nhập số nhà, tên đường');
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
                const problem = block.querySelector('textarea[name="device_problems[]"]');
                const model = block.querySelector('input[name="device_models[]"]');

                if (deviceType && deviceType.value === '') {
                    showError(deviceType, `Vui lòng chọn loại thiết bị ${index + 1}`);
                    hasError = true;
                }

                if (problem && problem.value.trim() === '') {
                    showError(problem, `Vui lòng mô tả tình trạng của thiết bị ${index + 1}`);
                    hasError = true;
                }
                if (model && model.value.trim() === '') { // Kiểm tra bắt buộc nhập phiên bản/thương hiệu
                    showError(model, `Vui lòng nhập thông tin phiên bản/thương hiệu thiết bị ${index + 1}`);
                    hasError = true;
                }
            });

            // Nếu có lỗi -> cuộn lên đầu
            // Nếu có lỗi -> hiện thông báo chung + cuộn lên đầu
            if (hasError) {
                showConfirm(
                    'Vui lòng nhập đầy đủ thông tin trước khi xác nhận đặt lịch!',
                    'Thiếu thông tin'
                );
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }


            // --- Nếu hợp lệ -> Gửi form hoặc hiển thị xác nhận ---
            showBookingConfirmation(); // Hàm có sẵn trong code của bạn
        });
    }

    // Gọi hàm khởi tạo sau khi DOM tải xong
    document.addEventListener('DOMContentLoaded', initFormValidation);

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

    // QUẢN LÝ ĐỊA CHỈ VỚI API
    function initAddressAPI() {
        console.log("📍 Khởi tạo Address API");
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

                // LOAD QUẬN/HUYỆN
                districtSelect.disabled = false;
                await loadDistricts();

                // THIẾT LẬP EVENT LISTENERS
                setupEventListeners();

                // CẬP NHẬT ĐỊA CHỈ BAN ĐẦU
                updateAddress();

            } catch (error) {
                console.error('Lỗi khởi tạo địa chỉ:', error);
            }
        }

        // LOAD DANH SÁCH QUẬN/HUYỆN
        async function loadDistricts() {
            try {
                districtSelect.innerHTML = '<option value="">Đang tải...</option>';
                districtSelect.disabled = true;

                const response = await fetch(`${baseURL}p/79?depth=2`);
                if (!response.ok) throw new Error('Lỗi kết nối API');

                const data = await response.json();

                // LỌC CHỈ CÁC QUẬN ĐƯỢC PHÉP
                const filteredDistricts = data.districts.filter(district =>
                    allowedDistricts.includes(district.code.toString())
                );

                // CẬP NHẬT DROPDOWN QUẬN
                districtSelect.innerHTML = '<option value="">Quận/Huyện</option>';
                filteredDistricts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });

                districtSelect.disabled = false;

            } catch (error) {
                console.error('Lỗi load quận/huyện:', error);
                // NẾU LỖI, VẪN HIỂN THỊ CÁC QUẬN ĐƯỢC PHÉP
                districtSelect.innerHTML = '<option value="">Quận/Huyện</option>';
                const districts = [
                    { code: '764', name: 'Quận 1' },
                    { code: '761', name: 'Quận 3' },
                    { code: '765', name: 'Quận 4' },
                    { code: '766', name: 'Quận 5' },
                    { code: '768', name: 'Quận 10' },
                    { code: '784', name: 'Quận Bình Thạnh' }
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
                // KIỂM TRA QUẬN CÓ ĐƯỢC PHÉP KHÔNG
                if (!allowedDistricts.includes(districtCode)) {
                    wardSelect.innerHTML = '<option value="">Quận không được hỗ trợ</option>';
                    wardSelect.disabled = true;
                    return;
                }

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

    // TẠO LƯỚI NGÀY (GIỮ NGUYÊN)
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

    // LOAD SLOTS THEO NGÀY (GIỮ NGUYÊN)
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

        // TÍNH GIỜ HIỆN TẠI THEO NGÀY
        const selectedDate = new Date(date);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);

        let currentHour;
        if (selectedDate.getTime() === today.getTime()) {
            currentHour = new Date().getHours(); // Hôm nay
        } else {
            currentHour = 0; // Ngày khác → chưa qua giờ nào
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

    // CẬP NHẬT HIỂN THỊ SLOTS (GIỮ NGUYÊN)
    function updateSlotsDisplay(slots, date) {
        const timeContainer = document.getElementById('time-slots-container');
        const infoDisplay = document.getElementById('slot-info-display');

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

            // Sử dụng cấu trúc dữ liệu từ test slot
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
                    <div class="slot-info mt-1">
                        ${!isAvailable ?
                    `<small class="text-danger">${reason}</small>` :
                    `<small class="text-success">Còn ${slot.kha_dung || 0} slot</small>`
                }
                    </div>
                </label>
            </div>
        `;
            timeContainer.appendChild(slotElement);
        });

        // Cập nhật thông tin phân bổ
        if (infoDisplay && slots.length > 0) {
            const firstSlot = slots[0];
            const totalTechs = firstSlot.tong_ktv || 0;
            const dateObj = new Date(date);
            const formattedDate = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;

            let html = `<h6 class="text-primary mb-2">Thông tin phân bổ KTV ngày ${formattedDate}</h6>
                   <div class="row">
                       <div class="col-12 mb-2">
                           <strong>Tổng KTV làm việc:</strong> ${totalTechs}
                       </div>`;

            slots.forEach(slot => {
                const statusText = !slot.kha_dung_bool ?
                    `<span class="text-danger">(${slot.ly_do || 'Không khả dụng'})</span>` :
                    `<span class="text-success">(Có thể đặt)</span>`;

                html += `<div class="col-md-6 col-lg-4 mb-2">
                        <strong>${slot.pham_vi || slot.khoangGio} ${statusText}</strong><br>
                        Đã đặt: ${slot.da_dat || 0}/${slot.toi_da || 0}<br>
                        Còn lại: ${slot.kha_dung || 0}
                     </div>`;
            });

            html += '</div>';
            infoDisplay.innerHTML = html;
        }
    }

    // QUẢN LÝ THIẾT BỊ (GIỮ NGUYÊN)
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
        newDevice.className = 'device-item mb-3 p-3 border border-gray rounded';
        newDevice.setAttribute('data-device-index', index);

        const deviceOptions = `<?php
        $options = '';
        foreach ($devices as $device) {
            $options .= '<option value=\"' . $device['maThietBi'] . '\">' . htmlspecialchars($device['tenThietBi']) . '</option>';
        }
        echo $options;
        ?>`;

        newDevice.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-primary">Thiết bị ${index}</h6>
            <button type="button" class="btn btn-danger btn-sm btn-remove-device">
                <i class="fas fa-times me-1"></i>Xóa
            </button>
        </div>
        <div class="row g-2">
            <div class="col-12">
                <label class="form-label">Loại thiết bị *</label>
                <select class="form-select input-gray" name="device_types[]">
                    <option value="">Chọn loại thiết bị</option>
                    ${deviceOptions}
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Thông tin thiết bị</label>
                <input type="text" class="form-control input-gray" name="device_models[]" 
                    placeholder="VD: Dell Inspiron 15, iPhone 13...">
            </div>
            <div class="col-12">
                <label class="form-label">Mô tả tình trạng *</label>
                <textarea class="form-control input-gray" name="device_problems[]" rows="2"
                        placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
            </div>
        </div>
    `;

        newDevice.querySelector('.btn-remove-device').addEventListener('click', function () {
            newDevice.remove();
            deviceCount--;
            updateDeviceNumbers();
        });

        additionalDevices.appendChild(newDevice);
    }

    function updateDeviceNumbers() {
        const deviceItems = document.querySelectorAll('.device-item');
        deviceItems.forEach((item, index) => {
            const title = item.querySelector('h6');
            if (title) {
                title.textContent = `Thiết bị ${index + 1}`;
            }
        });
    }
</script>

<?php include VIEWS_PATH . '/footer.php'; ?>

<!-- --------------------------------------- -->
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
                                <i class="fas fa-tools me-2"></i>Mô tả dòng máy, sự cố
                            </h5>
                            <div class="devices-container">
                                <div class="device-item mb-3">
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <h6 class="mb-0 text-primary">Thiết bị 1</h6>
                                            <label class="form-label">Loại thiết bị *</label>
                                            <select class="form-select input-gray device-type-select"
                                                name="device_types[]">
                                                <option value="">Chọn loại thiết bị</option>
                                                <?php foreach ($devices as $device): ?>
                                                    <option value="<?php echo $device['maThietBi']; ?>">
                                                        <?php echo htmlspecialchars($device['tenThietBi']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Thông tin phiên bản / thương hiệu</label>
                                            <input type="text" class="form-control input-gray" name="device_models[]"
                                                placeholder="VD: Panasonic Inverter 1HP CU/CS-PU9AKH-8 ...">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Mô tả tình trạng *</label>
                                            <textarea class="form-control input-gray" name="device_problems[]" rows="3"
                                                placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="additional-devices"></div>
                                <div class="text-center mt-3">
                                    <button type="button" id="btn-add-device" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-plus me-1"></i>Thêm thiết bị khác
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
                                <div class="mt-3 p-3 bg-light rounded small" id="slot-info-display">
                                    <p class="text-muted mb-0">Chọn ngày để xem thông tin phân bổ KTV</p>
                                </div>
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
                                    <?php echo (empty($userInfo['diaChi']) || empty($userInfo['sdt'])) ? 'disabled' : ''; ?>>
                                    <i class="fas fa-bolt me-2"></i>
                                    <?php echo (empty($userInfo['diaChi']) || empty($userInfo['sdt'])) ? 'VUI LÒNG CẬP NHẬT THÔNG TIN' : 'ĐẶT LỊCH NGAY'; ?>
                                </button>
                                
                                <?php if (empty($userInfo['diaChi']) || empty($userInfo['sdt'])): ?>
                                    <div class="mt-2">
                                        <small class="text-danger">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Cần cập nhật số điện thoại và địa chỉ để đặt lịch
                                        </small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Banner waiting image -->
        <div class="banner-image-container text-center mt-4">
            <img src="<?php echo asset('images/waitting.jpg'); ?>" alt="TechCare Banner" class="banner-image">
        </div>

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
    });

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
                const problem = block.querySelector('textarea[name="device_problems[]"]');
                const model = block.querySelector('input[name="device_models[]"]');

                if (deviceType && deviceType.value === '') {
                    showError(deviceType, `Vui lòng chọn loại thiết bị ${index + 1}`);
                    hasError = true;
                }

                if (problem && problem.value.trim() === '') {
                    showError(problem, `Vui lòng mô tả tình trạng của thiết bị ${index + 1}`);
                    hasError = true;
                }
                if (model && model.value.trim() === '') {
                    showError(model, `Vui lòng nhập thông tin phiên bản/thương hiệu thiết bị ${index + 1}`);
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

    // TẠO LƯỚI NGÀY (GIỮ NGUYÊN)
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

    // LOAD SLOTS THEO NGÀY (GIỮ NGUYÊN)
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

    // CẬP NHẬT HIỂN THỊ SLOTS (GIỮ NGUYÊN)
    function updateSlotsDisplay(slots, date) {
        const timeContainer = document.getElementById('time-slots-container');
        const infoDisplay = document.getElementById('slot-info-display');

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
                    <div class="slot-info mt-1">
                        ${!isAvailable ?
                    `<small class="text-danger">${reason}</small>` :
                    `<small class="text-success">Còn ${slot.kha_dung || 0} slot</small>`
                }
                    </div>
                </label>
            </div>
        `;
            timeContainer.appendChild(slotElement);
        });

        if (infoDisplay && slots.length > 0) {
            const firstSlot = slots[0];
            const totalTechs = firstSlot.tong_ktv || 0;
            const dateObj = new Date(date);
            const formattedDate = `${dateObj.getDate()}/${dateObj.getMonth() + 1}/${dateObj.getFullYear()}`;

            let html = `<h6 class="text-primary mb-2">Thông tin phân bổ KTV ngày ${formattedDate}</h6>
                   <div class="row">
                       <div class="col-12 mb-2">
                           <strong>Tổng KTV làm việc:</strong> ${totalTechs}
                       </div>`;

            slots.forEach(slot => {
                const statusText = !slot.kha_dung_bool ?
                    `<span class="text-danger">(${slot.ly_do || 'Không khả dụng'})</span>` :
                    `<span class="text-success">(Có thể đặt)</span>`;

                html += `<div class="col-md-6 col-lg-4 mb-2">
                        <strong>${slot.pham_vi || slot.khoangGio} ${statusText}</strong><br>
                        Đã đặt: ${slot.da_dat || 0}/${slot.toi_da || 0}<br>
                        Còn lại: ${slot.kha_dung || 0}
                     </div>`;
            });

            html += '</div>';
            infoDisplay.innerHTML = html;
        }
    }

    // QUẢN LÝ THIẾT BỊ (GIỮ NGUYÊN)
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
        newDevice.className = 'device-item mb-3 p-3 border border-gray rounded';
        newDevice.setAttribute('data-device-index', index);

        const deviceOptions = `<?php
        $options = '';
        foreach ($devices as $device) {
            $options .= '<option value=\"' . $device['maThietBi'] . '\">' . htmlspecialchars($device['tenThietBi']) . '</option>';
        }
        echo $options;
        ?>`;

        newDevice.innerHTML = `
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0 text-primary">Thiết bị ${index}</h6>
            <button type="button" class="btn btn-danger btn-sm btn-remove-device">
                <i class="fas fa-times me-1"></i>Xóa
            </button>
        </div>
        <div class="row g-2">
            <div class="col-12">
                <label class="form-label">Loại thiết bị *</label>
                <select class="form-select input-gray" name="device_types[]">
                    <option value="">Chọn loại thiết bị</option>
                    ${deviceOptions}
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Thông tin thiết bị</label>
                <input type="text" class="form-control input-gray" name="device_models[]" 
                    placeholder="VD: Dell Inspiron 15, iPhone 13...">
            </div>
            <div class="col-12">
                <label class="form-label">Mô tả tình trạng *</label>
                <textarea class="form-control input-gray" name="device_problems[]" rows="2"
                        placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
            </div>
        </div>
    `;

        newDevice.querySelector('.btn-remove-device').addEventListener('click', function () {
            newDevice.remove();
            deviceCount--;
            updateDeviceNumbers();
        });

        additionalDevices.appendChild(newDevice);
    }

    function updateDeviceNumbers() {
        const deviceItems = document.querySelectorAll('.device-item');
        deviceItems.forEach((item, index) => {
            const title = item.querySelector('h6');
            if (title) {
                title.textContent = `Thiết bị ${index + 1}`;
            }
        });
    }
</script>

<?php include VIEWS_PATH . '/footer.php'; ?>