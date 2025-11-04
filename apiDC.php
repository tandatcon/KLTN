<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Đặt dịch vụ - TechCare";
include VIEWS_PATH . '/header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('login') . '?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

require_once __DIR__ . '/../controllers/BookingController.php';
$bookingController = new BookingController($db);
$data = $bookingController->showBookingPage();

$devices = $data['devices'];
$bookedSchedules = $bookingController->getBookedSchedules();
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-primary mb-3">Đặt dịch vụ sửa chữa tại nhà</h1>
                    <p class="lead text-muted">Để lại thông tin, chúng tôi sẽ liên hệ xác nhận trong thời gian sớm nhất</p>
                </div>

                

                <!-- Booking Form -->
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4 p-md-5">
                        <form id="serviceBookingForm" action="<?php echo url('process_booking'); ?>" method="POST">
                            <!-- Hidden field để lưu loại dịch vụ -->
                            <input type="hidden" id="service_type" name="service_type" value="immediate">
                            <!-- Hidden fields cho ngày giờ mặc định khi đặt dịch vụ ngay -->
                            <input type="hidden" id="immediate_date" name="immediate_date" value="<?php echo date('Y-m-d'); ?>">
                            <input type="hidden" id="immediate_time" name="immediate_time" value="chieu">

                            <!-- Thông tin khách hàng -->
                            <div class="mb-5">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="fas fa-user me-2"></i>Thông tin khách hàng
                                </h3>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="customer_name" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="customer_name"
                                            name="customer_name"
                                            value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>"
                                            required placeholder="Nhập họ và tên">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="customer_phone" class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-lg" id="customer_phone"
                                            name="customer_phone"
                                            value="<?php echo isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : ''; ?>"
                                            required placeholder="Nhập số điện thoại">
                                    </div>

                                    <!-- PHẦN ĐỊA CHỈ VỚI API -->
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Địa chỉ <span class="text-danger">*</span></label>
                                        
                                        <!-- Select địa chỉ tự động -->
                                        <div class="address-select-container mb-3">
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label class="form-label small">Thành phố</label>
                                                    <select class="form-select" id="province" name="province">
                                                        <option value="">Chọn thành phố</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Quận/Huyện</label>
                                                    <select class="form-select" id="district" name="district" disabled>
                                                        <option value="">Chọn quận/huyện</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small">Phường/Xã</label>
                                                    <select class="form-select" id="ward" name="ward" disabled>
                                                        <option value="">Chọn phường/xã</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row g-2 mt-2">
                                                <div class="col-12">
                                                    <label class="form-label small">Số nhà, tên đường</label>
                                                    <input type="text" class="form-control" id="street_address" 
                                                           placeholder="VD: Số 12, đường Nguyễn Văn Linh">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Ô HIỂN THỊ ĐỊA CHỈ ĐẦY ĐỦ -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Địa chỉ đầy đủ</label>
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <div id="full_address_display" class="text-muted">
                                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                        <span>Chưa có địa chỉ</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="customer_address" name="customer_address" required>
                                        </div>

                                        <!-- Fallback địa chỉ thủ công -->
                                        <div class="manual-address-container" style="display: none;">
                                            <textarea class="form-control" id="customer_address_fallback" name="customer_address_fallback"
                                                rows="3"
                                                placeholder="Số nhà, tên đường, phường/xã, quận/huyện, thành phố"></textarea>
                                            <div class="form-text text-info mt-2">
                                                <i class="fas fa-info-circle me-1"></i>Hiện tại chúng tôi hỗ trợ khu vực: Gò Vấp, Bình Thạnh, Tân Bình, Phú Nhuận
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thiết bị cần sửa chữa -->
                            <div class="mb-5">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="fas fa-tools me-2"></i>Thiết bị cần sửa chữa
                                </h3>

                                <div class="devices-container">
                                    <!-- Thiết bị 1 -->
                                    <div class="card mb-3 device-item" data-device-index="1">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0 text-dark">Thiết bị 1</h5>
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-device" style="display: none;">
                                                <i class="fas fa-times me-1"></i>Xóa
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label for="device_type_1" class="form-label fw-semibold">Loại thiết bị <span class="text-danger">*</span></label>
                                                    <select class="form-select device-type-select" id="device_type_1" name="device_types[]" required>
                                                        <option value="">Chọn loại thiết bị</option>
                                                        <?php foreach ($devices as $device): ?>
                                                            <option value="<?php echo $device['maThietBi']; ?>">
                                                                <?php echo htmlspecialchars($device['tenThietBi']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="device_model_1" class="form-label fw-semibold">Thông tin thiết bị</label>
                                                    <input type="text" class="form-control" id="device_model_1" name="device_models[]"
                                                        placeholder="VD: Dell Inspiron 15, iPhone 13...">
                                                </div>

                                                <div class="col-12">
                                                    <label for="device_problem_1" class="form-label fw-semibold">Mô tả tình trạng hư hỏng <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" id="device_problem_1" name="device_problems[]" required rows="3"
                                                        placeholder="Mô tả chi tiết tình trạng hư hỏng, lỗi gặp phải..."></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Các thiết bị khác sẽ được thêm vào đây -->
                                    <div id="additional-devices"></div>

                                    <!-- Nút thêm thiết bị -->
                                    <div class="text-center border-2 border-dashed rounded py-4 add-device-section">
                                        <button type="button" id="btn-add-device" class="btn btn-success btn-lg">
                                            <i class="fas fa-plus me-2"></i>Thêm thiết bị khác
                                        </button>
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle me-1"></i>Tối đa 5 thiết bị mỗi đơn
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PHẦN THỜI GIAN (CHỈ HIỆN KHI CHỌN ĐẶT LỊCH HẸN) -->
                            <div class="mb-5 schedule-section" style="display: none;">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="fas fa-calendar-alt me-2"></i>Thời gian đặt lịch
                                </h3>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="booking_date" class="form-label fw-semibold">Ngày đặt lịch <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="booking_date" name="booking_date"
                                            min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                        <div class="form-text" id="date_status"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="booking_time" class="form-label fw-semibold">Khung giờ <span class="text-danger">*</span></label>
                                        <select class="form-select" id="booking_time" name="booking_time">
                                            <option value="">Chọn khung giờ</option>
                                            <option value="sang">Sáng (8:00 - 11:00)</option>
                                            <option value="chieu">Chiều (13:00 - 17:00)</option>
                                            <option value="toi">Tối (18:00 - 21:00)</option>
                                        </select>
                                        <div class="form-text" id="time_status"></div>
                                    </div>

                                    <!-- Hiển thị thông tin lịch đã đặt -->
                                    <div class="col-12 mt-3">
                                        <div id="schedule_availability" class="alert alert-info" style="display: none;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="availability_message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thời gian đặt lịch -->
                            <div class="mb-5">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="fas fa-calendar-alt me-2"></i>Thời gian đặt lịch
                                </h3>

                                <!-- CHECKBOX SỬA CHỮA NGAY -->
                                <div class="mb-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="immediate_service"
                                            name="immediate_service" value="1">
                                        <label class="form-check-label fw-bold text-danger" for="immediate_service">
                                            <i class="fas fa-bolt me-1"></i>Sửa chữa ngay - Yêu cầu xử lý ưu tiên
                                        </label>
                                    </div>
                                    <div class="form-text text-muted ms-4">
                                        <i class="fas fa-info-circle me-1"></i>Kỹ thuật viên sẽ liên hệ và đến sớm nhất
                                        có thể (phí dịch vụ +20%)
                                    </div>
                                </div>
                                <hr>
                                <div class="row g-3" id="schedule_fields">
                                    <div class="col-md-6">
                                        <label for="booking_date" class="form-label fw-semibold">Ngày đặt lịch <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="booking_date" name="booking_date"
                                            required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                                        <div class="form-text" id="date_status"></div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="booking_time" class="form-label fw-semibold">Khung giờ <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="booking_time" name="booking_time" required>
                                            <option value="">Chọn khung giờ</option>
                                            <option value="sang">Sáng (8:00 - 11:00)</option>
                                            <option value="chieu">Chiều (13:00 - 17:00)</option>
                                            <option value="toi">Tối (18:00 - 21:00)</option>
                                        </select>
                                        <div class="form-text" id="time_status"></div>
                                    </div>

                                    <!-- Hiển thị thông tin lịch đã đặt -->
                                    <div class="col-12 mt-3">
                                        <div id="schedule_availability" class="alert alert-info" style="display: none;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="availability_message"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Thông tin bổ sung -->
                            <div class="mb-5">
                                <h3 class="h4 text-primary mb-4">
                                    <i class="fas fa-comment-dots me-2"></i>Thông tin bổ sung
                                </h3>

                                <div class="mb-3">
                                    <label for="problem_description" class="form-label fw-semibold">Ghi chú thêm</label>
                                    <textarea class="form-control" id="problem_description" name="problem_description"
                                        rows="4"
                                        placeholder="Các yêu cầu đặc biệt, thời gian tiếp nhận phù hợp, ghi chú khác..."></textarea>
                                </div>
                            </div>

                            <!-- Nút đăng ký -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger btn-lg px-5 py-3" id="submit_btn">
                                    <i class="fas fa-bolt me-2"></i>Đặt dịch vụ ngay
                                </button>
                                <div class="form-text mt-3">
                                    <i class="fas fa-info-circle me-1"></i>Nhân viên sẽ gọi điện xác nhận trong vòng 30 phút
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/footer.php'; ?>

<!-- CSS styles giữ nguyên như trước -->
<style>
    .border-2 {
        border-width: 2px !important;
    }
    .border-dashed {
        border-style: dashed !important;
    }
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
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-danger {
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary:hover,
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .btn-success {
        transition: all 0.3s ease;
    }
    .btn-success:hover {
        transform: translateY(-2px);
    }
    .device-item {
        transition: all 0.3s ease;
    }
    .device-item:hover {
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.1);
    }
    .new-device {
        animation: slideDown 0.4s ease;
    }
    .service-option {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    .service-option:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .service-option.active {
        border-color: #3498db;
        box-shadow: 0 5px 15px rgba(52, 152, 219, 0.2);
    }
    #full_address_display {
        font-size: 1.1rem;
        font-weight: 500;
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
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .display-5 {
            font-size: 2rem;
        }
        .card-body {
            padding: 1.5rem !important;
        }
        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
        }
    }
    @media (max-width: 576px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        .card-body {
            padding: 1rem !important;
        }
    }
