<?php
ob_start();
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config.php';
    require_once __DIR__ . '/../helpers.php';
}

$pageTitle = "Thanh Toán - TechCare";
include VIEWS_PATH . '/header.php';

// Sử dụng Model và Controller mới
require_once __DIR__ . '/../models/mOrders.php';
require_once __DIR__ . '/../controllers/cOrders.php';
require_once __DIR__ . '/../controllers/cPayment.php';

$ordersController = new cOrders();
$paymentController = new cPayment();

$orderId = $_GET['id'] ?? 0;
$maKH = $_SESSION['user_id'] ?? null;

if (!$maKH) {
    header('Location: ' . url('login'));
    exit();
}

if (!$orderId) {
    $_SESSION['error'] = "Đơn hàng không tồn tại!";
    header('Location: ' . url('don-cua-toi'));
    exit();
}

$order = $ordersController->getOrderDetail($orderId, $maKH);
if (!$order) {
    $_SESSION['error'] = "Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!";
    header('Location: ' . url('don-cua-toi'));
    exit();
}

// Kiểm tra nếu đã thanh toán rồi
if ($order['thanhToan'] == 1) {
    $_SESSION['success'] = "Đơn hàng đã được thanh toán!";
    header('Location: ' . url('chi-tiet-don?id=' . $orderId));
    exit();
}

$userInfo = $ordersController->getCustomerInfo($maKH);
$devices = $ordersController->getOrderDevicesDetail($orderId);
$technicianInfo = $ordersController->getTechnicianInfo($order['maKTV']);

// Tính tổng chi phí và phân loại
$totalCost = 0;
$deviceRepairJobs = [];
$deviceTotals = [];

foreach ($devices as $device) {
    $maCTDon = $device['maCTDon'] ?? null;
    if ($maCTDon) {
        $repairJobs = $ordersController->getDeviceRepairDetails($orderId, $maCTDon);
        $deviceRepairJobs[$maCTDon] = $repairJobs;

        $deviceTotal = 0;
        foreach ($repairJobs as $job) {
            $deviceTotal += $job['chiPhi'] ?? 0;
            $totalCost += $job['chiPhi'] ?? 0;
        }
        $deviceTotals[$maCTDon] = $deviceTotal;
    }
}

// Xử lý thanh toán
// Trong phần xử lý form thanh toán
// Trong phần xử lý form thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['thanh_toan'])) {
    $diemSuDung = intval($_POST['diem_su_dung'] ?? 0);
    $paymentMethod = $_POST['payment_method'] ?? 'vnpay';

    // Lấy điểm tích lũy hiện tại của khách hàng
    $diemHienCo = $ordersController->getDiemTichLuy($maKH);

    if ($diemSuDung > $diemHienCo) {
        $_SESSION['error'] = "Số điểm sử dụng vượt quá điểm tích lũy hiện có!";
    } else {
        // XỬ LÝ PHÂN LOẠI PHƯƠNG THỨC THANH TOÁN
        if ($paymentMethod === 'cash') {
            // Thanh toán tiền mặt
            $result = $paymentController->processCashPayment($orderId, $maKH, $diemSuDung);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                header('Location: ' . url('chi-tiet-don?id=' . $orderId));
                exit();
            } else {
                $_SESSION['error'] = $result['error'] ?? "Có lỗi xảy ra khi xác nhận thanh toán tiền mặt!";
            }
            
        } else {
            // Thanh toán qua VNPay
            $result = $paymentController->processVNPayPayment($orderId, $totalCost, $maKH, $diemSuDung);

            if ($result['success']) {
                if (isset($result['payment_url'])) {
                    // Chuyển hướng đến VNPay
                    header('Location: ' . $result['payment_url']);
                    exit();
                }
            } else {
                $_SESSION['error'] = $result['error'] ?? "Có lỗi xảy ra khi thanh toán. Vui lòng thử lại!";
            }
        }
    }
}

// Lấy điểm tích lũy hiện tại
$diemHienCo = $ordersController->getDiemTichLuy($maKH);
$diemToiDaCoTheSuDung = min($diemHienCo, floor($totalCost / 1000));
$diemNhanDuoc = round($totalCost * 0.015 / 1000);
?>

