<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Đăng ký dịch vụ - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../function/khachhang.php';
require_once __DIR__ . '/../../function/dichvu.php';

$khachhang = new KhachHang($db);
$dichVuService = new DichVuService($db);

// Lấy danh sách thiết bị
$devices = $dichVuService->layDanhSachThietBi();

// Kiểm tra role - chỉ cho phép nhân viên (role 2,3,4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    header('Location: ' . url('home'));
    exit();
}



// Xử lý tìm kiếm khách hàng
$customerInfo = null;
$searchPerformed = false;
$searchPhone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search_customer'])) {
    unset($_SESSION['customer_info']);
    $searchPhone = trim($_POST['sdt'] ?? '');
    $searchPerformed = true;
    
    if (!empty($searchPhone)) {
        $customerInfo = $khachhang->layKHBySDT($searchPhone);
        
        if ($customerInfo) {
            $_SESSION['customer_info'] = $customerInfo;
            $_SESSION['search_phone'] = $searchPhone;
        }
    }
}

// Xử lý khi có customer_info trong session
if (isset($_SESSION['customer_info'])) {
    $customerInfo = $_SESSION['customer_info'];
    $searchPhone = $_SESSION['search_phone'] ?? '';
}

// Hàm helper để tránh lỗi deprecated
function safe_htmlspecialchars($value) {
    return $value !== null ? htmlspecialchars($value) : '';
}

