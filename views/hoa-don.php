<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Hóa Đơn Dịch Vụ - TechCare";
include VIEWS_PATH . '/header.php';

require_once __DIR__ . '/../function/donhang.php';
require_once __DIR__ . '/../function/quytrinh.php';

$donHangService = new DonHangService($db);
$quyTrinhService = new QuyTrinhService($db);

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

$order = $donHangService->getOrderDetail($orderId, $maKH);
if (!$order) {
    $_SESSION['error'] = "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!";
    header('Location: ' . url('my_orders'));
    exit();
}

$userInfo = $donHangService->getCustomerInfo($maKH);
$devices = $donHangService->getOrderDevicesDetail($orderId);
$technicianInfo = $donHangService->getTechnicianInfo($order['maKTV']);

$totalCost = 0;
$deviceRepairJobs = [];
foreach ($devices as $device) {
    $maCTDon = $device['maCTDon'] ?? null;
    if ($maCTDon) {
        $repairJobs = $donHangService->getDeviceRepairDetails($orderId, $maCTDon);
        $deviceRepairJobs[$maCTDon] = $repairJobs;
        foreach ($repairJobs as $job) {
            $totalCost += $job['chiPhi'] ?? 0;
        }
    }
}

// XÁC ĐỊNH TRẠNG THÁI VÀ PHƯƠNG THỨC THANH TOÁN CHI TIẾT
$paymentStatus = 'Chưa thanh toán';
$paymentBadgeClass = 'bg-warning';
$paymentMethod = '';
$paymentIcon = 'fa-clock';

switch ($order['thanhToan']) {
    case 1:
        $paymentStatus = 'Đã thanh toán';
        $paymentBadgeClass = 'bg-success';
        $paymentMethod = 'Chuyển khoản VNPay';
        $paymentIcon = 'fa-credit-card';
        break;
    case 2:
        $paymentStatus = 'Chờ xác nhận tiền mặt';
        $paymentBadgeClass = 'bg-warning';
        $paymentMethod = 'Tiền mặt';
        $paymentIcon = 'fa-money-bill-wave';
        break;
    case 3:
        $paymentStatus = 'Đã thanh toán';
        $paymentBadgeClass = 'bg-success';
        $paymentMethod = 'Tiền mặt';
        $paymentIcon = 'fa-money-bill-wave';
        break;
    default:
        $paymentStatus = 'Chưa thanh toán';
        $paymentBadgeClass = 'bg-secondary';
        $paymentMethod = '';
        $paymentIcon = 'fa-clock';
        break;
}

// Xác định trạng thái đơn hàng
$orderStatus = 'Không xác định';
$orderStatusClass = 'bg-secondary';

switch ($order['trangThai']) {
    case 1:
        $orderStatus = 'Đã tiếp nhận';
        $orderStatusClass = 'bg-info';
        break;
    case 2:
        $orderStatus = 'Đang sửa chữa';
        $orderStatusClass = 'bg-warning';
        break;
    case 3:
        $orderStatus = 'Đang thực hiện';
        $orderStatusClass = 'bg-primary';
        break;
    case 4:
        $orderStatus = 'Hoàn thành';
        $orderStatusClass = 'bg-success';
        break;
    default:
        $orderStatus = 'Không xác định';
        $orderStatusClass = 'bg-secondary';
        break;
}
?>

<style>
@media print {
    body { background: white !important; }
    .no-print { display: none !important; }
    .invoice-card { box-shadow: none !important; border: 1px solid #000 !important; }
    .watermark { opacity: 0.1 !important; }
}
.watermark {
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-45deg);
    font-size: 80px;
    font-weight: bold;
    color: #e3f2fd;
    opacity: 0.3;
    pointer-events: none;
    z-index: 0;
    user-select: none;
}
.payment-details {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    padding: 15px;
    border-left: 4px solid #28a745;
}
.status-badge {
    font-size: 14px;
    padding: 8px 12px;
}
</style>