</style>

<script>
    // DỮ LIỆU TỪ PHP
    const bookedSchedules = <?php echo json_encode($bookedSchedules); ?>;
    const maxBookingsPerSlot = 5;

    // CLASS QUẢN LÝ API ĐỊA CHỈ (giữ nguyên)
    class AddressAPI {
        constructor() {
            this.baseURL = 'https://provinces.open-api.vn/api/';
            this.apiLoaded = false;
            this.hcmCode = null;
            this.init();
        }

        init() {
            this.loadHCMData();
            this.setupEventListeners();
        }

        async fetchData(url) {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                this.apiLoaded = true;
                return await response.json();
            } catch (error) {
                console.error('Fetch error:', error);
                this.apiLoaded = false;
                this.showManualAddress();
                return [];
            }
        }

        async loadHCMData() {
            const provinceSelect = document.getElementById('province');
            provinceSelect.disabled = true;
            provinceSelect.innerHTML = '<option value="">Đang tải...</option>';

            const provinces = await this.fetchData(this.baseURL + '?depth=1');
            
            if (this.apiLoaded && provinces.length > 0) {
                const hcm = provinces.find(p => p.name === 'Thành phố Hồ Chí Minh' || p.name.includes('Hồ Chí Minh'));
                
                if (hcm) {
                    this.hcmCode = hcm.code;
                    provinceSelect.innerHTML = '<option value="">Chọn thành phố</option>';
                    const option = document.createElement('option');
                    option.value = hcm.code;
                    option.textContent = 'Thành phố Hồ Chí Minh';
                    provinceSelect.appendChild(option);
                    
                    provinceSelect.value = hcm.code;
                    this.loadDistricts(hcm.code);
                } else {
                    this.showManualAddress();
                }
                provinceSelect.disabled = false;
            } else {
                this.showManualAddress();
            }
        }

        async loadDistricts(provinceCode) {
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            
            districtSelect.innerHTML = '<option value="">Đang tải...</option>';
            districtSelect.disabled = true;
            
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;

            const province = await this.fetchData(this.baseURL + `p/${provinceCode}?depth=2`);
            
            if (province.districts) {
                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                province.districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.code;
                    option.textContent = district.name;
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
            }
        }

        async loadWards(districtCode) {
            const wardSelect = document.getElementById('ward');
            wardSelect.innerHTML = '<option value="">Đang tải...</option>';
            wardSelect.disabled = true;

            const district = await this.fetchData(this.baseURL + `d/${districtCode}?depth=2`);
            
            if (district.wards) {
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                district.wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.code;
                    option.textContent = ward.name;
                    wardSelect.appendChild(option);
                });
                wardSelect.disabled = false;
            }
        }

        setupEventListeners() {
            document.getElementById('district').addEventListener('change', (e) => {
                const districtCode = e.target.value;
                if (districtCode) {
                    this.loadWards(districtCode);
                } else {
                    this.resetWards();
                }
                this.updateAddress();
            });

            document.getElementById('ward').addEventListener('change', () => {
                this.updateAddress();
            });

            document.getElementById('street_address').addEventListener('input', () => {
                this.updateAddress();
            });
        }

        resetWards() {
            const wardSelect = document.getElementById('ward');
            wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            wardSelect.disabled = true;
        }

        showManualAddress() {
            const addressContainer = document.querySelector('.address-select-container');
            const manualContainer = document.querySelector('.manual-address-container');
            
            addressContainer.style.display = 'none';
            manualContainer.style.display = 'block';
        }

        updateAddress() {
            const province = document.getElementById('province');
            const district = document.getElementById('district');
            const ward = document.getElementById('ward');
            const street = document.getElementById('street_address');
            const addressDisplay = document.getElementById('full_address_display');
            const addressInput = document.getElementById('customer_address');

            let addressParts = [];

            if (street.value) addressParts.push(street.value);
            if (ward.selectedIndex > 0) addressParts.push(ward.options[ward.selectedIndex].textContent);
            if (district.selectedIndex > 0) addressParts.push(district.options[district.selectedIndex].textContent);
            if (province.selectedIndex > 0) addressParts.push(province.options[province.selectedIndex].textContent);

            const fullAddress = addressParts.join(', ');
            
            if (fullAddress) {
                addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt me-2 text-primary"></i><span class="text-dark">${fullAddress}</span>`;
                addressInput.value = fullAddress;
            } else {
                addressDisplay.innerHTML = `<i class="fas fa-map-marker-alt me-2 text-primary"></i><span class="text-muted">Chưa có địa chỉ</span>`;
                addressInput.value = '';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo API địa chỉ
        new AddressAPI();

        let deviceCount = 1;
        const maxDevices = 5;
        let currentServiceType = 'immediate'; // Mặc định là đặt dịch vụ ngay

        // Xử lý chọn loại dịch vụ
        document.querySelectorAll('.service-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.service-option').forEach(opt => {
                    opt.classList.remove('active');
                });
                
                this.classList.add('active');
                
                currentServiceType = this.getAttribute('data-service-type');
                document.getElementById('service_type').value = currentServiceType;
                
                updateServiceUI();
            });
        });

        function updateServiceUI() {
            const scheduleSection = document.querySelector('.schedule-section');
            const immediateTimeSection = document.querySelector('.immediate-time-section');
            const submitBtn = document.getElementById('submit_btn');

            if (currentServiceType === 'immediate') {
                // Ẩn phần chọn lịch hẹn, hiển thị thông báo thời gian mặc định
                scheduleSection.style.display = 'none';
                immediateTimeSection.style.display = 'block';
                
                // Cập nhật nút submit
                submitBtn.innerHTML = '<i class="fas fa-bolt me-2"></i>Đặt dịch vụ ngay';
                submitBtn.className = 'btn btn-danger btn-lg px-5 py-3';
            } else {
                // Hiện phần chọn lịch hẹn, ẩn thông báo thời gian mặc định
                scheduleSection.style.display = 'block';
                immediateTimeSection.style.display = 'none';
                
                // Cập nhật nút submit
                submitBtn.innerHTML = '<i class="fas fa-calendar-check me-2"></i>Đặt lịch hẹn';
                submitBtn.className = 'btn btn-primary btn-lg px-5 py-3';
            }
        }

        // Set min date cho input date
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('booking_date').min = today;

        // Format số điện thoại
        document.getElementById('customer_phone').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^0-9+]/g, '').slice(0, 12);
        });

        // Validate ngày đặt lịch
        document.getElementById('booking_date').addEventListener('change', function (e) {
            const selectedDate = new Date(this.value);
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            tomorrow.setHours(0, 0, 0, 0);

            if (selectedDate < tomorrow) {
                alert('Vui lòng chọn ngày từ ngày mai trở đi!');
                this.value = '';
                this.focus();
                return;
            }

            checkScheduleAvailability();
        });

        // Kiểm tra availability khi chọn khung giờ
        document.getElementById('booking_time').addEventListener('change', function (e) {
            checkScheduleAvailability();
        });

        function checkScheduleAvailability() {
            const bookingDate = document.getElementById('booking_date').value;
            const bookingTime = document.getElementById('booking_time').value;
            const availabilityDiv = document.getElementById('schedule_availability');
            const availabilityMessage = document.getElementById('availability_message');
            const submitBtn = document.getElementById('submit_btn');

            if (!bookingDate || !bookingTime) {
                availabilityDiv.style.display = 'none';
                return;
            }

            const scheduleKey = bookingDate + '_' + bookingTime;
            const currentBookings = bookedSchedules[scheduleKey] || 0;
            const isFull = currentBookings >= 1;

            availabilityDiv.style.display = 'block';

            if (!isFull) {
                availabilityDiv.className = 'alert alert-success';
                availabilityMessage.innerHTML = "Khung giờ hợp lý!";
                submitBtn.disabled = false;
            } else {
                availabilityDiv.className = 'alert alert-danger';
                availabilityMessage.innerHTML = `<i class="fas fa-ban me-2"></i>Tất cả Kỹ thuật viên đều bận! Vui lòng chọn khung giờ khác.`;
                submitBtn.disabled = true;
            }
        }

        // Xử lý thêm thiết bị (giữ nguyên)
        document.getElementById('btn-add-device').addEventListener('click', function () {
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
            newDevice.className = 'card mb-3 device-item new-device';
            newDevice.setAttribute('data-device-index', index);

            const deviceOptions = `<?php
            $options = '';
            foreach ($devices as $device) {
                $options .= '<option value=\"' . $device['maThietBi'] . '\">' . htmlspecialchars($device['tenThietBi']) . '</option>';
            }
            echo $options;
            ?>`;

            newDevice.innerHTML = `
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">Thiết bị ${index}</h5>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-device">
                        <i class="fas fa-times me-1"></i>Xóa
                    </button>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="device_type_${index}" class="form-label fw-semibold">Loại thiết bị <span class="text-danger">*</span></label>
                            <select class="form-select device-type-select" id="device_type_${index}" name="device_types[]" required>
                                <option value="">Chọn loại thiết bị</option>
                                ${deviceOptions}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="device_model_${index}" class="form-label fw-semibold">Thông tin thiết bị</label>
                            <input type="text" class="form-control" id="device_model_${index}" name="device_models[]" 
                                   placeholder="VD: Dell Inspiron 15, iPhone 13...">
                        </div>
                        <div class="col-12">
                            <label for="device_problem_${index}" class="form-label fw-semibold">Mô tả tình trạng hư hỏng <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="device_problem_${index}" name="device_problems[]" required rows="3"
                                      placeholder="Mô tả chi tiết tình trạng hư hỏng, lỗi gặp phải..."></textarea>
                        </div>
                    </div>
                </div>
            `;

            newDevice.querySelector('.btn-remove-device').addEventListener('click', function () {
                removeDevice(index, newDevice);
            });

            additionalDevices.appendChild(newDevice);
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
                device.querySelector('h5').textContent = `Thiết bị ${deviceIndex}`;

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
                addButton.innerHTML = '<i class="fas fa-ban me-2"></i>Đã đạt tối đa';
                addButton.classList.remove('btn-success');
                addButton.classList.add('btn-secondary');
            } else {
                addButton.disabled = false;
                addButton.innerHTML = '<i class="fas fa-plus me-2"></i>Thêm thiết bị khác';
                addButton.classList.remove('btn-secondary');
                addButton.classList.add('btn-success');
            }
        }

        // Xử lý form submit
        document.getElementById('serviceBookingForm').addEventListener('submit', function (e) {
            const customerAddress = document.getElementById('customer_address').value;

            // Kiểm tra địa chỉ
            if (!customerAddress) {
                e.preventDefault();
                alert('Vui lòng nhập đầy đủ địa chỉ!');
                return;
            }

            // Kiểm tra các trường bắt buộc cho thiết bị
            const deviceTypes = this.querySelectorAll('select[name="device_types[]"]');
            let isValid = true;

            deviceTypes.forEach((select, index) => {
                if (!select.value) {
                    isValid = false;
                    select.classList.add('is-invalid');
                    if (isValid) {
                        select.focus();
                    }
                } else {
                    select.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng chọn loại thiết bị cho tất cả các thiết bị!');
                return;
            }

            // Nếu chọn đặt lịch hẹn thì phải có ngày và giờ
            if (currentServiceType === 'schedule') {
                const bookingDate = document.getElementById('booking_date').value;
                const bookingTime = document.getElementById('booking_time').value;
                
                if (!bookingDate || !bookingTime) {
                    e.preventDefault();
                    alert('Vui lòng chọn ngày và khung giờ đặt lịch!');
                    return;
                }

                // Validate thêm cho ngày
                const selectedDate = new Date(bookingDate);
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(0, 0, 0, 0);

                if (selectedDate < tomorrow) {
                    e.preventDefault();
                    alert('Vui lòng chọn ngày từ ngày mai trở đi!');
                    document.getElementById('booking_date').focus();
                    return;
                }
            }

            // Hiển thị thông báo xác nhận
            e.preventDefault();
            let confirmMessage = '';
            
            if (currentServiceType === 'immediate') {
                confirmMessage = `Xác nhận đặt dịch vụ ngay:\n\n` +
                    `⚡ Dịch vụ ưu tiên cao nhất\n` +
                    `📞 Liên hệ trong 15-30 phút\n` +
                    `📅 Hôm nay - Khung giờ chiều\n\n` +
                    `Địa chỉ: ${customerAddress}\n\n` +
                    `Nhân viên sẽ gọi điện xác nhận ngay!`;
            } else {
                const bookingDate = document.getElementById('booking_date').value;
                const bookingTimeText = document.getElementById('booking_time').options[document.getElementById('booking_time').selectedIndex].text;
                
                confirmMessage = `Xác nhận đặt lịch hẹn:\n\n` +
                    `📅 Ngày: ${bookingDate}\n` +
                    `⏰ Giờ: ${bookingTimeText}\n` +
                    `📍 Địa chỉ: ${customerAddress}\n\n` +
                    `Kỹ thuật viên sẽ đến đúng lịch hẹn!`;
            }

            if (confirm(confirmMessage)) {
                this.submit();
            }
        });

        // Khởi tạo trạng thái ban đầu
        updateAddButtonState();
        document.querySelector('.btn-remove-device').style.display = 'block';
    });
</script>