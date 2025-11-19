<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Chi Tiết Đơn Hàng - TechCare";
include VIEWS_PATH . '/header.php';

// Khởi tạo services
require_once __DIR__ . '/../function/donhang.php';
require_once __DIR__ . '/../function/quytrinh.php';

$donHangService = new DonHangService($db);
$quyTrinhService = new QuyTrinhService($db);

// Lấy mã đơn từ URL
$orderId = $_GET['id'] ?? 0;
$maKH = $_SESSION['user_id'] ?? null;

if (!$maKH) {
    header('Location: ' . url('login'));
    exit();
}

if (!$orderId) {
    $_SESSION['error'] = "Đơn hàng không tồn tại!";
    header('Location: ' . url('my_orders'));
    exit();
}

// Lấy dữ liệu đơn hàng
$order = $donHangService->getOrderDetail($orderId, $maKH);

// Kiểm tra nếu không có dữ liệu
if (!$order) {
    $_SESSION['error'] = "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!";
    header('Location: ' . url('my_orders'));
    exit();
}

// Lấy thông tin bổ sung
$userInfo = $donHangService->getCustomerInfo($maKH);
$devices = $donHangService->getOrderDevicesDetail($orderId);
$technicianInfo = $donHangService->getTechnicianInfo($order['maKTV']);
$orderHistory = $donHangService->getServiceActions($orderId);

