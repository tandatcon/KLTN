<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Chi Tiết Đơn Hàng - TechCare";
include VIEWS_PATH . '/header.php';

// Khởi tạo controller và models
require_once __DIR__ . '/../controllers/OrderController.php';
require_once __DIR__ . '/../models/ServiceProcess.php';

$orderController = new OrderController($db);
$serviceProcessModel = new ServiceProcess($db);

// Lấy mã đơn từ URL
$orderId = $_GET['id'] ?? 0;

// Lấy dữ liệu đơn hàng
$data = $orderController->showOrderDetail($orderId);

// Kiểm tra nếu không có dữ liệu
if (!$data || !isset($data['order'])) {
    $_SESSION['error'] = "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!";
    header('Location: ' . url('my_orders'));
    exit();
}

// Extract data
$order = $data['order'];
$userInfo = $data['userInfo'];
$orderHistory = $data['orderHistory'];
$deviceNames = $data['deviceNames'];

// Lấy thông tin bổ sung
$repairDetails = $orderController->getDeviceDetails($orderId);
$technicianInfo = $orderController->getOrderTechnician($orderId);

// Lấy danh sách công việc sửa chữa cho từng thiết bị
$deviceRepairJobs = [];
if (!empty($order['devices'])) {
    foreach ($order['devices'] as $device) {
        $maCTDon = $device['maCTDon'] ?? null;
        if ($maCTDon) {
            $deviceRepairJobs[$maCTDon] = $serviceProcessModel->getDeviceRepairDetails($orderId, $maCTDon);
        }
    }
}

// Xác định trạng thái đơn hàng
$statusInfo = [
    'class' => '',
    'icon' => '',
    'text' => ''
];

switch ((int) $order['trangThai']) {
    case 0:
        $statusInfo = [
            'class' => 'bg-danger text-white',
            'icon' => 'fas fa-times-circle',
            'text' => 'Đã hủy'
        ];
        break;
    case 1:
        $statusInfo = [
            'class' => 'bg-warning text-dark',
            'icon' => 'fas fa-clock',
            'text' => 'Đã đặt'
        ];
        break;
    case 2:
        $statusInfo = [
            'class' => 'bg-info text-white',
            'icon' => 'fas fa-tasks',
            'text' => 'Đã nhận'
        ];
        break;
    case 3:
        $statusInfo = [
            'class' => 'bg-success text-white',
            'icon' => 'fas fa-check-circle',
            'text' => 'Hoàn thành'
        ];
        break;
    default:
        $statusInfo = [
            'class' => 'bg-warning text-dark',
            'icon' => 'fas fa-clock',
            'text' => 'Đã đặt'
        ];
}
?>

