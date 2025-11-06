<?php
// File: employee/xemChiTietDon.php - Trang xem chi tiết đơn cho KTV (đã cập nhật)

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Chi Tiết Đơn Hàng - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../controllers/OrderController.php';
require_once __DIR__ . '/../../models/ServiceProcess.php';

$orderController = new OrderController($db);
$serviceProcessModel = new ServiceProcess($db);

// Kiểm tra role - chỉ cho phép KTV (role 3) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header('Location: ' . url('home'));
    exit();
}

$ktvId = $_SESSION['user_id'];

// Lấy mã đơn từ URL
$orderId = $_GET['id'] ?? 0;

// Lấy dữ liệu đơn hàng
$data = $orderController->layChiTietDonChoKTV($orderId, $ktvId);

// Kiểm tra nếu không có dữ liệu
if (!$data) {
    echo "<div class='alert alert-danger'>Đơn hàng không tồn tại hoặc bạn không có quyền truy cập!</div>";
    include __DIR__ . '/../footer.php';
    exit();
}

// Extract data
$donHang = $data['donHang'];
$chiTietDonDichVu = $data['chiTietDonDichVu'];
$thongTinKhachHang = $data['thongTinKhachHang'];

// Lấy danh sách công việc sửa chữa cho từng thiết bị
$deviceRepairJobs = [];
$totalOrderCost = 0;

if (!empty($chiTietDonDichVu)) {
    foreach ($chiTietDonDichVu as $ctdd) {
        $maCTDon = $ctdd['maCTDon'] ?? null;
        if ($maCTDon) {
            $deviceRepairJobs[$maCTDon] = $serviceProcessModel->getDeviceRepairDetails($orderId, $maCTDon);

            // Tính tổng chi phí đơn hàng
            foreach ($deviceRepairJobs[$maCTDon] as $job) {
                $totalOrderCost += $job['chiPhi'] ?? 0;
            }
        }
    }
}

// Xác định trạng thái đơn hàng
$thongTinTrangThai = [
    'class' => '',
    'icon' => '',
    'text' => ''
];

switch ((int) $donHang['trangThai']) {
    case 0:
        $thongTinTrangThai = [
            'class' => 'danger',
            'icon' => 'fas fa-times-circle',
            'text' => 'Đã hủy'
        ];
        break;
    case 1:
        $thongTinTrangThai = [
            'class' => 'primary',
            'icon' => 'fas fa-clock',
            'text' => 'Đã tiếp nhận'
        ];
        break;
    case 2:
        $thongTinTrangThai = [
            'class' => 'warning',
            'icon' => 'fas fa-tasks',
            'text' => 'Đang thực hiện'
        ];
        break;
    case 3:
        $thongTinTrangThai = [
            'class' => 'success',
            'icon' => 'fas fa-check-circle',
            'text' => 'Hoàn thành'
        ];
        break;
    default:
        $thongTinTrangThai = [
            'class' => 'secondary',
            'icon' => 'fas fa-question-circle',
            'text' => 'Không xác định'
        ];
}
?>

