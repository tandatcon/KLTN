<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Lịch Nghỉ Phép - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/WorkSchedule.php';

$leaveRequestModel = new WorkSchedule($db);

// Kiểm tra role - chỉ cho phép KTV (role 3) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header('Location: ' . url('home'));
    exit();
}

$ktvId = $_SESSION['user_id'];

// Xử lý hủy đơn
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_leave'])) {
    $leaveId = $_POST['leave_id'];
    $result = $leaveRequestModel->huyDonNghi($leaveId);
    
    if ($result) {
        $successMessage = "Đã hủy đơn xin nghỉ phép thành công!";
        // Reload để cập nhật danh sách
        header('Location: ' . url('KTV/xemLNP?status=3'));
    } else {
        $errorMessage = "Có lỗi xảy ra khi hủy đơn!";
    }
}

// Xử lý bộ lọc
$statusFilter = $_GET['status'] ?? 'all';

// Lấy danh sách đơn xin nghỉ phép
$leaveRequests = $leaveRequestModel->getLNPbyNV($ktvId);

// Lọc theo trạng thái nếu có
if ($statusFilter !== 'all') {
    $leaveRequests = array_filter($leaveRequests, function($request) use ($statusFilter) {
        return $request['trangThai'] == $statusFilter;
    });
}

// Sắp xếp theo ngày tạo mới nhất
usort($leaveRequests, function($a, $b) {
    return strtotime($b['ngayTao']) - strtotime($a['ngayTao']);
});


?>

<section class="py-3">
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-umbrella-beach text-primary me-2"></i>
                            Lịch Nghỉ Phép
                        </h1>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-md-end gap-2">
                            <a href="<?php echo url('employee/schedule'); ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>
                                Quay lại
                            </a>
                            <button class="btn btn-primary btn-sm" onclick="redirectToSchedule()">
                                <i class="fas fa-plus me-1"></i>
                                Xin Nghỉ Phép
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- THÔNG BÁO -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $successMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?php echo $errorMessage; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- BỘ LỌC -->
        <div class="card mb-3">
            <div class="card-body py-2">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label"><strong>Lọc theo trạng thái:</strong></label>
                        <select class="form-select" onchange="window.location.href='?status='+this.value">
                            <option value="all" <?php echo $statusFilter == 'all' ? 'selected' : ''; ?>>Tất cả trạng thái</option>
                            <option value="0" <?php echo $statusFilter == '0' ? 'selected' : ''; ?>>Chờ duyệt</option>
                            <option value="1" <?php echo $statusFilter == '1' ? 'selected' : ''; ?>>Đã duyệt</option>
                            <option value="2" <?php echo $statusFilter == '2' ? 'selected' : ''; ?>>Từ chối</option>
                            <option value="3" <?php echo $statusFilter == '3' ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-9">
                        <div class="text-muted small">
                            Hiển thị <strong><?php echo count($leaveRequests); ?></strong> đơn xin nghỉ phép
                            <?php 
                            $allLeaves = $leaveRequestModel->getLNPbyNV($ktvId);
                            $counts = [
                                'all' => count($allLeaves),
                                '0' => count(array_filter($allLeaves, function($r) { return $r['trangThai'] == '0'; })),
                                '1' => count(array_filter($allLeaves, function($r) { return $r['trangThai'] == '1'; })),
                                '2' => count(array_filter($allLeaves, function($r) { return $r['trangThai'] == '2'; })),
                                '3' => count(array_filter($allLeaves, function($r) { return $r['trangThai'] == '3'; }))
                            ];
                            ?>
                            <span class="ms-3">
                                <span class="badge bg-warning"><?php echo $counts['0']; ?> chờ duyệt</span>
                                <span class="badge bg-success"><?php echo $counts['1']; ?> đã duyệt</span>
                                <span class="badge bg-danger"><?php echo $counts['2']; ?> từ chối</span>
                                <span class="badge bg-secondary"><?php echo $counts['3']; ?> đã hủy</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH ĐƠN NGHỈ PHÉP -->
        <div class="card">
            <div class="card-body p-0">
                <?php if (empty($leaveRequests)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Không có đơn xin nghỉ phép nào</h5>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="100">Trạng thái</th>
                                    <th width="120">Ngày nghỉ</th>
                                    <th width="80">Số ngày</th>
                                    <th>Lý do</th>
                                    <th width="120">Ngày gửi</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($leaveRequests as $leave): 
                                    $statusClass = [
                                        '0' => 'warning',
                                        '1' => 'success', 
                                        '2' => 'danger',
                                        '3' => 'secondary'
                                    ][$leave['trangThai']];
                                    
                                    $statusText = [
                                        '0' => 'Chờ Duyệt',
                                        '1' => 'Đã Duyệt',
                                        '2' => 'Từ Chối',
                                        '3' => 'Đã Hủy'
                                    ][$leave['trangThai']];
                                    
                                    $statusIcon = [
                                        '0' => 'clock',
                                        '1' => 'check-circle',
                                        '2' => 'times-circle',
                                        '3' => 'ban'
                                    ][$leave['trangThai']];
                                ?>
                                    <tr>
                                        <td>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <i class="fas fa-<?php echo $statusIcon; ?> me-1"></i>
                                                <?php echo $statusText; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($leave['ngayBatDau'])); ?><br>
                                            <small class="text-muted">đến <?php echo date('d/m/Y', strtotime($leave['ngayKetThuc'])); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo $leave['soNgayXN']; ?> ngày</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="<?php echo htmlspecialchars($leave['lyDo']); ?>">
                                                <?php echo htmlspecialchars($leave['lyDo']); ?>
                                            </div>
                                            <?php if ($leave['trangThai'] == '2' && !empty($leave['ghiChu'])): ?>
                                                <small class="text-danger">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    <?php echo htmlspecialchars($leave['ghiChu']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php echo date('d/m/Y', strtotime($leave['ngayTao'])); ?><br>
                                            <small class="text-muted"><?php echo date('H:i', strtotime($leave['ngayTao'])); ?></small>
                                        </td>
                                        <td>
                                            <?php if ($leave['trangThai'] == '0'): ?>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="leave_id" value="<?php echo $leave['maLichXN']; ?>">
                                                    <button type="submit" name="cancel_leave" class="btn btn-outline-danger btn-sm" 
                                                            onclick="return confirm('Bạn có chắc muốn hủy đơn xin nghỉ phép này?')"
                                                            title="Hủy đơn">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
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

<script>
function redirectToSchedule() {
    alert('Vui lòng thực hiện chức năng xin nghỉ phép tại trang Lịch làm việc');
    window.location.href = '<?php echo url("KTV/xemLPC"); ?>';
}
</script>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
}
.table td {
    vertical-align: middle;
}
.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>