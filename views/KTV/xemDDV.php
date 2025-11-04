<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Đơn Được Phân Công - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Order.php';

$orderModel = new Order($db);

// Kiểm tra role - chỉ cho phép KTV (role 3) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header('Location: ' . url('home'));
    exit();
}

$ktvId = $_SESSION['user_id'];

// Xử lý bộ lọc
$statusFilter = $_GET['status'] ?? 'all';

// Lấy đơn hôm nay
$todayOrders = $orderModel->getDonHomNayByKTV($ktvId);

// Lấy tất cả đơn được phân công
$allOrders = $orderModel->getDonPhanCongByKTV($ktvId);

// Lọc theo trạng thái nếu có
if ($statusFilter !== 'all') {
    $allOrders = array_filter($allOrders, function($order) use ($statusFilter) {
        return $order['trangThai'] == $statusFilter;
    });
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
                            <i class="fas fa-tasks text-primary me-2"></i>
                            Đơn Được Phân Công
                        </h1>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end">
                            <a href="<?php echo url('employee/schedule'); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ĐƠN HÔM NAY -->
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0">
                    <i class="fas fa-star me-2"></i>
                    Đơn Hôm Nay 
                    <?php if (!empty($todayOrders)): ?>
                        (<?php echo count($todayOrders); ?> đơn)
                    <?php endif; ?>
                </h6>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($todayOrders)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-warning">
                                <tr>
                                    <th width="80">Mã đơn</th>
                                    <th width="120">Khách hàng</th>
                                    <th>Thiết bị</th>
                                    <th>Địa điểm</th>
                                    <th width="120">Loại</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($todayOrders as $order): ?>
                                <tr>
                                    <td>
                                        <strong>#<?php echo $order['maDon']; ?></strong>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($order['tenThietBi'] ?? 'N/A'); ?></div>
                                        <small class="text-muted"><?php echo htmlspecialchars($order['loai_thietbi'] ?? ''); ?></small>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($order['diemhen'] ?? 'N/A'); ?>
                                    </td>
                                    <td>
                                        <?php if ($order['noiSuaChua'] == 1): ?>
                                            <span class="badge bg-success">Cửa hàng</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Nhà KH</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo url('KTV/thuchienDDV?id=' . $order['maDon']); ?>" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-play me-1"></i>Thực hiện
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-calendar-day fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">Hôm nay không có đơn nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- BỘ LỌC -->
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label"><strong>Lọc theo trạng thái:</strong></label>
                        <select class="form-select" onchange="window.location.href='?status='+this.value">
                            <option value="all" <?php echo $statusFilter == 'all' ? 'selected' : ''; ?>>Tất cả đơn</option>
                            <option value="2" <?php echo $statusFilter == '2' ? 'selected' : ''; ?>>Đang thực hiện</option>
                            <option value="3" <?php echo $statusFilter == '3' ? 'selected' : ''; ?>>Đã hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <div class="text-muted small">
                            Hiển thị <strong><?php echo count($allOrders); ?></strong> đơn được phân công
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH TẤT CẢ ĐƠN -->
        <div class="card">
            <div class="card-body p-0">
                <?php if (empty($allOrders)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không có đơn được phân công</h5>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">Mã đơn</th>
                                    <th width="120">Ngày đặt</th>
                                    <th width="120">Khách hàng</th>
                                    <th>Thiết bị</th>
                                    <th width="100">Địa chỉ</th>
                                    <th width="100">Trạng thái</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($allOrders as $order): 
                                    $statusClass = [
                                        '1' => 'primary',
                                        '2' => 'warning', 
                                        '3' => 'success'
                                    ][$order['trangThai']];
                                    
                                    $statusText = [
                                        '1' => 'Đã tiếp nhận',
                                        '2' => 'Đang thực hiện',
                                        '3' => 'Hoàn thành'
                                    ][$order['trangThai']];
                                ?>
                                    <tr>
                                        <td>
                                            <strong>#<?php echo $order['maDon']; ?></strong>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($order['ngayDat'])); ?>
                                        </td>
                                        <td>
                                            <?php echo htmlspecialchars($order['customer_name'] ?? 'N/A'); ?>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($order['tenThietBi'] ?? 'N/A'); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($order['loai_thietbi'] ?? ''); ?></small>
                                        </td>
                                        <td>
                                            <?php if ($order['noiSuaChua'] == 1): ?>
                                                <span class="badge bg-success">Cửa hàng</span>
                                            <?php else: ?>
                                                <span class="badge bg-info">Nhà KH</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo url('KTV/xemChiTietDon?id=' . $order['maDon']); ?>" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}
.table td {
    vertical-align: middle;
}
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>