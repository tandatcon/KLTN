<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Hóa Đơn Dịch Vụ - TechCare";
include VIEWS_PATH . '/header.php';

// Khởi tạo services



require_once __DIR__ . '/../../controllers/OrderController.php';
require_once __DIR__ . '/../../function/quytrinh.php';

require_once __DIR__ . '/../../function/donhang.php';

$orderController = new DonHangService($db);
$serviceProcessModel = new QuyTrinhService($db);

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

// Tính tổng chi phí
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
?>

<style>
.invoice-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.invoice-header {
    border-bottom: 3px solid #007bff;
    padding-bottom: 1.5rem;
}

.company-logo {
    font-size: 2rem;
    color: #007bff;
    font-weight: bold;
}

.invoice-title {
    color: #007bff;
    font-weight: bold;
    font-size: 1.8rem;
}

.customer-info, .technician-info {
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.table th {
    background: #007bff;
    color: white;
    border: none;
    font-weight: 600;
}

.total-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    border: 2px solid #28a745;
}

.signature-section {
    border-top: 2px dashed #dee2e6;
    margin-top: 2rem;
    padding-top: 1.5rem;
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .invoice-container {
        border: none;
        box-shadow: none;
        margin: 0;
        padding: 0;
    }
    
    body {
        background: white !important;
        font-size: 12pt;
    }
    
    .table {
        font-size: 10pt;
    }
    
    .btn {
        display: none !important;
    }
}

.watermark {
    position: absolute;
    opacity: 0.1;
    font-size: 120px;
    transform: rotate(-45deg);
    z-index: -1;
    white-space: nowrap;
}
</style>