<main class="bg-light min-vh-100 py-4">
    <div class="container">
        <!-- Header Section -->
        <div class="card bg-gradient-primary text-white shadow-lg mb-4 border-0">
            <div class="card-body p-4 p-md-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-2">
                            <i class="fas fa-file-invoice me-3"></i>Chi Tiết Đơn Hàng
                        </h1>
                        <p class="lead mb-0 opacity-75">Thông tin chi tiết đơn hàng của bạn</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="<?php echo url('my_orders'); ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-arrow-left me-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>
            </div>
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

        <div class="row g-4">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Thông tin đơn hàng -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h4 mb-0">
                            <i class="fas fa-info-circle me-2"></i>Thông Tin Đơn Hàng
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-hashtag text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Mã đơn</small>
                                        <strong class="text-dark">
                                            #<?php echo !empty($order['maDon']) ? htmlspecialchars($order['maDon']) : 'N/A'; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-calendar text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Ngày hẹn</small>
                                        <strong class="text-dark">
                                            <?php echo !empty($order['ngayDat']) ? date('d/m/Y', strtotime($order['ngayDat'])) : 'N/A'; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-clock text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Khung giờ hẹn</small>
                                        <strong class="text-dark">
                                            <?php
                                            $time_slots = [
                                                'sang' => 'Sáng (8:00 - 11:00)',
                                                'chieu' => 'Chiều (13:00 - 17:00)',
                                                'toi' => 'Tối (18:00 - 21:00)'
                                            ];
                                            echo !empty($order['gioDat']) ? ($time_slots[$order['gioDat']] ?? $order['gioDat']) : '<span class="fst-italic text-muted">Chưa có thông tin</span>';
                                            ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-map-marker-alt text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Địa điểm hẹn</small>
                                        <strong class="text-dark">
                                            <?php echo !empty($order['diemhen']) ? htmlspecialchars($order['diemhen']) : '<span class="fst-italic text-muted">Chưa có địa chỉ</span>'; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-tools text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Số lượng thiết bị</small>
                                        <strong class="text-dark">
                                            <?php echo !empty($order['devices']) ? count($order['devices']) : 0; ?> thiết bị
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-tag text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Trạng thái</small>
                                        <span class="badge <?php echo $statusInfo['class']; ?> px-3 py-2">
                                            <i class="<?php echo $statusInfo['icon']; ?> me-1"></i>
                                            <?php echo $statusInfo['text']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-home text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Nơi sửa chữa</small>
                                        <strong class="text-dark">
                                            <?php
                                            if (isset($order['noiSuaChua'])) {
                                                if ($order['noiSuaChua'] == 0) {
                                                    echo "🏠 Tại nhà";
                                                } else if ($order['noiSuaChua'] == 1) {
                                                    echo "🏪 Tại cửa hàng";
                                                } else {
                                                    echo '<span class="fst-italic text-muted">Chưa xác định</span>';
                                                }
                                            } else {
                                                echo '<span class="fst-italic text-muted">Chưa có thông tin</span>';
                                            }
                                            ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ghi chú của khách hàng -->
                        <?php if (!empty($order['ghiChu'])): ?>
                            <div class="mt-4 pt-3 border-top">
                                <h5 class="text-dark mb-3">
                                    <i class="fas fa-sticky-note me-2 text-warning"></i>Ghi Chú Của Bạn
                                </h5>
                                <div class="alert alert-warning">
                                    <?php echo nl2br(htmlspecialchars($order['ghiChu'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Danh sách thiết bị -->
                <?php if (!empty($order['devices'])): ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h4 mb-0">
                                <i class="fas fa-tools me-2"></i>Danh Sách Thiết Bị Sửa Chữa
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="devicesAccordion">
                                <?php foreach ($order['devices'] as $index => $device):
                                    $deviceSafe = [
                                        'loai_thietbi' => $device['loai_thietbi'] ?? '',
                                        'tenThietBi' => $device['tenThietBi'] ?? '',
                                        'thong_tin_thiet_bi' => $device['thong_tin_thiet_bi'] ?? null,
                                        'mota_tinhtrang' => $device['mota_tinhtrang'] ?? null,
                                        'maCTDon' => $device['maCTDon'] ?? null
                                    ];

                                    // Tìm thông tin sửa chữa
                                    $deviceRepair = null;
                                    if (!empty($repairDetails)) {
                                        foreach ($repairDetails as $repair) {
                                            if (isset($repair['maThietBi']) && $repair['maThietBi'] == $deviceSafe['loai_thietbi']) {
                                                $deviceRepair = $repair;
                                                break;
                                            }
                                        }
                                    }

                                    // Lấy danh sách công việc sửa chữa cho thiết bị này
                                    $repairJobs = [];
                                    $totalCost = 0;
                                    if (!empty($deviceSafe['maCTDon']) && isset($deviceRepairJobs[$deviceSafe['maCTDon']])) {
                                        $repairJobs = $deviceRepairJobs[$deviceSafe['maCTDon']];
                                        foreach ($repairJobs as $job) {
                                            $totalCost += $job['chiPhi'] ?? 0;
                                        }
                                    }

                                    $deviceName = !empty($deviceSafe['tenThietBi']) ?
                                        $deviceSafe['tenThietBi'] :
                                        ($deviceNames[$deviceSafe['loai_thietbi']] ?? $deviceSafe['loai_thietbi'] ?? 'Thiết bị không xác định');
                                ?>
                                <div class="accordion-item border-0 mb-3">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed bg-light" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#device<?php echo $index; ?>"
                                                aria-expanded="false">
                                            <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <span class="badge bg-primary">Thiết bị <?php echo $index + 1; ?></span>
                                                    <strong class="text-dark"><?php echo htmlspecialchars($deviceName); ?></strong>
                                                </div>
                                                <?php if ($totalCost > 0): ?>
                                                    <span class="badge bg-success fs-6">
                                                        <?php echo number_format($totalCost); ?>đ
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="device<?php echo $index; ?>" class="accordion-collapse collapse" 
                                         data-bs-parent="#devicesAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-4">
                                                <!-- Thông tin thiết bị -->
                                                <div class="col-md-6">
                                                    <div class="card h-100 border">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">
                                                                <i class="fas fa-info-circle me-2 text-primary"></i>Thông Tin Thiết Bị
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="mb-3">
                                                                <small class="text-muted d-block">Loại thiết bị</small>
                                                                <strong><?php echo htmlspecialchars($deviceName); ?></strong>
                                                            </div>
                                                            <?php if (!empty($deviceSafe['thong_tin_thiet_bi'])): ?>
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Mô tả</small>
                                                                    <span><?php echo htmlspecialchars($deviceSafe['thong_tin_thiet_bi']); ?></span>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (!empty($deviceSafe['mota_tinhtrang'])): ?>
                                                                <div>
                                                                    <small class="text-muted d-block">Mô tả tình trạng</small>
                                                                    <span><?php echo htmlspecialchars($deviceSafe['mota_tinhtrang']); ?></span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Kỹ thuật viên -->
                                                <div class="col-md-6">
                                                    <div class="card h-100 border">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">
                                                                <i class="fas fa-user-cog me-2 text-info"></i>Kỹ Thuật Viên
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <?php if (!empty($technicianInfo['hoTen'])): ?>
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Họ tên</small>
                                                                    <strong><?php echo htmlspecialchars($technicianInfo['hoTen']); ?></strong>
                                                                </div>
                                                                <?php if (!empty($technicianInfo['sdt'])): ?>
                                                                    <div>
                                                                        <small class="text-muted d-block">Điện thoại</small>
                                                                        <span><?php echo htmlspecialchars($technicianInfo['sdt']); ?></span>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <div class="text-center text-muted py-3">
                                                                    <i class="fas fa-user-clock fa-2x mb-2"></i>
                                                                    <p class="mb-0">Chưa có kỹ thuật viên tiếp nhận</p>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Chuẩn đoán và sửa chữa -->
                                                <div class="col-12">
                                                    <div class="card border">
                                                        <div class="card-header bg-light">
                                                            <h6 class="mb-0">
                                                                <i class="fas fa-clipboard-check me-2 text-success"></i>Chuẩn Đoán & Sửa Chữa
                                                            </h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row g-4">
                                                                <!-- Tình trạng thực tế -->
                                                                <div class="col-12">
                                                                    <div class="border-start border-3 border-primary ps-3 mb-4">
                                                                        <small class="text-muted d-block">
                                                                            <i class="fas fa-search me-1"></i>Tình trạng hư hại thực tế
                                                                        </small>
                                                                        <div class="mt-1">
                                                                            <?php echo (!empty($deviceRepair['chuandoanKTV'])) ?
                                                                                nl2br(htmlspecialchars($deviceRepair['chuandoanKTV'])) :
                                                                                '<em class="text-muted">Chưa có thông tin chuẩn đoán</em>'; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- DANH SÁCH CÔNG VIỆC SỬA CHỮA -->
                                                                <?php if (!empty($repairJobs)): ?>
                                                                    <div class="col-12">
                                                                        <div class="card border-success">
                                                                            <div class="card-header bg-success text-white py-2">
                                                                                <h6 class="mb-0">
                                                                                    <i class="fas fa-list-check me-2"></i>Danh Sách Công Việc Sửa Chữa
                                                                                </h6>
                                                                            </div>
                                                                            <div class="card-body p-0">
                                                                                <div class="table-responsive">
                                                                                    <table class="table table-bordered mb-0">
                                                                                        <thead class="table-light">
                                                                                            <tr>
                                                                                                <th width="10%">STT</th>
                                                                                                <th width="55%">Công việc</th>
                                                                                                <th width="20%">Chi phí (VND)</th>
                                                                                                <th width="15%">Loại</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $tongThietBi = 0;
                                                                                            foreach ($repairJobs as $idx => $chiTiet):
                                                                                                $tongThietBi += $chiTiet['chiPhi'];
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td class="text-center"><?php echo $idx + 1; ?></td>
                                                                                                    <td><?php echo htmlspecialchars($chiTiet['loiSuaChua']); ?></td>
                                                                                                    <td class="text-end"><?php echo number_format($chiTiet['chiPhi']); ?></td>
                                                                                                    <td class="text-center">
                                                                                                        <span class="badge bg-<?php echo $chiTiet['loai'] == 'chuan' ? 'primary' : 'warning'; ?>">
                                                                                                            <?php echo $chiTiet['loai']; ?>
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            <?php endforeach; ?>
                                                                                        </tbody>
                                                                                        <tfoot>
                                                                                            <tr class="table-secondary">
                                                                                                <td colspan="2" class="text-end fw-bold">Tổng cộng:</td>
                                                                                                <td class="text-end fw-bold"><?php echo number_format($tongThietBi); ?></td>
                                                                                                <td></td>
                                                                                            </tr>
                                                                                        </tfoot>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php else: ?>
                                                                    <div class="col-12">
                                                                        <div class="alert alert-info">
                                                                            <i class="fas fa-info-circle me-2"></i>
                                                                            Chưa có công việc sửa chữa nào được thêm
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <!-- Thiếu linh kiện -->
                                                                <div class="col-md-6">
                                                                    <div class="border-start border-3 border-info ps-3">
                                                                        <small class="text-muted d-block">
                                                                            <i class="fas fa-sticky-note me-1"></i>Thiếu linh kiện
                                                                        </small>
                                                                        <div class="mt-1">
                                                                            <?php
                                                                            $thieuLinhKien = isset($deviceRepair['thieuLinhKien']) && !empty($deviceRepair['thieuLinhKien'])
                                                                                ? nl2br(htmlspecialchars($deviceRepair['thieuLinhKien']))
                                                                                : '<em class="text-muted">Chưa có thông tin thiếu linh kiện</em>';
                                                                            echo $thieuLinhKien;
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Ghi chú kỹ thuật -->
                                                                <?php if (!empty($deviceRepair['ghi_chu_ky_thuat'])): ?>
                                                                    <div class="col-12">
                                                                        <div class="border-start border-3 border-secondary ps-3">
                                                                            <small class="text-muted d-block">
                                                                                <i class="fas fa-sticky-note me-1"></i>Ghi chú kỹ thuật
                                                                            </small>
                                                                            <div class="mt-1 alert alert-secondary">
                                                                                <?php echo nl2br(htmlspecialchars($deviceRepair['ghi_chu_ky_thuat'])); ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-tools text-muted fa-3x mb-3"></i>
                            <h4 class="text-muted">Không có thiết bị nào</h4>
                            <p class="text-muted">Đơn hàng này chưa có thiết bị được thêm vào</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Thông tin khách hàng -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="h4 mb-0">
                            <i class="fas fa-user me-2"></i>Thông Tin Khách Hàng
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-user-circle text-primary fa-2x"></i>
                            <div>
                                <h5 class="mb-1"><?php echo !empty($userInfo['hoTen']) ? htmlspecialchars($userInfo['hoTen']) : 'Khách hàng'; ?></h5>
                                <?php if (!empty($userInfo['email'])): ?>
                                    <div class="text-muted">
                                        <i class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($userInfo['email']); ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($userInfo['sdt'])): ?>
                                    <div class="text-muted">
                                        <i class="fas fa-phone me-2"></i><?php echo htmlspecialchars($userInfo['sdt']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tổng chi phí đơn hàng -->
                <?php
                $totalOrderCost = 0;
                if (!empty($order['devices'])) {
                    foreach ($order['devices'] as $device) {
                        $maCTDon = $device['maCTDon'] ?? null;
                        if ($maCTDon && isset($deviceRepairJobs[$maCTDon])) {
                            foreach ($deviceRepairJobs[$maCTDon] as $job) {
                                $totalOrderCost += $job['chiPhi'] ?? 0;
                            }
                        }
                    }
                }
                ?>

                <?php if ($totalOrderCost > 0): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h3 class="h4 mb-0">
                                <i class="fas fa-money-bill-wave me-2"></i>Tổng Chi Phí
                            </h3>
                        </div>
                        <div class="card-body text-center">
                            <h2 class="text-success fw-bold"><?php echo number_format($totalOrderCost); ?>đ</h2>
                            <p class="text-muted mb-0">Tổng chi phí sửa chữa cho đơn hàng</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo url('my_orders'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại danh sách
                            </a>
                            <button class="btn btn-outline-secondary" onclick="window.print()">
                                <i class="fas fa-print me-2"></i>
                                In hóa đơn
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Minimal custom CSS chỉ cho gradient và hiệu ứng */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Custom cho accordion */
.accordion-button:not(.collapsed) {
    background-color: #e7f1ff !important;
    color: #0c63e4 !important;
}

.accordion-button:focus {
    box-shadow: none;
    border-color: rgba(0,0,0,.125);
}

/* Custom cho bảng công việc */
.table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}
</style>

<?php
include VIEWS_PATH . '/footer.php';
?>