date_default_timezone_set('Asia/Ho_Chi_Minh');
$currentDateTime = date('Y-m-d H:i:s');
$currentDate = date('d/m/Y');
$currentTime = date('H:i');
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-primary mb-3">
                        <i class="fas fa-user-plus me-2"></i>ĐĂNG KÝ DỊCH VỤ CHO KHÁCH HÀNG
                    </h1>
                    <p class="lead text-muted">Tìm kiếm khách hàng và đăng ký dịch vụ sửa chữa tại cửa hàng</p>
                </div>

                <!-- Thông báo -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $_SESSION['error']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- BƯỚC 1: TÌM KIẾM KHÁCH HÀNG -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-search me-2"></i>Tìm kiếm khách hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label for="sdt" class="form-label">Số điện thoại khách hàng *</label>
                                <input type="tel" class="form-control" id="sdt" name="sdt" 
                                       value="<?php echo safe_htmlspecialchars($searchPhone); ?>" 
                                       required placeholder="Nhập số điện thoại (10-11 số)"
                                       pattern="[0-9]{10,11}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" name="search_customer" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Tìm kiếm
                                </button>
                            </div>
                        </form>

                        <!-- HIỂN THỊ KẾT QUẢ TÌM KIẾM -->
                        <?php if ($searchPerformed): ?>
                            <?php if ($customerInfo): ?>
                                <!-- KHÁCH HÀNG CŨ -->
                                <div class="alert alert-success mt-3">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle fa-2x me-3 mt-1"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">Đã tìm thấy khách hàng</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>Họ tên:</strong> <?php echo safe_htmlspecialchars($customerInfo['hoTen']); ?>
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>SĐT:</strong> <?php echo safe_htmlspecialchars($customerInfo['sdt']); ?>
                                                </div>
                                                <?php if (!empty($customerInfo['email'])): ?>
                                                <div class="col-md-6">
                                                    <strong>Email:</strong> <?php echo safe_htmlspecialchars($customerInfo['email']); ?>
                                                </div>
                                                <?php endif; ?>
                                                <div class="col-md-6">
                                                    <strong>Địa chỉ:</strong> <?php echo !empty($customerInfo['diaChi']) ? safe_htmlspecialchars($customerInfo['diaChi']) : 'Chưa có địa chỉ'; ?>
                                                </div>
                                            </div>
                                            <hr class="my-2">
                                            <small class="text-success">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Thông tin khách hàng đã được tự động điền vào form bên dưới. Bạn có thể chỉnh sửa nếu cần.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!-- KHÁCH HÀNG MỚI -->
                                <div class="alert alert-warning mt-3">
                                    <div class="d-flex">
                                        <i class="fas fa-user-plus fa-2x me-3 mt-1"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">Khách hàng mới</h6>
                                            <p class="mb-2">Số điện thoại <strong><?php echo safe_htmlspecialchars($searchPhone); ?></strong> chưa có trong hệ thống.</p>
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo url('employee/them-khach-hang?sdt=' . urlencode($searchPhone)); ?>" class="btn btn-success btn-sm">
                                                    <i class="fas fa-user-plus me-1"></i> Thêm khách hàng mới
                                                </a>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="enableManualInput()">
                                                    <i class="fas fa-edit me-1"></i> Nhập thủ công
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- BƯỚC 2: FORM ĐĂNG KÝ DỊCH VỤ -->
                <div class="booking-content" id="bookingFormSection" style="<?php echo (!$customerInfo && $searchPerformed) ? 'display: none;' : ''; ?>">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-list me-2"></i>Thông tin đăng ký dịch vụ
                                <?php if ($customerInfo): ?>
                                    <span class="badge bg-light text-success ms-2">ĐÃ TÌM THẤY</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark ms-2">NHẬP THỦ CÔNG</span>
                                <?php endif; ?>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="serviceBookingForm" action="<?php echo url('employee/quy-trinh-them-dich-vu'); ?>" method="POST">
                                <!-- THÔNG TIN ẨN -->
                                <input type="hidden" name="customer_id" value="<?php echo $customerInfo ? $customerInfo['maND'] : ''; ?>">
                                <input type="hidden" name="sdt" value="<?php echo safe_htmlspecialchars($searchPhone); ?>">
                                <input type="hidden" name="booking_datetime" value="<?php echo $currentDateTime; ?>">

                                <!-- THÔNG TIN THỜI GIAN TIẾP NHẬN -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <h6 class="mb-1"><i class="fas fa-clock me-2"></i>Thời gian tiếp nhận</h6>
                                                    <p class="mb-0">Ngày: <strong><?php echo $currentDate; ?></strong> - Giờ: <strong><?php echo $currentTime; ?></strong></p>
                                                </div>
                                                <div class="col-md-4 text-md-end">
                                                    <span class="badge bg-primary">TẠI CỬA HÀNG</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- THÔNG TIN KHÁCH HÀNG -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3 border-bottom pb-2">
                                            <i class="fas fa-user me-2"></i>Thông tin khách hàng
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_name" class="form-label">Họ và tên khách hàng *</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                               value="<?php echo $customerInfo ? safe_htmlspecialchars($customerInfo['hoTen']) : ''; ?>" 
                                               required placeholder="Nhập họ và tên khách hàng">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_phone_input" class="form-label">Số điện thoại *</label>
                                        <input type="text" class="form-control" id="customer_phone_input" name="customer_phone" 
                                               value="<?php echo safe_htmlspecialchars($searchPhone); ?>" 
                                               required placeholder="Nhập số điện thoại"
                                               pattern="[0-9]{10,11}">
                                        <small class="form-text text-muted">Số điện thoại khách hàng</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_email" class="form-label">Email (tuỳ chọn)</label>
                                        <input type="email" class="form-control" id="customer_email" name="customer_email" 
                                               value="<?php echo $customerInfo ? safe_htmlspecialchars($customerInfo['email'] ?? '') : ''; ?>" 
                                               placeholder="Nhập email khách hàng">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="customer_address" class="form-label">Địa chỉ khách hàng *</label>
                                        <input type="text" class="form-control" id="customer_address" name="customer_address" 
                                               value="<?php echo $customerInfo ? safe_htmlspecialchars($customerInfo['diaChi'] ?? '') : ''; ?>" 
                                               required placeholder="Nhập địa chỉ khách hàng">
                                    </div>
                                </div>

                                <?php if (!$customerInfo && $searchPerformed): ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Bạn đang nhập thông tin khách hàng thủ công. Hệ thống sẽ tự động tạo tài khoản cho khách hàng mới.
                                </div>
                                <?php endif; ?>

                                <!-- THIẾT BỊ CẦN SỬA CHỮA -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3 border-bottom pb-2">
                                            <i class="fas fa-tools me-2"></i>Thiết bị cần sửa chữa
                                        </h6>
                                    </div>
                                    
                                    <div class="devices-container">
                                        <!-- Thiết bị 1 -->
                                        <div class="device-item card mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0 text-primary">Thiết bị 1</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Loại thiết bị *</label>
                                                        <select class="form-select device-type-select" name="device_types[]" required>
                                                            <option value="">Chọn loại thiết bị</option>
                                                            <?php foreach ($devices as $device): ?>
                                                                <option value="<?php echo $device['maThietBi']; ?>">
                                                                    <?php echo safe_htmlspecialchars($device['tenThietBi']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Thông tin thiết bị *</label>
                                                        <input type="text" class="form-control" name="device_models[]" required
                                                               placeholder="Model, hãng sản xuất...">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label">Mô tả tình trạng hư hỏng *</label>
                                                        <textarea class="form-control" name="device_problems[]" required rows="3"
                                                                  placeholder="Mô tả chi tiết tình trạng hư hỏng của thiết bị..."></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Các thiết bị khác sẽ được thêm vào đây -->
                                        <div id="additional-devices"></div>
                                        
                                        <!-- Nút thêm thiết bị -->
                                        <div class="text-center">
                                            <button type="button" id="btn-add-device" class="btn btn-outline-success">
                                                <i class="fas fa-plus me-1"></i>Thêm thiết bị khác
                                            </button>
                                            <p class="text-muted mt-2 small">
                                                <i class="fas fa-info-circle me-1"></i>Tối đa 5 thiết bị mỗi đơn
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- THÔNG TIN BỔ SUNG -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="text-primary mb-3 border-bottom pb-2">
                                            <i class="fas fa-comment-dots me-2"></i>Thông tin bổ sung
                                        </h6>
                                        <div class="mb-3">
                                            <label for="problem_description" class="form-label">Ghi chú thêm (tuỳ chọn)</label>
                                            <textarea class="form-control" id="problem_description" name="problem_description" rows="4"
                                                      placeholder="Các yêu cầu đặc biệt, ghi chú khác..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- NÚT ĐĂNG KÝ -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-save me-2"></i>ĐĂNG KÝ DỊCH VỤ
                                            </button>
                                        </div>
                                        <div class="text-center mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <?php if ($customerInfo): ?>
                                                    Thông tin khách hàng đã được xác nhận từ hệ thống. Bạn có thể chỉnh sửa nếu cần.
                                                <?php else: ?>
                                                    Nhập thông tin khách hàng để đăng ký dịch vụ.
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let deviceCount = 1;
    const maxDevices = 5;
    
    // Format số điện thoại
    const phoneInput = document.getElementById('sdt');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });
    }

    // Format số điện thoại trong form
    const customerPhoneInput = document.getElementById('customer_phone_input');
    if (customerPhoneInput) {
        customerPhoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        });
    }
    
    // Xử lý thêm thiết bị
    document.getElementById('btn-add-device')?.addEventListener('click', function() {
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
        if (!additionalDevices) return;
        
        const newDevice = document.createElement('div');
        newDevice.className = 'device-item card mb-3';
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
                <h6 class="mb-0 text-primary">Thiết bị ${index}</h6>
                <button type="button" class="btn btn-danger btn-sm btn-remove-device">
                    <i class="fas fa-times"></i> Xóa
                </button>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Loại thiết bị *</label>
                        <select class="form-select device-type-select" name="device_types[]" required>
                            <option value="">Chọn loại thiết bị</option>
                            ${deviceOptions}
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Thông tin thiết bị *</label>
                        <input type="text" class="form-control" name="device_models[]" required
                               placeholder="Model, hãng sản xuất...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả tình trạng hư hỏng *</label>
                        <textarea class="form-control" name="device_problems[]" required rows="3"
                                  placeholder="Mô tả chi tiết tình trạng hư hỏng của thiết bị..."></textarea>
                    </div>
                </div>
            </div>
        `;
        
        // Thêm sự kiện xóa
        newDevice.querySelector('.btn-remove-device').addEventListener('click', function() {
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
            const header = device.querySelector('.card-header h6');
            if (header) {
                header.textContent = `Thiết bị ${deviceIndex}`;
            }
        });
    }
    
    function updateAddButtonState() {
        const addButton = document.getElementById('btn-add-device');
        if (!addButton) return;
        
        if (deviceCount >= maxDevices) {
            addButton.disabled = true;
            addButton.innerHTML = '<i class="fas fa-ban me-1"></i>Đã đạt tối đa';
        } else {
            addButton.disabled = false;
            addButton.innerHTML = '<i class="fas fa-plus me-1"></i>Thêm thiết bị khác';
        }
    }
    
    // Xử lý form submit
    document.getElementById('serviceBookingForm')?.addEventListener('submit', function(e) {
        const customerName = document.getElementById('customer_name');
        const customerPhone = document.getElementById('customer_phone_input');
        const customerAddress = document.getElementById('customer_address');
        const deviceTypes = this.querySelectorAll('select[name="device_types[]"]');
        const deviceModels = this.querySelectorAll('input[name="device_models[]"]');
        const deviceProblems = this.querySelectorAll('textarea[name="device_problems[]"]');
        
        let isValid = true;
        let errorMessage = '';

        // Validate thông tin khách hàng
        if (!customerName.value.trim()) {
            isValid = false;
            customerName.style.borderColor = '#dc3545';
            errorMessage = 'Vui lòng nhập họ tên khách hàng!';
        } else {
            customerName.style.borderColor = '#ced4da';
        }

        if (!customerPhone.value.trim()) {
            isValid = false;
            customerPhone.style.borderColor = '#dc3545';
            errorMessage = 'Vui lòng nhập số điện thoại khách hàng!';
        } else {
            customerPhone.style.borderColor = '#ced4da';
        }

        if (!customerAddress.value.trim()) {
            isValid = false;
            customerAddress.style.borderColor = '#dc3545';
            errorMessage = 'Vui lòng nhập địa chỉ khách hàng!';
        } else {
            customerAddress.style.borderColor = '#ced4da';
        }
        
        // Validate thiết bị
        deviceTypes.forEach((select, index) => {
            if (!select.value) {
                isValid = false;
                select.style.borderColor = '#dc3545';
                errorMessage = 'Vui lòng chọn loại thiết bị cho tất cả các thiết bị!';
            } else {
                select.style.borderColor = '#ced4da';
            }
        });
        
        deviceModels.forEach((input, index) => {
            if (!input.value.trim()) {
                isValid = false;
                input.style.borderColor = '#dc3545';
                errorMessage = 'Vui lòng nhập thông tin thiết bị cho tất cả các thiết bị!';
            } else {
                input.style.borderColor = '#ced4da';
            }
        });
        
        deviceProblems.forEach((textarea, index) => {
            if (!textarea.value.trim()) {
                isValid = false;
                textarea.style.borderColor = '#dc3545';
                errorMessage = 'Vui lòng mô tả tình trạng hư hỏng cho tất cả các thiết bị!';
            } else {
                textarea.style.borderColor = '#ced4da';
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return;
        }
        
        if (!confirm('Bạn có chắc chắn muốn đăng ký dịch vụ cho khách hàng này?')) {
            e.preventDefault();
        }
    });
    
    // Khởi tạo trạng thái ban đầu
    updateAddButtonState();
});

// Hàm bật chế độ nhập thủ công
function enableManualInput() {
    const bookingSection = document.getElementById('bookingFormSection');
    if (bookingSection) {
        bookingSection.style.display = 'block';
    }
    
    // Thông báo
    alert('Bạn có thể nhập thông tin khách hàng thủ công. Hệ thống sẽ tự động xử lý thông tin khách hàng mới.');
}
</script>

<?php include __DIR__ . '/../footer.php'; ?>