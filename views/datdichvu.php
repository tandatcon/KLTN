<?php
session_start();
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Đặt dịch vụ - TechCare";
include VIEWS_PATH . '/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vui lòng đăng nhập để đặt lịch!";
    header('Location: ' . url('login'));
    exit();
}

require_once __DIR__ . '/../controllers/BookingController.php';
$bookingController = new BookingController($db);
$data = $bookingController->showBookingPage();
$devices = $data['devices'];
$bookedSchedules = $bookingController->getBookedSchedules();
?>

<section class="py-4">
    <div class="container">
        <!-- Header -->
        <div class="card border-0 s mb-4">
            <div class="card-body p-4 p-md-5 text-center">
                <div class="">
                    <h1 class="display-5 fw-bold mb-3 text-primary">
                        <i class="fas fa-tools me-3"></i>ĐẶT DỊCH VỤ SỬA CHỮA THIẾT BỊ
                    </h1>
                    <p class="lead text-muted mb-0">Dịch vụ chuyên nghiệp - Hỗ trợ tận tâm</p>
                </div>
            </div>
        </div>
        <div class="banner-image-container text-center">
            <img src="<?php echo asset('images/bannerddv.jpg'); ?>" alt="TechCare Banner"
                class="banner-image img-fluid">
        </div>

        <!-- FORM CHÍNH - BAO BỌC TOÀN BỘ NỘI DUNG -->
        <form id="serviceBookingForm" action="<?php echo url('process_booking'); ?>" method="POST">
            <!-- Các trường ẩn cần thiết -->
            <input type="hidden" id="service_type" name="service_type" value="today">
            <input type="hidden" id="immediate_date" name="immediate_date" value="<?php echo date('Y-m-d'); ?>">
            <input type="hidden" id="immediate_time" name="immediate_time" value="chieu">
            <input type="hidden" id="booking_date" name="booking_date" value="">

            <div class="row">
                <!-- Cột trái: Thông tin khách hàng & Thiết bị -->
                <div class="col-lg-6 mb-4">
                    <!-- Thông tin khách hàng -->
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
                                        value="<?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>"
                                        required placeholder="Nhập họ và tên">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Số điện thoại *</label>
                                    <input type="tel" class="form-control input-gray" id="customer_phone"
                                        name="customer_phone"
                                        value="<?php echo isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : ''; ?>"
                                        required placeholder="Nhập số điện thoại">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Địa chỉ *</label>
                                    <div class="address-select-container mb-3">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <select class="form-select input-gray" id="province" name="province"required >
                                                    <option value="">Thành phố</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-select input-gray" id="district" name="district"
                                                    disabled required>
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
                                                    name="street_address" placeholder="Số nhà, tên đường" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="p-2 border border-gray rounded bg-light">
                                            <div id="full_address_display" class="small">
                                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                                <span>Chưa có địa chỉ</span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="customer_address" name="customer_address" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mô tả sự cố máy -->
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
                                                name="device_types[]" required>
                                                <option value="">Chọn loại thiết bị</option>
                                                <?php foreach ($devices as $device): ?>
                                                    <option value="<?php echo $device['maThietBi']; ?>">
                                                        <?php echo htmlspecialchars($device['tenThietBi']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Thông tin phiên bản/ thương hiệu</label>
                                            <input type="text" class="form-control input-gray" name="device_models[]"
                                                placeholder="VD: Panasonic Inverter 1 HP CU/CS-PU9AKH-8 ...">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label">Mô tả tình trạng *</label>
                                            <textarea class="form-control input-gray" name="device_problems[]" required
                                                rows="3" placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
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

                <!-- Cột phải: Thời gian & Dịch vụ -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-gray">
                        <div class="card-body">
                            <!-- Loại đặt lịch -->
                            <div class="mb-4">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-clock me-2"></i>Thời gian đặt lịch
                                </h5>

                                <!-- Radio Đặt lịch hôm nay -->
                                <!-- 👉 -->
                                <div class="form-check mb-3 p-3  rounded bg-light">
                                    <input class="form-check-input" type="radio" id="schedule_today"
                                        name="schedule_type" value="today" checked>
                                    <label class="form-check-label fw-semibold text-success" for="schedule_today">
                                        <i class="fas fa-calendar-plus me-2"></i>Đặt lịch hôm nay
                                    </label>
                                    <div class="form-text ms-4">
                                        <i class="fas fa-info-circle me-1"></i>Kỹ thuật viên sẽ liên hệ và đến sớm nhất
                                        có thể
                                    </div>
                                </div>

                                <!-- Radio Đặt lịch hẹn -->
                                <!-- 👉 -->
                                <div class="form-check p-3  rounded bg-light">
                                <input class="form-check-input" type="radio" id="schedule_appointment"
                                        name="schedule_type" value="appointment">
                                    <label class="form-check-label fw-semibold text-primary" for="schedule_appointment">
                                        <i class="fas fa-calendar-plus me-2"></i>Đặt lịch hẹn
                                    </label>
                                    <div class="form-text ms-4">
                                        <i class="fas fa-info-circle me-1"></i>Chọn ngày và giờ cụ thể cho lịch hẹn
                                    </div>
                                </div>
                            </div>

                            <!-- Phần chọn ngày giờ (chỉ hiện khi chọn đặt lịch hẹn) -->
                            <div class="appointment-fields" style="display: none;">
                                <!-- Chọn ngày -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-calendar me-2"></i>Chọn ngày
                                    </h6>
                                    <div class="date-selection">
                                        <div class="row g-2" id="date-grid">
                                            <!-- Dates will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Chọn khung giờ -->
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-clock me-2"></i>Chọn khung giờ
                                    </h6>
                                    <div class="time-selection">
                                        <div class="row g-2">
                                            <!-- Sáng -->
                                            <div class="col-md-4">
                                                <div class="time-slot-group text-center">
                                                    <input type="radio" class="btn-check" name="booking_time"
                                                        id="time_sang" value="sang" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 py-2" for="time_sang">
                                                        <div class="fw-bold">SÁNG</div>
                                                        <small class="text-muted">8:00 - 11:00</small>
                                                    </label>
                                                </div>
                                            </div>



                                            <!-- Chiều -->
                                            <div class="col-md-4">
                                                <div class="time-slot-group text-center">
                                                    <input type="radio" class="btn-check" name="booking_time"
                                                        id="time_chieu" value="chieu" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 py-2" for="time_chieu">
                                                        <div class="fw-bold">CHIỀU</div>
                                                        <small class="text-muted">13:00 - 17:00</small>
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Tối -->
                                            <div class="col-md-4">
                                                <div class="time-slot-group text-center">
                                                    <input type="radio" class="btn-check" name="booking_time"
                                                        id="time_trua" value="toi" autocomplete="off">
                                                    <label class="btn btn-outline-primary w-100 py-2" for="time_trua">
                                                        <div class="fw-bold">TỐI</div>
                                                        <small class="text-muted">17:00-21:00</small>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ghi chú -->
                            <div class="mb-4">
                                <h5 class="card-title text-primary mb-3">
                                    <i class="fas fa-comments me-2"></i>Ghi chú thêm
                                </h5>
                                <textarea class="form-control input-gray" id="problem_description"
                                    name="problem_description" rows="3"
                                    placeholder="Ghi chú của bạn giành cho chúng tôi..."></textarea>
                            </div>

                            <!-- Nút đặt lịch -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                                    <i class="fas fa-bolt me-2"></i>ĐẶT LỊCH NGAY
                                </button>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>Được bảo hành dịch vụ 30 ngày
                                    </small>
                                </div>
                                <div class="banner-image-container text-center">
            <img src="<?php echo asset('images/waitting.jpg'); ?>" alt="TechCare Banner"
                style="width:50%;">
        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include VIEWS_PATH . '/footer.php'; ?>

<style>
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
    }

    .date-btn {
        width: 100%;
        padding: 8px 4px;
        font-size: 0.85rem;
    }

    .time-slot-group .btn {
        transition: all 0.3s ease;
    }

    @media (max-width: 991.98px) {
        .time-slot-group {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 768px) {
        .date-btn {
            font-size: 0.8rem;
            padding: 6px 2px;
        }

        .time-slot-group .btn {
            padding: 10px 5px;
        }
    }
</style>

<script>
    // DỮ LIỆU TỪ PHP
    const bookedSchedules = <?php echo json_encode($bookedSchedules ?? []); ?>;

    // CLASS QUẢN LÝ LỊCH
    class ScheduleManager {
        constructor() {
            this.selectedDate = null;
            this.selectedTime = null;
            this.init();
        }

        init() {
            this.generateDateGrid();
            this.setupEventListeners();
            this.initScheduleType();
        }

        initScheduleType() {
            const scheduleToday = document.getElementById('schedule_today');
            const scheduleAppointment = document.getElementById('schedule_appointment');
            const appointmentFields = document.querySelector('.appointment-fields');

            scheduleToday.addEventListener('change', () => {
                if (scheduleToday.checked) {
                    appointmentFields.style.display = 'none';
                    document.getElementById('service_type').value = 'today';
                }
            });

            scheduleAppointment.addEventListener('change', () => {
                if (scheduleAppointment.checked) {
                    appointmentFields.style.display = 'block';
                    document.getElementById('service_type').value = 'appointment';
                }
            });
        }

        // Tác dụng: Tạo lưới ngày cho 8 ngày từ ngày mai trở đi
        generateDateGrid() {
            const dateGrid = document.getElementById('date-grid');
            const today = new Date();

            if (!dateGrid) return;

            dateGrid.innerHTML = '';

            // Bắt đầu từ ngày mai (i=1) và hiển thị 8 ngày
            for (let i = 1; i <= 8; i++) {
                const date = new Date();
                date.setDate(today.getDate() + i);

                const dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                const dayName = dayNames[date.getDay()];
                const dayNumber = date.getDate();
                const month = date.getMonth() + 1;
                const dateString = date.toISOString().split('T')[0];

                const dateElement = document.createElement('div');
                dateElement.className = 'col-4 col-sm-3 col-md-3'; // Điều chỉnh layout cho 8 ngày
                dateElement.innerHTML = `
                <input type="radio" class="btn-check" name="booking_date" id="date_${i}" value="${dateString}" autocomplete="off">
                <label class="btn btn-outline-secondary date-btn w-100" for="date_${i}">
                    <div class="fw-bold">${dayName}</div>
                    <div class="small">${dayNumber}/${month}</div>
                </label>
            `;

                dateGrid.appendChild(dateElement);
            }
        }

        setupEventListeners() {
            document.addEventListener('change', (e) => {
                if (e.target.name === 'booking_date') {
                    this.selectedDate = e.target.value;
                    document.getElementById('booking_date').value = this.selectedDate;
                    this.checkAvailability();
                }

                if (e.target.name === 'booking_time') {
                    this.selectedTime = e.target.value;
                    this.checkAvailability();
                }
            });
        }

        checkAvailability() {
            if (this.selectedDate && this.selectedTime) {
                const scheduleKey = this.selectedDate + '_' + this.selectedTime;
                const currentBookings = bookedSchedules[scheduleKey] || 0;

                if (currentBookings >= 1) {
                    showAlert('Khung giờ này đã được đặt! Vui lòng chọn khung giờ khác.', 'Thông báo');
                    document.querySelector(`input[name="booking_time"][value="${this.selectedTime}"]`).checked = false;
                    this.selectedTime = null;
                }
            }
        }
    }

    // CLASS QUẢN LÝ API ĐỊA CHỈ
    class AddressAPI {
        constructor() {
            this.baseURL = 'https://provinces.open-api.vn/api/';
            this.init();
        }

        async fetchData(url) {
            try {
                const response = await fetch(url);
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Lỗi khi fetch dữ liệu địa chỉ:', error);
                this.showManualAddress();
                return [];
            }
        }

        async loadHCMData() {
            const provinceSelect = document.getElementById('province');
            if (!provinceSelect) return;

            try {
                const provinces = await this.fetchData(this.baseURL + '?depth=1');
                const hcm = provinces.find(p => p.name === 'Thành phố Hồ Chí Minh');

                if (hcm) {
                    const option = document.createElement('option');
                    option.value = hcm.code;
                    option.textContent = 'TP Hồ Chí Minh';
                    provinceSelect.appendChild(option);
                    provinceSelect.value = hcm.code;
                    await this.loadDistricts(hcm.code);
                } else {
                    this.showManualAddress();
                }
            } catch (error) {
                this.showManualAddress();
            }
        }

        async loadDistricts(provinceCode) {
            const districtSelect = document.getElementById('district');
            if (!districtSelect) return;

            try {
                const province = await this.fetchData(this.baseURL + `p/${provinceCode}?depth=2`);

                if (province && province.districts) {
                    districtSelect.innerHTML = '<option value="">Quận/Huyện</option>';
                    province.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                    districtSelect.disabled = false;
                }
            } catch (error) {
                console.error('Lỗi khi load districts:', error);
            }
        }

        async loadWards(districtCode) {
            const wardSelect = document.getElementById('ward');
            if (!wardSelect) return;

            try {
                const district = await this.fetchData(this.baseURL + `d/${districtCode}?depth=2`);

                if (district && district.wards) {
                    wardSelect.innerHTML = '<option value="">Phường/Xã</option>';
                    district.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                    wardSelect.disabled = false;
                }
            } catch (error) {
                console.error('Lỗi khi load wards:', error);
            }
        }

        showManualAddress() {
            const addressContainer = document.querySelector('.address-select-container');
            if (addressContainer) {
                addressContainer.style.display = 'none';
            }
        }

        updateAddress() {
            const province = document.getElementById('province');
            const district = document.getElementById('district');
            const ward = document.getElementById('ward');
            const street = document.getElementById('street_address');
            const addressDisplay = document.getElementById('full_address_display');
            const addressInput = document.getElementById('customer_address');

            if (!province || !district || !ward || !street || !addressDisplay || !addressInput) return;

            let addressParts = [];
            if (street.value) addressParts.push(street.value);
            if (ward.selectedIndex > 0) addressParts.push(ward.options[ward.selectedIndex].textContent);
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

        init() {
            this.loadHCMData();

            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            const streetInput = document.getElementById('street_address');

            if (districtSelect) {
                districtSelect.addEventListener('change', (e) => {
                    if (e.target.value) {
                        this.loadWards(e.target.value);
                    }
                    this.updateAddress();
                });
            }

            if (wardSelect) {
                wardSelect.addEventListener('change', () => this.updateAddress());
            }

            if (streetInput) {
                streetInput.addEventListener('input', () => this.updateAddress());
            }
        }
    }

    // QUẢN LÝ FORM ĐẶT LỊCH
    class BookingForm {
        constructor() {
            this.deviceCount = 1;
            this.maxDevices = 8;
            this.init();
        }

        init() {
            new AddressAPI();
            new ScheduleManager();
            this.initDeviceManagement();
            this.initFormValidation();
        }

        initDeviceManagement() {
            const addButton = document.getElementById('btn-add-device');
            if (!addButton) return;

            addButton.addEventListener('click', () => {
                if (this.deviceCount >= this.maxDevices) {
                    showAlert(`Chỉ được thêm tối đa ${this.maxDevices} thiết bị`);
                    return;
                }
                this.deviceCount++;
                this.addDevice(this.deviceCount);
            });
        }

        addDevice(index) {
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
                        <select class="form-select input-gray" name="device_types[]" required>
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
                        <textarea class="form-control input-gray" name="device_problems[]" required rows="2"
                                placeholder="Mô tả chi tiết tình trạng hư hỏng..."></textarea>
                    </div>
                </div>
            `;

            newDevice.querySelector('.btn-remove-device').addEventListener('click', () => {
                newDevice.remove();
                this.deviceCount--;
                this.updateDeviceNumbers();
            });

            additionalDevices.appendChild(newDevice);
        }

        updateDeviceNumbers() {
            const deviceItems = document.querySelectorAll('.device-item');
            deviceItems.forEach((item, index) => {
                const title = item.querySelector('h6');
                if (title) {
                    title.textContent = `Thiết bị ${index + 1}`;
                }
            });
        }

        initFormValidation() {
            const form = document.getElementById('serviceBookingForm');
            if (!form) return;

            const phoneInput = document.getElementById('customer_phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function (e) {
                    this.value = this.value.replace(/[^0-9+]/g, '').slice(0, 12);
                });
            }

            form.addEventListener('submit', (e) => {
                e.preventDefault();

                if (this.validateForm()) {
                    this.showBookingConfirmation();
                }
            });
        }

        validateForm() {
            const customerName = document.getElementById('customer_name')?.value.trim();
            const customerPhone = document.getElementById('customer_phone')?.value.trim();
            const customerAddress = document.getElementById('customer_address')?.value.trim();
            const scheduleAppointment = document.getElementById('schedule_appointment')?.checked;

            if (!customerName || !customerPhone || !customerAddress) {
                showAlert('Vui lòng điền đầy đủ thông tin khách hàng!');
                return false;
            }

            const phoneRegex = /(0[3|5|7|8|9])+([0-9]{8})\b/;
            if (!phoneRegex.test(customerPhone)) {
                showAlert('Vui lòng nhập số điện thoại hợp lệ!');
                return false;
            }

            if (scheduleAppointment) {
                const bookingDate = document.querySelector('input[name="booking_date"]:checked');
                const bookingTime = document.querySelector('input[name="booking_time"]:checked');

                if (!bookingDate || !bookingTime) {
                    showAlert('Vui lòng chọn ngày và khung giờ đặt lịch!');
                    return false;
                }
            }

            const deviceTypes = document.querySelectorAll('select[name="device_types[]"]');
            let hasDevice = false;
            deviceTypes.forEach(select => {
                if (select.value) hasDevice = true;
            });

            if (!hasDevice) {
                showAlert('Vui lòng chọn ít nhất một thiết bị!');
                return false;
            }

            return true;
        }

        showBookingConfirmation() {
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
    }

    // Khởi tạo form khi trang load xong
    document.addEventListener('DOMContentLoaded', function () {
        new BookingForm();
    });
</script>