<section class="py-3">
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-file-invoice text-primary me-2"></i>
                            Chi Tiết Đơn Hàng
                        </h1>
                        <p class="text-muted mb-0">Mã đơn: #<?php echo $orderId; ?></p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-2">
                            <a href="<?php echo url('employee/don-phan-cong'); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại
                            </a>
                            <?php if ($donHang['trangThai'] == '1' && date('Y-m-d') == date('Y-m-d', strtotime($donHang['ngayDat']))): ?>
                                <a href="<?php echo url('employee/thuchienDDV?id=' . $orderId); ?>"
                                    class="btn btn-primary btn-sm">
                                    <i class="fas fa-play me-1"></i>
                                    Thực hiện đơn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- THÔNG TIN CHÍNH -->
            <div class="col-lg-8">
                <!-- THÔNG TIN ĐƠN HÀNG -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <strong>Mã đơn:</strong><br>
                                #<?php echo $orderId; ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Ngày hẹn:</strong><br>
                                <?php echo date('d/m/Y', strtotime($donHang['ngayDat'])); ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Khung giờ:</strong><br>
                                <?php
                                $khungGio = [
                                    'sang' => 'Sáng (8:00 - 11:00)',
                                    'chieu' => 'Chiều (13:00 - 17:00)',
                                    'toi' => 'Tối (18:00 - 21:00)'
                                ];
                                echo $khungGio[$donHang['gioDat']] ?? $donHang['gioDat'];
                                ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Trạng thái:</strong><br>
                                <span class="badge bg-<?php echo $thongTinTrangThai['class']; ?>">
                                    <i class="<?php echo $thongTinTrangThai['icon']; ?> me-1"></i>
                                    <?php echo $thongTinTrangThai['text']; ?>
                                </span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Nơi sửa chữa:</strong><br>
                                <?php echo $donHang['noiSuaChua'] == 0 ? "🏠 Tại nhà" : "🏪 Tại cửa hàng"; ?>
                            </div>
                            <div class="col-md-6 mb-2">
                                <strong>Số lượng dịch vụ:</strong><br>
                                <?php echo count($chiTietDonDichVu); ?> dịch vụ
                            </div>
                            <div class="col-12 mb-2">
                                <strong>Địa điểm hẹn:</strong><br>
                                <?php echo htmlspecialchars($donHang['diemhen']); ?>
                            </div>
                            <?php if (!empty($donHang['ghiChu'])): ?>
                                <div class="col-12">
                                    <strong>Ghi chú khách hàng:</strong><br>
                                    <?php echo nl2br(htmlspecialchars($donHang['ghiChu'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- DANH SÁCH CHI TIẾT ĐƠN DỊCH VỤ -->
                <?php if (!empty($chiTietDonDichVu)): ?>
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-tools me-2"></i>Chi tiết dịch vụ & Sửa chữa</h6>
                        </div>
                        <div class="card-body">
                            <?php foreach ($chiTietDonDichVu as $index => $ctdd):
                                $maCTDon = $ctdd['maCTDon'] ?? null;
                                $repairJobs = $maCTDon ? ($deviceRepairJobs[$maCTDon] ?? []) : [];
                                $totalDeviceCost = 0;

                                foreach ($repairJobs as $job) {
                                    $totalDeviceCost += $job['chiPhi'] ?? 0;
                                }
                                ?>
                                <div class="card mb-4 border">
                                    <div class="card-header bg-white">
                                        <h6 class="mb-0">
                                            <i class="fas fa-cogs me-2"></i>
                                            Dịch vụ <?php echo $index + 1; ?>:
                                            <?php echo htmlspecialchars($ctdd['tenThietBi'] ?? 'Thiết bị không xác định'); ?>
                                            <?php if ($totalDeviceCost > 0): ?>
                                                <span class="badge bg-success float-end">
                                                    <?php echo number_format($totalDeviceCost); ?>đ
                                                </span>
                                            <?php endif; ?>
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- THÔNG TIN CHI TIẾT DỊCH VỤ -->
                                        <div class="border-bottom pb-3 mb-3">
                                            <h6 class="text-primary mb-3">
                                                <i class="fas fa-info-circle me-2"></i>
                                                Thông tin dịch vụ
                                            </h6>

                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <strong>Mã CTĐơn:</strong><br>
                                                    <?php echo $ctdd['maCTDon']; ?>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Mã thiết bị:</strong><br>
                                                    <?php echo $ctdd['maThietBi']; ?>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>Thiết bị:</strong><br>
                                                    <?php echo htmlspecialchars($ctdd['tenThietBi'] ?? 'N/A'); ?>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <strong>KTV phụ trách:</strong><br>
                                                    <?php echo htmlspecialchars($ctdd['tenKTV'] ?? 'Chưa phân công'); ?>
                                                </div>
                                                <?php if (!empty($ctdd['mota_tinhtrang'])): ?>
                                                    <div class="col-12 mb-2">
                                                        <strong>Mô tả tình trạng:</strong><br>
                                                        <?php echo nl2br(htmlspecialchars($ctdd['mota_tinhtrang'])); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- THÔNG TIN SỬA CHỮA -->
                                        <div>
                                            <h6 class="text-primary mb-3">
                                                <i class="fas fa-wrench me-2"></i>
                                                Thông tin sửa chữa
                                            </h6>

                                            <?php if (!empty($repairJobs) || !empty($ctdd['tinh_trang_thuc_te'])): ?>
                                                <!-- Có thông tin sửa chữa -->

                                                <!-- Tình trạng thực tế -->
                                                <?php if (!empty($ctdd['tinh_trang_thuc_te'])): ?>
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <strong><i class="fas fa-search me-1"></i> Tình trạng thực tế:</strong><br>
                                                            <?php echo nl2br(htmlspecialchars($ctdd['tinh_trang_thuc_te'])); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- DANH SÁCH CÔNG VIỆC SỬA CHỮA -->
                                                <?php if (!empty($repairJobs)): ?>
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <div class="card border-success">
                                                                <div class="card-header bg-success text-white py-2">
                                                                    <h6 class="mb-0">
                                                                        <i class="fas fa-list-check me-2"></i>Danh Sách Công Việc Sửa
                                                                        Chữa
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
                                                                                        <td><?php echo htmlspecialchars($chiTiet['loiSuaChua']); ?>
                                                                                        </td>
                                                                                        <td class="text-end">
                                                                                            <?php echo number_format($chiTiet['chiPhi']); ?>
                                                                                        </td>
                                                                                        <td class="text-center">
                                                                                            <span
                                                                                                class="badge bg-<?php echo $chiTiet['loai'] == 'chuan' ? 'primary' : 'warning'; ?>">
                                                                                                <?php echo $chiTiet['loai']; ?>
                                                                                            </span>
                                                                                        </td>
                                                                                    </tr>
                                                                                <?php endforeach; ?>
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr class="table-secondary">
                                                                                    <td colspan="2" class="text-end fw-bold">Tổng cộng:
                                                                                    </td>
                                                                                    <td class="text-end fw-bold">
                                                                                        <?php echo number_format($tongThietBi); ?></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Thiếu linh kiện -->
                                                <?php if (!empty($ctdd['thieu_linh_kien'])): ?>
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <strong><i class="fas fa-exclamation-triangle me-1"></i> Thiếu linh
                                                                kiện:</strong><br>
                                                            <?php echo nl2br(htmlspecialchars($ctdd['thieu_linh_kien'])); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Ghi chú kỹ thuật -->
                                                <?php if (!empty($ctdd['ghi_chu_ky_thuat'])): ?>
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <strong><i class="fas fa-sticky-note me-1"></i> Ghi chú kỹ
                                                                thuật:</strong><br>
                                                            <?php echo nl2br(htmlspecialchars($ctdd['ghi_chu_ky_thuat'])); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <!-- Ngày bắt đầu và kết thúc -->
                                                <div class="row">
                                                    <?php if (!empty($ctdd['ngay_bat_dau'])): ?>
                                                        <div class="col-md-6 mb-2">
                                                            <strong><i class="fas fa-calendar-plus me-1"></i> Ngày bắt đầu:</strong><br>
                                                            <?php echo date('d/m/Y', strtotime($ctdd['ngay_bat_dau'])); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($ctdd['ngay_ket_thuc'])): ?>
                                                        <div class="col-md-6 mb-2">
                                                            <strong><i class="fas fa-calendar-check me-1"></i> Ngày kết
                                                                thúc:</strong><br>
                                                            <?php echo date('d/m/Y', strtotime($ctdd['ngay_ket_thuc'])); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                            <?php else: ?>
                                                <!-- Chưa có thông tin sửa chữa -->
                                                <div class="text-center py-3">
                                                    <i class="fas fa-info-circle text-muted fa-2x mb-2"></i>
                                                    <p class="text-muted mb-0">Chưa có thông tin sửa chữa</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-body text-center py-4">
                            <i class="fas fa-tools fa-2x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có dịch vụ nào</h5>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- SIDEBAR -->
            <div class="col-lg-4">
                <!-- THÔNG TIN KHÁCH HÀNG -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Họ tên:</strong><br>
                            <?php echo htmlspecialchars($thongTinKhachHang['hoTen']); ?>
                        </div>
                        <div class="mb-3">
                            <strong>Số điện thoại:</strong><br>
                            <?php echo htmlspecialchars($thongTinKhachHang['sdt']); ?>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong><br>
                            <?php echo htmlspecialchars($thongTinKhachHang['email']); ?>
                        </div>
                    </div>
                </div>

                <!-- TỔNG CHI PHÍ -->
                <?php if ($totalOrderCost > 0): ?>
                    <div class="card mb-3">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Tổng chi phí đơn hàng</h6>
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-success fw-bold"><?php echo number_format($totalOrderCost); ?>đ</h3>
                            <p class="text-muted mb-0">Tổng chi phí sửa chữa</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- THAO TÁC -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo url('employee/don-phan-cong'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại
                            </a>
                            <?php if ($donHang['trangThai'] == '1' && date('Y-m-d') == date('Y-m-d', strtotime($donHang['ngayDat']))): ?>
                                <a href="<?php echo url('employee/thuchienDDV?id=' . $orderId); ?>" class="btn btn-primary">
                                    <i class="fas fa-play me-1"></i>
                                    Thực hiện đơn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>