<style>
    .payment-container {
        max-width: 1000px;
        margin: 0 auto;
        background: #f5f5f5;
        min-height: 100vh;
    }

    .shopee-style {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, .1);
        margin-bottom: 12px;
        padding: 16px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 500;
        color: #222;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f2f2f2;
    }

    .service-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .service-table th {
        background: #f8f9fa;
        padding: 12px 8px;
        text-align: left;
        font-weight: 500;
        color: #555;
        border-bottom: 2px solid #e9ecef;
    }

    .service-table td {
        padding: 12px 8px;
        border-bottom: 1px solid #f2f2f2;
        vertical-align: top;
    }

    .service-table tr:last-child td {
        border-bottom: none;
    }

    .device-header {
        background: #e3f2fd !important;
        font-weight: 600 !important;
        color: #1976d2 !important;
    }

    .device-header td {
        padding: 16px 8px !important;
        border-bottom: 2px solid #bbdefb !important;
    }

    .service-price {
        font-weight: 500;
        color: #ee4d2d;
        text-align: right;
    }

    .device-total {
        background: #f8f9fa;
        font-weight: 600;
    }

    .device-total td {
        border-top: 2px solid #e9ecef;
        padding: 16px 8px !important;
    }

    .points-section {
        background: #fffbf8;
        border: 1px solid #f8e3c5;
        border-radius: 4px;
        padding: 12px;
        margin: 12px 0;
    }

    .points-input {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 14px;
        width: 120px;
        text-align: center;
    }

    .payment-summary {
        background: #fffefb;
        border: 1px solid #f8e3c5;
        border-radius: 4px;
        padding: 16px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 14px;
    }

    .summary-row.total {
        border-top: 1px solid #f2f2f2;
        font-size: 18px;
        font-weight: 500;
        color: #ee4d2d;
        padding-top: 12px;
        margin-top: 8px;
    }

    .btn-payment {
        background: #ee4d2d;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: 500;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-payment:hover {
        background: #e73c1e;
    }

    .discount-badge {
        background: #ee4d2d;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .points-badge {
        background: #ffc107;
        color: #222;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .text-orange {
        color: #ee4d2d;
    }

    .text-green {
        color: #26aa99;
    }

    .service-description {
        color: #666;
        font-size: 13px;
        line-height: 1.4;
    }

    .payment-method {
        border: 2px solid #e8e8e8;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .payment-method:hover {
        border-color: #ee4d2d;
    }

    .payment-method.selected {
        border-color: #ee4d2d;
        background-color: #fff5f5;
    }

    .payment-method input[type="radio"] {
        margin-right: 10px;
    }

    .payment-icon {
        font-size: 24px;
        margin-right: 10px;
        color: #ee4d2d;
    }
</style>