<main class="bg-light min-vh-100 py-4">
    <div class="container">
        <!-- Nút hành động -->
        <div class="text-center mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-print me-2"></i> In Hóa Đơn
            </button>
            <a href="<?php echo url('my_orders'); ?>" class="btn btn-secondary btn-lg me-2">
                <i class="fas fa-arrow-left me-2"></i> Quay Lại
            </a>
            <a href="<?php echo url('order-detail?id=' . $orderId); ?>" class="btn btn-info btn-lg">
                <i class="fas fa-info-circle me-2"></i> Xem Chi Tiết
            </a>
        </div>

        <!-- Hóa đơn -->
        <div class="invoice-container p-4 p-md-5">
            <!-- Watermark -->
            <div class="watermark text-primary" style="top: 30%; left: 10%;">TECHCARE</div>
            
            <!-- Header -->
            <div class="invoice-header mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="company-logo mb-2">
                            <i class="fas fa-laptop-code me-2"></i>TECHCARE
                        </div>
                        <p class="text-muted mb-1"><strong>Địa chỉ:</strong> 123 Nguyễn Văn Linh, Quận 7, TP.HCM</p>
                        <p class="text-muted mb-1"><strong>Hotline:</strong> 0909 123 456</p>
                        <p class="text-muted mb-0"><strong>Email:</strong> contact@techcare.com</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h1 class="invoice-title mb-2">HÓA ĐƠN DỊCH VỤ</h1>
                        <p class="mb-1 fs-5"><strong>Mã đơn:</strong> #<?php echo $orderId; ?></p>
                        <p class="mb-1"><strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($order['ngayDat'])); ?></p>
                        <span class="badge bg-success fs-6"><?php echo $totalCost > 0 ? 'ĐÃ THANH TOÁN' : 'CHỜ THANH TOÁN'; ?></span>
                    </div>
                </div>
            </div>

            <!-- Thông tin khách hàng & KTV -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="customer-info p-3 h-100">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-user me-2"></i>THÔNG TIN KHÁCH HÀNG
                        </h5>
                        <p class="mb-2"><strong>Họ tên:</strong> <?php echo htmlspecialchars($userInfo['hoTen'] ?? 'Khách hàng'); ?></p>
                        <p class="mb-2"><strong>SĐT:</strong> <?php echo htmlspecialchars($userInfo['sdt'] ?? 'N/A'); ?></p>
                        <p class="mb-2"><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email'] ?? 'N/A'); ?></p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['diemhen'] ?? 'N/A'); ?></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="technician-info p-3 h-100">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-user-cog me-2"></i>KỸ THUẬT VIÊN
                        </h5>
                        <?php if (!empty($technicianInfo)): ?>
                            <p class="mb-2"><strong>Họ tên:</strong> <?php echo htmlspecialchars($technicianInfo['hoTen']); ?></p>
                            <p class="mb-2"><strong>SĐT:</strong> <?php echo htmlspecialchars($technicianInfo['sdt'] ?? 'N/A'); ?></p>
                            <?php if (isset($technicianInfo['danhGia']) && $technicianInfo['danhGia'] > 0): ?>
                                <p class="mb-0">
                                    <strong>Đánh giá:</strong> 
                                    <span class="badge bg-warning text-dark">
                                        <?php echo number_format($technicianInfo['danhGia'], 1); ?>/5 ⭐
                                    </span>
                                </p>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-muted mb-0">Chưa có KTV tiếp nhận</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Chi tiết dịch vụ -->
            <div class="mb-4">
                <h5 class="text-primary mb-3">
                    <i class="fas fa-list me-2"></i>CHI TIẾT DỊCH VỤ
                </h5>
                
                <?php foreach ($devices as $index => $device): 
                    $maCTDon = $device['maCTDon'] ?? null;
                    $repairJobs = $maCTDon ? ($deviceRepairJobs[$maCTDon] ?? []) : [];
                    $deviceTotal = 0;
                    
                    foreach ($repairJobs as $job) {
                        $deviceTotal += $job['chiPhi'] ?? 0;
                    }
                ?>
                <div class="card border mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-laptop me-2"></i>
                            Thiết bị <?php echo $index + 1; ?>: <?php echo htmlspecialchars($device['tenThietBi'] ?? 'Thiết bị không xác định'); ?>
                            <?php if ($deviceTotal > 0): ?>
                                <span class="float-end badge bg-success"><?php echo number_format($deviceTotal); ?>đ</span>
                            <?php endif; ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Thông tin thiết bị -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <small class="text-muted">Tên thiết bị:</small>
                                <p class="mb-2"><strong><?php echo htmlspecialchars($device['tenThietBi'] ?? 'N/A'); ?></strong></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Phiên bản:</small>
                                <p class="mb-2"><strong><?php echo !empty($device['phienban']) ? htmlspecialchars($device['phienban']) : 'N/A'; ?></strong></p>
                            </div>
                            <?php if (!empty($device['motaTinhTrang'])): ?>
                                <div class="col-12">
                                    <small class="text-muted">Mô tả tình trạng:</small>
                                    <p class="mb-0"><?php echo htmlspecialchars($device['motaTinhTrang']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Công việc sửa chữa -->
                        <?php if (!empty($repairJobs)): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-primary">
                                        <tr>
                                            <th width="5%">STT</th>
                                            <th width="65%">Công việc sửa chữa</th>
                                            <th width="30%" class="text-end">Chi phí (VND)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($repairJobs as $idx => $job): ?>
                                        <tr>
                                            <td class="text-center"><?php echo $idx + 1; ?></td>
                                            <td><?php echo htmlspecialchars($job['loiSuaChua'] ?? ''); ?></td>
                                            <td class="text-end"><?php echo number_format($job['chiPhi'] ?? 0); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <td colspan="2" class="text-end fw-bold">Tổng thiết bị:</td>
                                            <td class="text-end fw-bold"><?php echo number_format($deviceTotal); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Chưa có công việc sửa chữa nào được thêm
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Tổng thanh toán -->
            <div class="total-section p-4">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-end"><strong>Tổng chi phí:</strong></td>
                                <td class="text-end" width="150"><?php echo number_format($totalCost); ?>đ</td>
                            </tr>
                            <tr>
                                <td class="text-end"><strong>VAT (0%):</strong></td>
                                <td class="text-end">0đ</td>
                            </tr>
                            <tr class="border-top">
                                <td class="text-end"><strong>Tổng thanh toán:</strong></td>
                                <td class="text-end fs-4 text-success fw-bold"><?php echo number_format($totalCost); ?>đ</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ghi chú và chữ ký -->
            <div class="signature-section">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-primary">Ghi chú:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Bảo hành 03 tháng cho linh kiện thay thế</li>
                                <li>Liên hệ ngay nếu có vấn đề phát sinh</li>
                                <li>Hóa đơn có giá trị trong vòng 30 ngày</li>
                            </ul>
                        </div>
                        <?php if (!empty($order['ghiChu'])): ?>
                            <div class="alert alert-warning mb-0">
                                <strong>Ghi chú của khách hàng:</strong><br>
                                <?php echo nl2br(htmlspecialchars($order['ghiChu'])); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 text-center">
                        <p class="mb-4">Ngày <?php echo date('d'); ?> tháng <?php echo date('m'); ?> năm <?php echo date('Y'); ?></p>
                        <p class="fw-bold mb-1">KỸ THUẬT VIÊN</p>
                        <p class="text-muted mb-4">(Ký và ghi rõ họ tên)</p>
                        <p class="border-top pt-2"><?php echo !empty($technicianInfo['hoTen']) ? htmlspecialchars($technicianInfo['hoTen']) : '___________________'; ?></p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-4 pt-3 border-top">
                <p class="text-muted mb-0">
                    <strong>Cảm ơn quý khách đã sử dụng dịch vụ của TechCare!</strong><br>
                    Mọi thắc mắc xin liên hệ: 0909 123 456 - contact@techcare.com
                </p>
            </div>
        </div>
    </div>
</main>

<script>
// Tự động in khi trang load (tùy chọn)
// window.onload = function() {
//     window.print();
// };
</script>

<?php
include VIEWS_PATH . '/footer.php';
?>