// Lấy danh sách công việc sửa chữa cho từng thiết bị
$deviceRepairJobs = [];
$deviceDiagnoses = [];
foreach ($devices as $device) {
    $maCTDon = $device['maCTDon'] ?? null;
    if ($maCTDon) {
        $deviceRepairJobs[$maCTDon] = $donHangService->getDeviceRepairDetails($orderId, $maCTDon);
        $deviceDiagnoses[$maCTDon] = $donHangService->getDeviceDiagnosis($orderId, $maCTDon);
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
                                        <strong class="text-dark">#<?php echo htmlspecialchars($orderId); ?></strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-calendar text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Ngày đặt</small>
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
                                        <small class="text-muted d-block">Khung giờ</small>
                                        <strong class="text-dark">
                                            <?php echo !empty($order['gioBatDau']) ? $order['gioBatDau'] . ' - ' . $order['gioKetThuc'] : 'N/A'; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-map-marker-alt text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Địa điểm</small>
                                        <strong class="text-dark">
                                            <?php echo !empty($order['diemhen']) ? htmlspecialchars($order['diemhen']) : 'N/A'; ?>
                                        </strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <i class="fas fa-tools text-primary mt-1"></i>
                                    <div>
                                        <small class="text-muted d-block">Số thiết bị</small>
                                        <strong class="text-dark"><?php echo count($devices); ?> thiết bị</strong>
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
                                                    echo 'Chưa xác định';
                                                }
                                            } else {
                                                echo 'N/A';
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
                <!-- Thông tin thanh toán -->
                <?php if ($order['thanhToan'] > 0): ?>
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light border-bottom">
                            <h3 class="h4 mb-0 text-dark">
                                <i class="fas fa-credit-card me-2"></i>Thông Tin Thanh Toán
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="fas fa-money-bill-wave text-dark mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Trạng thái thanh toán</small>
                                            <?php if ($order['thanhToan'] == 1): ?>
                                                <span class="text-dark fw-bold">
                                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán - Chuyển khoản
                                                </span>
                                            <?php elseif ($order['thanhToan'] == 2): ?>
                                                <span class="text-dark fw-bold">
                                                    <i class="fas fa-check-circle me-1"></i>Đã thanh toán - Tiền mặt
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <i class="fas fa-calendar-check text-dark mt-1"></i>
                                        <div>
                                            <small class="text-muted d-block">Ngày thanh toán</small>
                                            <strong class="text-dark">
                                                <?php echo !empty($order['ngayThanhToan']) ? date('d/m/Y H:i', strtotime($order['ngayThanhToan'])) : 'N/A'; ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($order['tongTien'])): ?>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="fas fa-coins text-dark mt-1"></i>
                                            <div>
                                                <small class="text-muted d-block">Tổng tiền</small>
                                                <strong class="text-dark fs-5">
                                                    <?php echo number_format($order['tongTien']); ?>đ
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($order['diemSuDung'])): ?>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="fas fa-gift text-dark mt-1"></i>
                                            <div>
                                                <small class="text-muted d-block">Điểm sử dụng</small>
                                                <strong class="text-dark">
                                                    <?php echo number_format($order['diemSuDung']); ?> điểm
                                                </strong>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Danh sách thiết bị -->
                <?php if (!empty($devices)): ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h3 class="h4 mb-0">
                                <i class="fas fa-tools me-2"></i>Danh Sách Thiết Bị
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="devicesAccordion">
                                <?php foreach ($devices as $index => $device):
                                    $maCTDon = $device['maCTDon'] ?? null;
                                    $repairJobs = $maCTDon ? ($deviceRepairJobs[$maCTDon] ?? []) : [];
                                    $diagnosis = $maCTDon ? ($deviceDiagnoses[$maCTDon] ?? null) : null;

                                    $totalCost = 0;
                                    foreach ($repairJobs as $job) {
                                        $totalCost += $job['chiPhi'] ?? 0;
                                    }
                                    ?>
                                    <div class="accordion-item border-0 mb-3">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed bg-light" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#device<?php echo $index; ?>"
                                                aria-expanded="false">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="badge bg-primary">Thiết bị <?php echo $index + 1; ?></span>
                                                        <strong
                                                            class="text-dark"><?php echo htmlspecialchars($device['tenThietBi'] ?? 'Thiết bị không xác định'); ?></strong>
                                                    </div>
                                                    <?php if ($totalCost > 0): ?>
                                                        <span
                                                            class="badge bg-success fs-6"><?php echo number_format($totalCost); ?>đ</span>
                                                    <?php endif; ?>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="device<?php echo $index; ?>" class="accordion-collapse collapse"
                                            data-bs-parent="#devicesAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-4">
                                                    <!-- Thông tin thiết bị -->
                                                    <div class="col-12">
                                                        <div class="card h-100 border">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0">
                                                                    <i class="fas fa-info-circle me-2 text-primary"></i>Thông
                                                                    Tin Thiết Bị
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-3">
                                                                        <small class="text-muted d-block">Tên thiết bị</small>
                                                                        <strong><?php echo htmlspecialchars($device['tenThietBi'] ?? 'N/A'); ?></strong>
                                                                    </div>
                                                                    <div class="col-md-6 mb-3">
                                                                        <small class="text-muted d-block">Phiên bản</small>
                                                                        <strong><?php echo !empty($device['phienban']) ? htmlspecialchars($device['phienban']) : 'N/A'; ?></strong>
                                                                    </div>
                                                                    <?php if (!empty($device['motaTinhTrang'])): ?>
                                                                        <div class="col-12">
                                                                            <small class="text-muted d-block">Mô tả tình
                                                                                trạng</small>
                                                                            <span><?php echo htmlspecialchars($device['motaTinhTrang']); ?></span>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Thông tin sửa chữa -->
                                                    <div class="col-12">
                                                        <div class="card border">
                                                            <div class="card-header bg-light">
                                                                <h6 class="mb-0">
                                                                    <i
                                                                        class="fas fa-clipboard-check me-2 text-success"></i>Thông
                                                                    Tin Sửa Chữa
                                                                </h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row g-4">
                                                                    <!-- Chẩn đoán -->
                                                                    <?php if ($diagnosis && !empty($diagnosis['tinh_trang_thuc_te'])): ?>
                                                                        <div class="col-12">
                                                                            <div
                                                                                class="border-start border-3 border-primary ps-3 mb-4">
                                                                                <small class="text-muted d-block">
                                                                                    <i class="fas fa-search me-1"></i>Chẩn đoán của
                                                                                    KTV
                                                                                </small>
                                                                                <div class="mt-1">
                                                                                    <?php echo nl2br(htmlspecialchars($diagnosis['tinh_trang_thuc_te'])); ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <!-- Công việc sửa chữa -->
                                                                    <?php if (!empty($repairJobs)): ?>
                                                                        <div class="col-12">
                                                                            <div class="card border-success">
                                                                                <div class="card-header bg-success text-white py-2">
                                                                                    <h6 class="mb-0">
                                                                                        <i class="fas fa-list-check me-2"></i>Danh
                                                                                        Sách Công Việc
                                                                                    </h6>
                                                                                </div>
                                                                                <div class="card-body p-0">
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered mb-0">
                                                                                            <thead class="table-light">
                                                                                                <tr>
                                                                                                    <th width="10%">STT</th>
                                                                                                    <th width="60%">Công việc</th>
                                                                                                    <th width="30%">Chi phí (VND)
                                                                                                    </th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <tbody>
                                                                                                <?php foreach ($repairJobs as $idx => $chiTiet): ?>
                                                                                                    <tr>
                                                                                                        <td class="text-center">
                                                                                                            <?php echo $idx + 1; ?>
                                                                                                        </td>
                                                                                                        <td><?php echo htmlspecialchars($chiTiet['loiSuaChua'] ?? ''); ?>
                                                                                                        </td>
                                                                                                        <td class="text-end">
                                                                                                            <?php echo number_format($chiTiet['chiPhi'] ?? 0); ?>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                <?php endforeach; ?>
                                                                                            </tbody>
                                                                                            <tfoot>
                                                                                                <tr class="table-secondary">
                                                                                                    <td colspan="2"
                                                                                                        class="text-end fw-bold">
                                                                                                        Tổng cộng:</td>
                                                                                                    <td class="text-end fw-bold">
                                                                                                        <?php echo number_format($totalCost); ?>
                                                                                                    </td>
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
                                <h5 class="mb-1">
                                    <?php echo !empty($userInfo['hoTen']) ? htmlspecialchars($userInfo['hoTen']) : 'Khách hàng'; ?>
                                </h5>
                                <?php if (!empty($userInfo['email'])): ?>
                                    <div class="text-muted">
                                        <i
                                            class="fas fa-envelope me-2"></i><?php echo htmlspecialchars($userInfo['email']); ?>
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

                <!-- Thông tin KTV -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h3 class="h4 mb-0">
                            <i class="fas fa-user-cog me-2"></i>Kỹ Thuật Viên
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($technicianInfo)): ?>
                            <div class="text-center mb-3">
                                <i class="fas fa-user-cog fa-3x text-info mb-3"></i>
                                <h5 class="text-dark mb-2"><?php echo htmlspecialchars($technicianInfo['hoTen']); ?></h5>

                                <!-- Hiển thị số sao đánh giá -->
                                <?php if (isset($technicianInfo['danhGia']) && $technicianInfo['danhGia'] > 0): ?>
                                    <div class="mb-3">
                                        <span class="badge bg-warning text-dark fs-6">
                                            <i class="fas fa-star me-1"></i>
                                            <?php echo number_format($technicianInfo['danhGia'], 1); ?>/5
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <div class="mb-3">
                                        <span class="badge bg-secondary fs-6">
                                            <i class="fas fa-star me-1"></i>
                                            Chưa có đánh giá
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <!-- Hiển thị số điện thoại -->
                                <?php if (!empty($technicianInfo['sdt'])): ?>
                                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                        <i class="fas fa-phone text-success"></i>
                                        <span class="text-dark"><?php echo htmlspecialchars($technicianInfo['sdt']); ?></span>
                                    </div>
                                <?php endif; ?>

                                <!-- Hiển thị email -->
                                <?php if (!empty($technicianInfo['email'])): ?>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <i class="fas fa-envelope text-primary"></i>
                                        <span class="text-dark"><?php echo htmlspecialchars($technicianInfo['email']); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-user-clock fa-2x mb-2"></i>
                                <p class="mb-0">Chưa có KTV tiếp nhận</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo url('my_orders'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Quay lại danh sách
                            </a>

                            <?php if ($order['trangThai'] == 3): ?>
                                <!-- Chỉ hiển thị khi trạng thái = 3 (Đã hoàn thành) -->
                                <a href="<?php echo url('hoa-don?id=' . $orderId); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-file-invoice me-2"></i>
                                    Xem hóa đơn
                                </a>

                                <?php if ($order['thanhToan'] == 0): ?>
                                    <!-- Chỉ hiển thị nút thanh toán nếu chưa thanh toán -->
                                    <a href="<?php echo url('thanh-toan?id=' . $orderId); ?>" class="btn btn-success">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Thanh toán
                                    </a>
                                <?php else: ?>
                                    <!-- Đã thanh toán thì hiển thị thông báo -->
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check-circle me-2"></i>
                                        Đã thanh toán
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</main>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #e7f1ff !important;
        color: #0c63e4 !important;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(0, 0, 0, .125);
    }

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