<main class="payment-container py-3">
    <!-- Header -->
    <div class="d-flex align-items-center mb-3">
        <a href="<?= url('don-cua-toi') ?>" class="btn btn-light me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h4 class="mb-0 fw-bold">Thanh Toán</h4>
    </div>

    <!-- Thông báo -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="">
        <!-- Thông tin đơn hàng -->
        <div class="shopee-style">
            <div class="section-title">Thông tin đơn hàng</div>
            <div class="d-flex justify-content-between text-muted mb-2">
                <span>Mã đơn: #<?= $orderId ?></span>
                <span><?= date('d/m/Y', strtotime($order['ngayDat'])) ?></span>
            </div>
            <?php if (!empty($technicianInfo['hoTen'])): ?>
                <div class="text-muted mb-3">
                    Kỹ thuật viên: <strong><?= htmlspecialchars($technicianInfo['hoTen']) ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <!-- Chi tiết dịch vụ - Dạng bảng -->
        <div class="shopee-style">
            <div class="section-title">Chi tiết dịch vụ sửa chữa</div>

            <div class="table-responsive">
                <table class="service-table">
                    <thead>
                        <tr>
                            <th width="5%">STT</th>
                            <th width="45%">Tên thiết bị & Dịch vụ</th>
                            <th width="20%">Mô tả</th>
                            <th width="15%">Loại</th>
                            <th width="15%">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stt = 1;
                        foreach ($devices as $index => $device):
                            $maCTDon = $device['maCTDon'] ?? null;
                            $repairJobs = $maCTDon ? ($deviceRepairJobs[$maCTDon] ?? []) : [];
                            ?>
                            <!-- Header thiết bị -->
                            <tr class="device-header">
                                <td colspan="5">
                                    <i class="fas fa-laptop me-2"></i>
                                    <?= htmlspecialchars($device['tenThietBi'] ?? 'Thiết bị không xác định') ?>
                                    <?php if (!empty($device['phienban'])): ?>
                                        <span class="text-muted">(<?= htmlspecialchars($device['phienban']) ?>)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php if (!empty($device['motaTinhTrang'])): ?>
                                <tr>
                                    <td></td>
                                    <td colspan="4" class="service-description">
                                        <strong>Tình trạng:</strong> <?= htmlspecialchars($device['motaTinhTrang']) ?>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <!-- Chi tiết từng dịch vụ -->
                            <?php if (!empty($repairJobs)): ?>
                                <?php foreach ($repairJobs as $idx => $job):
                                    $loai = $job['loai'] ?? 'Báo giá';
                                    $loaiClass = $loai == 'Phát sinh' ? 'text-danger' : 'text-primary';
                                    ?>
                                    <tr>
                                        <td class="text-center"><?= $stt++ ?></td>
                                        <td>
                                            <div class="fw-medium"><?= htmlspecialchars($job['loiSuaChua'] ?? '') ?></div>
                                        </td>
                                        <td class="service-description">
                                            <?php if (!empty($job['moTa'])): ?>
                                                <?= htmlspecialchars($job['moTa']) ?>
                                            <?php else: ?>
                                                <span class="text-muted">Không có mô tả</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="<?= $loaiClass ?>"><?= $loai ?></span>
                                        </td>
                                        <td class="service-price"><?= number_format($job['chiPhi'] ?? 0) ?>đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Chưa có dịch vụ sửa chữa nào
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <!-- Tổng cho thiết bị -->
                            <?php if (!empty($repairJobs)): ?>
                                <tr class="device-total">
                                    <td colspan="4" class="text-end fw-bold">Tổng thiết bị:</td>
                                    <td class="service-price"><?= number_format($deviceTotals[$maCTDon] ?? 0) ?>đ</td>
                                </tr>
                            <?php endif; ?>

                        <?php endforeach; ?>

                        <!-- Tổng toàn bộ đơn hàng -->
                        <tr class="device-total">
                            <td colspan="4" class="text-end fw-bold fs-6">TỔNG CỘNG:</td>
                            <td class="service-price fs-5 text-orange"><?= number_format($totalCost) ?>đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <!-- Phương thức thanh toán -->
<div class="shopee-style">
    <div class="section-title">Phương thức thanh toán</div>
    
    <div class="payment-method selected" onclick="selectPaymentMethod('vnpay')">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" 
                   id="vnpay" value="vnpay" checked>
            <label class="form-check-label w-100" for="vnpay">
                <div class="d-flex align-items-center">
                    <i class="fas fa-credit-card payment-icon"></i>
                    <div>
                        <div class="fw-bold">Thanh toán qua VNPay</div>
                        <div class="text-muted small">Chuyển khoản an toàn qua cổng VNPay</div>
                    </div>
                </div>
            </label>
        </div>
    </div>
    
    <!-- THANH TOÁN TIỀN MẶT -->
    <div class="payment-method" onclick="selectPaymentMethod('cash')">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment_method" 
                   id="cash" value="cash">
            <label class="form-check-label w-100" for="cash">
                <div class="d-flex align-items-center">
                    <i class="fas fa-money-bill-wave payment-icon"></i>
                    <div>
                        <div class="fw-bold">Thanh toán tiền mặt</div>
                        <div class="text-muted small">Thanh toán khi nhận dịch vụ hoàn tất</div>
                    </div>
                </div>
            </label>
        </div>
    </div>
