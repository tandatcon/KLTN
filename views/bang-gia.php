<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Bảng Giá Sửa Chữa - TechCare";
include VIEWS_PATH . '/header.php';



require_once __DIR__ . '/../models/devices.php';

$thietBiModel = new thietbi($db);

// Lấy tất cả thiết bị
$devices = $thietBiModel->getAllDevices();

// Lấy tham số từ URL
$selectedDeviceId = isset($_GET['device_id']) ? intval($_GET['device_id']) : null;

// Lấy bảng giá theo thiết bị được chọn hoặc tất cả
if ($selectedDeviceId) {
    $priceList = $thietBiModel->getBangGiaByDevice($selectedDeviceId);
    $selectedDevice = $thietBiModel->getDeviceById($selectedDeviceId);
} else {
    $priceList = $thietBiModel->getAllBangGia();
    $selectedDevice = null;
}
?>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-5 fw-bold text-primary mb-3">Bảng Giá Sửa Chữa</h1>
                    <p class="lead text-muted">Tham khảo bảng giá dịch vụ sửa chữa các thiết bị</p>
                </div>

                <!-- Bộ lọc thiết bị -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form id="filterForm" method="GET" action="">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="device_id" class="form-label fw-semibold">Chọn thiết bị</label>
                                    <select class="form-select" id="device_id" name="device_id">
                                        <option value="">Tất cả thiết bị</option>
                                        <?php foreach ($devices as $device): ?>
                                            <option value="<?php echo $device['maThietBi']; ?>" 
                                                <?php echo $selectedDeviceId == $device['maThietBi'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($device['tenThietBi']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <?php if ($selectedDeviceId): ?>
                                <div class="mt-3">
                                    <a href="<?php echo url('bang-gia'); ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times me-1"></i>Xem tất cả
                                    </a>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <!-- Kết quả -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            <?php if ($selectedDevice): ?>
                                Bảng Giá: <?php echo htmlspecialchars($selectedDevice['tenThietBi']); ?>
                            <?php else: ?>
                                Bảng Giá Tất Cả Thiết Bị
                            <?php endif; ?>
                        </h4>
                        <span class="badge bg-light text-dark">
                            <?php echo count($priceList); ?> dịch vụ
                        </span>
                    </div>
                    
                    <div class="card-body p-0">
                        <?php if (!empty($priceList)): ?>
                            <?php 
                            // Nhóm bảng giá theo thiết bị nếu đang xem tất cả
                            if (!$selectedDeviceId) {
                                $priceByDevice = [];
                                foreach ($priceList as $price) {
                                    $deviceId = $price['maThietBi'];
                                    if (!isset($priceByDevice[$deviceId])) {
                                        $priceByDevice[$deviceId] = [
                                            'device' => $thietBiModel->getDeviceById($deviceId),
                                            'prices' => []
                                        ];
                                    }
                                    $priceByDevice[$deviceId]['prices'][] = $price;
                                }
                            }
                            ?>

                            <?php if ($selectedDeviceId): ?>
                                <!-- Hiển thị theo thiết bị được chọn -->
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th width="50%">Chi Tiết Lỗi</th>
                                                <th width="25%">Giá Tham Khảo</th>
                                                <th width="25%">Thời Gian Sửa Chữa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($priceList as $price): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold"><?php echo htmlspecialchars($price['chitietLoi']); ?></div>
                                                        <?php if (!empty($price['moTa'])): ?>
                                                            <small class="text-muted"><?php echo htmlspecialchars($price['moTa']); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success">
                                                            <?php echo !empty($price['khoangGia']) ? htmlspecialchars($price['khoangGia']) : 'Liên hệ'; ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="">
                                                            <?php 
                                                            if ($price['DVT']) {
                                                                echo $price['DVT'];
                                                            } else {
                                                                echo '-' ;
                                                            }
                                                            ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <!-- Hiển thị tất cả thiết bị -->
                                <?php foreach ($priceByDevice as $deviceId => $data): ?>
                                    <?php if (!empty($data['prices'])): ?>
                                        <div class="device-section border-top">
                                            <!-- Header thiết bị -->
                                            <div class="p-4 bg-light">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-tools fa-2x text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h5 class="mb-1 text-dark">
                                                            <?php echo htmlspecialchars($data['device']['tenThietBi']); ?>
                                                        </h5>
                                                        <?php if (!empty($data['device']['moTa'])): ?>
                                                            <p class="mb-0 text-muted small">
                                                                <?php echo htmlspecialchars($data['device']['moTa']); ?>
                                                            </p>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="badge bg-success">
                                                        <?php echo count($data['prices']); ?> dịch vụ
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bảng giá -->
                                            <div class="table-responsive">
                                                <table class="table table-hover mb-0">
                                                    <thead class="table-secondary">
                                                        <tr>
                                                            <th width="50%">Chi Tiết Lỗi</th>
                                                            <th width="25%">Giá Tham Khảo</th>
                                                            <th width="25%">Đơn vị tính</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($data['prices'] as $price): ?>
                                                            <tr>
                                                                <td>
                                                                    <div class="fw-semibold"><?php echo htmlspecialchars($price['chitietLoi']); ?></div>
                                                                    <?php if (!empty($price['moTa'])): ?>
                                                                        <small class="text-muted"><?php echo htmlspecialchars($price['moTa']); ?></small>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <span class="fw-bold text-success">
                                                                        <?php echo !empty($price['khoangGia']) ? htmlspecialchars($price['khoangGia']) : 'Liên hệ'; ?>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                    <span class="">
                                                                        <?php 
                                                                        if ($price['DVT']) {
                                                                            echo $price['DVT'] . ' giờ';
                                                                        }else{
                                                                            echo '-';
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="p-5 text-center">
                                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Không có bảng giá</h5>
                                <p class="text-muted">
                                    <?php if ($selectedDeviceId): ?>
                                        Không có bảng giá cho thiết bị này
                                    <?php else: ?>
                                        Không có bảng giá trong hệ thống
                                    <?php endif; ?>
                                </p>
                                <a href="<?php echo url('bang-gia'); ?>" class="btn btn-primary">
                                    <i class="fas fa-arrow-left me-1"></i>Xem tất cả
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ghi chú -->
                <div class="alert alert-info mt-4">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Lưu ý:</h6>
                    <ul class="mb-0">
                        <li>Giá trên là giá tham khảo, có thể thay đổi tùy theo tình trạng thiết bị</li>
                        <li>Thời gian sửa chữa có thể thay đổi tùy theo mức độ hư hỏng</li>
                        <li>Báo giá chính xác sẽ được kỹ thuật viên kiểm tra và thông báo sau</li>
                        <li>Giá đã bao gồm VAT và phí dịch vụ</li>
                    </ul>
                </div>

                <!-- Thông tin liên hệ -->
                <div class="card bg-light border-0 mt-4">
                    <div class="card-body text-center">
                        <h5 class="text-primary mb-3">Cần hỗ trợ thêm?</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-phone-alt text-primary me-2"></i>
                                    <div>
                                        <div class="fw-bold">Hotline</div>
                                        <div class="text-muted">1900 1234</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <div>
                                        <div class="fw-bold">Thời gian làm việc</div>
                                        <div class="text-muted">8:00 - 21:00</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-calendar-check text-primary me-2"></i>
                                    <div>
                                        <div class="fw-bold">Làm việc cả tuần</div>
                                        <div class="text-muted">Thứ 2 - Chủ nhật</div>
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
.device-section {
    transition: all 0.3s ease;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.table td {
    vertical-align: middle;
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.8rem;
}

.card-header {
    border-bottom: 2px solid rgba(255,255,255,0.1);
}

.device-section:last-child {
    border-bottom: none;
}

.form-select:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tự động submit form khi chọn thiết bị
    document.getElementById('device_id').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
});
</script>