<main class="py-4">
    <!-- Nút hành động (không in) -->
    <div class="container-fluid no-print mb-4">
        <div class="text-center">
            <div class="btn-group" role="group">
                <a href="<?= url('my_orders') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay Lại
                </a>
                <a href="<?= url('order-detail?id=' . $orderId) ?>" class="btn btn-info">
                    <i class="fas fa-info-circle me-2"></i>Chi Tiết Đơn
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>In Hóa Đơn
                </button>
            </div>
        </div>
    </div>

    <!-- Hóa đơn chính - căn giữa, rộng ~50-60% trên desktop -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-7">
                <div class="card invoice-card shadow-sm position-relative overflow-hidden">
                    <!-- Watermark -->
                    <div class="watermark">TECHCARE</div>

                    <div class="card-body p-4 p-md-5 position-relative z-1">

                        <!-- Header -->
                        <div class="row align-items-center mb-4 text-center text-md-start">
                            <div class="col-md-6">
                                <h2 class="fw-bold text-primary mb-1">TECHCARE</h2>
                                <p class="text-muted small mb-0">123 Nguyễn Văn Linh, Q.7, TP.HCM</p>
                                <p class="text-muted small mb-0">Hotline: 0909 123 456</p>
                                <p class="text-muted small mb-0">Email: contact@techcare.com</p>
                            </div>
                            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                                <h1 class="display-6 fw-bold text-primary mb-1">HÓA ĐƠN</h1>
                                <p class="mb-1"><strong>Mã đơn:</strong> #<?= $orderId ?></p>
                                <p class="mb-2 small text-muted"><?= date('d/m/Y H:i', strtotime($order['ngayDat'])) ?></p>
                                
                                <!-- Hiển thị trạng thái đơn hàng -->
                                <span class="badge <?= $orderStatusClass ?> status-badge mb-2">
                                    <i class="fas fa-clipboard-list me-1"></i><?= $orderStatus ?>
                                </span>
                                
                                <!-- Hiển thị trạng thái thanh toán -->
                                <?php if ($order['thanhToan'] > 0): ?>
                                <span class="badge <?= $paymentBadgeClass ?> status-badge">
                                    <i class="fas <?= $paymentIcon ?> me-1"></i><?= $paymentStatus ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Thông tin khách hàng & KTV -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <h5 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-user me-2"></i>KHÁCH HÀNG
                                </h5>
                                <table class="table table-borderless table-sm">
                                    <tr><td class="text-muted pe-3" width="35%">Họ tên:</td><td><strong><?= htmlspecialchars($userInfo['hoTen'] ?? 'N/A') ?></strong></td></tr>
                                    <tr><td class="text-muted pe-3">SĐT:</td><td><?= htmlspecialchars($userInfo['sdt'] ?? 'N/A') ?></td></tr>
                                    <tr><td class="text-muted pe-3">Địa chỉ:</td><td><?= htmlspecialchars($order['diemhen'] ?? 'N/A') ?></td></tr>
                                    <?php if (!empty($userInfo['email'])): ?>
                                    <tr><td class="text-muted pe-3">Email:</td><td><?= htmlspecialchars($userInfo['email']) ?></td></tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary fw-bold mb-2">
                                    <i class="fas fa-tools me-2"></i>KỸ THUẬT VIÊN
                                </h5>
                                <?php if (!empty($technicianInfo)): ?>
                                    <table class="table table-borderless table-sm">
                                        <tr><td class="text-muted pe-3" width="35%">Họ tên:</td><td><strong><?= htmlspecialchars($technicianInfo['hoTen']) ?></strong></td></tr>
                                        <tr><td class="text-muted pe-3">SĐT:</td><td><?= htmlspecialchars($technicianInfo['sdt'] ?? 'N/A') ?></td></tr>
                                        <?php if (!empty($technicianInfo['danhGia'])): ?>
                                        <tr>
                                            <td class="text-muted pe-3">Đánh giá:</td>
                                            <td><span class="badge bg-warning text-dark"><?= number_format($technicianInfo['danhGia'], 1) ?>/5 <i class="fas fa-star ms-1"></i></span></td>
                                        </tr>
                                        <?php endif; ?>
                                    </table>
                                <?php else: ?>
                                    <p class="text-muted"><i class="fas fa-info-circle me-1"></i>Chưa phân công KTV</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        

                        <!-- Danh sách thiết bị -->
                        <h5 class="text-primary fw-bold mb-3">
                            <i class="fas fa-list-check me-2"></i>CHI TIẾT DỊCH VỤ
                        </h5>
                        <?php foreach ($devices as $index => $device):
                            $maCTDon = $device['maCTDon'] ?? null;
                            $repairJobs = $maCTDon ? ($deviceRepairJobs[$maCTDon] ?? []) : [];
                            $deviceTotal = array_sum(array_column($repairJobs, 'chiPhi'));
                        ?>
                        <div class="border rounded p-3 mb-3 bg-light">
                            <h6 class="fw-bold text-primary mb-2">
                                <i class="fas fa-laptop me-2"></i>
                                Thiết bị <?= $index + 1 ?>: <?= htmlspecialchars($device['tenThietBi'] ?? 'Không xác định') ?>
                                <?php if ($deviceTotal > 0): ?>
                                    <span class="float-end badge bg-success fs-6"><?= number_format($deviceTotal) ?>đ</span>
                                <?php endif; ?>
                            </h6>
                            <?php if (!empty($device['motaTinhTrang'])): ?>
                                <p class="small text-muted mb-2"><strong>Tình trạng:</strong> <?= htmlspecialchars($device['motaTinhTrang']) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($repairJobs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-primary text-center">
                                            <tr>
                                                <th width="10%">STT</th>
                                                <th>Công việc sửa chữa</th>
                                                <th width="25%">Chi phí</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($repairJobs as $idx => $job): ?>
                                            <tr>
                                                <td class="text-center"><?= $idx + 1 ?></td>
                                                <td><?= htmlspecialchars($job['loiSuaChua'] ?? '') ?></td>
                                                <td class="text-end fw-bold"><?= number_format($job['chiPhi'] ?? 0) ?>đ</td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr class="table-secondary">
                                                <td colspan="2" class="text-end fw-bold">Tổng thiết bị:</td>
                                                <td class="text-end fw-bold"><?= number_format($deviceTotal) ?>đ</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info py-2 mb-0 small">
                                    <i class="fas fa-info-circle me-1"></i>Chưa có công việc sửa chữa
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>

                        <!-- Tổng tiền -->
                        <div class="border-top pt-3 mt-4">
                            <table class="table table-borderless float-end" style="width: auto;">
                                <tr>
                                    <td class="text-end fw-bold pe-4">Tổng chi phí:</td>
                                    <td class="text-end fw-bold" width="150"><?= number_format($totalCost) ?>đ</td>
                                </tr>
                                
                                <!-- Hiển thị giảm giá nếu có -->
                                <?php if (!empty($order['tienGiamGia']) && $order['tienGiamGia'] > 0): ?>
                                <tr>
                                    <td class="text-end text-muted">Giảm trừ chi phí bằng điểm tích lũy:</td>
                                    <td class="text-end text-success">-<?= number_format($order['tienGiamGia']) ?>đ</td>
                                </tr>
                                <?php endif; ?>
                                
                                <tr>
                                    <td class="text-end text-muted">VAT (0%):</td>
                                    <td class="text-end">0đ</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-end fs-5 fw-bold text-success">TỔNG THANH TOÁN:</td>
                                    <td class="text-end fs-5 fw-bold text-success">
                                        <?= number_format($totalCost - ($order['tienGiamGia'] ?? 0)) ?>đ
                                    </td>
                                </tr>
                                
                                <!-- Hiển thị phương thức thanh toán nếu đã thanh toán -->
                                <?php if ($order['thanhToan'] > 0): ?>
                                <tr>
                                    <td class="text-end text-muted">Phương thức:</td>
                                    <td class="text-end fw-bold text-primary"><?= $paymentMethod ?></td>
                                </tr>
                                <?php if (!empty($order['ngayThanhToan'])): ?>
                                <tr>
                                    <td class="text-end text-muted">Ngày thanh toán:</td>
                                    <td class="text-end"><?= date('d/m/Y H:i', strtotime($order['ngayThanhToan'])) ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php endif; ?>
                            </table>
                            <div class="clearfix"></div>
                        </div>

                        <hr class="my-4">

                        <!-- Ghi chú & chữ ký -->
                        <div class="row mt-4">
                            <div class="col-md-7">
                                <h6 class="fw-bold text-primary">
                                    <i class="fas fa-sticky-note me-2"></i>Ghi chú:
                                </h6>
                                <ul class="small text-muted">
                                    <li>Bảo hành linh kiện & công sửa chữa: 03 tháng</li>
                                    <li>Liên hệ ngay nếu máy có vấn đề trong thời gian bảo hành</li>
                                    <li>Hóa đơn có giá trị xuất trình khi yêu cầu bảo hành</li>
                                </ul>
                            </div>
                            <div class="col-md-5 text-center mt-4 mt-md-0">
                                <p class="mb-1">TP.HCM, ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?></p>
                                <p class="fw-bold mb-4">KỸ THUẬT VIÊN</p>
                                <div style="height: 80px;"></div>
                                <p class="border-top pt-2 fw-bold">
                                    <?= !empty($technicianInfo['hoTen']) ? htmlspecialchars($technicianInfo['hoTen']) : '___________________' ?>
                                </p>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-4 border-top text-muted small">
                            <p class="mb-0"><strong>Cảm ơn Quý khách đã sử dụng dịch vụ!</strong></p>
                            <p>Hotline: 0909 123 456 | Email: contact@techcare.com | Website: techcare.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include VIEWS_PATH . '/footer.php'; ?>