</div>
    
    

        <!-- Điểm tích lũy -->
        <div class="shopee-style">
            <div class="section-title">Điểm tích lũy</div>

            <div class="points-section">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Điểm hiện có:</span>
                    <span class="fw-bold text-orange"><?= number_format($diemHienCo) ?> điểm</span>
                </div>

                <div class="d-flex align-items-center gap-2 mb-2">
                    <span>Sử dụng điểm:</span>
                    <input type="number" class="points-input" id="diem_su_dung" name="diem_su_dung" min="0"
                        max="<?= $diemToiDaCoTheSuDung ?>" value="0" onchange="updatePayment()">
                    <span>điểm</span>
                </div>

                <div class="text-muted small">
                    Tối đa: <?= $diemToiDaCoTheSuDung ?> điểm (<?= number_format($diemToiDaCoTheSuDung * 1000) ?>đ)
                </div>
            </div>

            <div id="discount-info" class="d-none">
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <span>Giảm giá từ điểm:</span>
                    <span class="discount-badge" id="discount-amount">0đ</span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                <span>Điểm nhận được sau thanh toán:</span>
                <span class="points-badge">+<?= $diemNhanDuoc ?> điểm</span>
            </div>
        </div>

        <!-- Tổng thanh toán -->
        <div class="shopee-style">
            <div class="payment-summary">
                <div class="summary-row">
                    <span>Tổng tiền dịch vụ:</span>
                    <span><?= number_format($totalCost) ?>đ</span>
                </div>

                <div id="discount-row" class="summary-row d-none">
                    <span>Giảm giá điểm:</span>
                    <span class="text-green" id="discount-display">0đ</span>
                </div>

                <div class="summary-row total">
                    <span>Tổng thanh toán:</span>
                    <span id="final-amount"><?= number_format($totalCost) ?>đ</span>
                </div>
            </div>
        </div>

        <!-- Nút thanh toán -->
        <div class="shopee-style">
            <button type="submit" name="thanh_toan" class="btn-payment">
                <i class="fas fa-credit-card me-2"></i>
                THANH TOÁN VNPAY
            </button>
        </div>
    </form>
</main>

<script>
    function updatePayment() {
        const diemSuDung = parseInt(document.getElementById('diem_su_dung').value) || 0;
        const totalCost = <?= $totalCost ?>;
        const discount = diemSuDung * 1000;
        const finalAmount = Math.max(0, totalCost - discount);

        const discountInfo = document.getElementById('discount-info');
        const discountRow = document.getElementById('discount-row');

        if (diemSuDung > 0) {
            // Update discount amounts
            document.getElementById('discount-amount').textContent = '-' + discount.toLocaleString() + 'đ';
            document.getElementById('discount-display').textContent = '-' + discount.toLocaleString() + 'đ';

            // Show discount elements
            discountInfo.classList.remove('d-none');
            discountRow.classList.remove('d-none');

            // Update final amount
            document.getElementById('final-amount').textContent = finalAmount.toLocaleString() + 'đ';
        } else {
            // Hide discount elements
            discountInfo.classList.add('d-none');
            discountRow.classList.add('d-none');

            // Reset final amount
            document.getElementById('final-amount').textContent = totalCost.toLocaleString() + 'đ';
        }
    }

    function selectPaymentMethod(method) {
    // Remove selected class from all payment methods
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selected class to clicked method
    event.currentTarget.classList.add('selected');
    
    // Update radio button
    document.getElementById(method).checked = true;
    
    // Update button text
    const button = document.querySelector('.btn-payment');
    if (method === 'points_only') {
        button.innerHTML = '<i class="fas fa-coins me-2"></i> THANH TOÁN BẰNG ĐIỂM';
    } else if (method === 'cash') {
        button.innerHTML = '<i class="fas fa-money-bill-wave me-2"></i> XÁC NHẬN THANH TOÁN TIỀN MẶT';
    } else {
        button.innerHTML = '<i class="fas fa-credit-card me-2"></i> THANH TOÁN VNPAY';
    }
}

    // Khởi tạo khi trang load
    document.addEventListener('DOMContentLoaded', function () {
        updatePayment();
    });
</script>

<?php include VIEWS_PATH . '/footer.php'; ?>