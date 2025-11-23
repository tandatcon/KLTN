<?php
ob_start();
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Lịch Làm Việc - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/Employee.php';
require_once __DIR__ . '/../../models/WorkSchedule.php';

$orderModel = new Order($db);
$employeeModel = new Employee($db);
$workScheduleModel = new WorkSchedule($db);
$ktvId = $_SESSION['user_id'];

// Kiểm tra role - chỉ cho phép KTV (role 3) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 3) {
    header('Location: ' . url('home'));
    exit();
}

// Lấy thông tin KTV
$ktvInfo = $employeeModel->getEmployeeById($ktvId);

// Lấy tuần hiện tại
$week = $_GET['week'] ?? date('Y-W');
list($year, $weekNum) = explode('-', $week);

// Lấy ngày đầu tuần
$firstDay = new DateTime();
$firstDay->setISODate($year, $weekNum);
$firstDay->setTime(0, 0, 0);

// Tạo mảng các ngày trong tuần
$weekDays = [];
for ($i = 0; $i < 7; $i++) {
    $day = clone $firstDay;
    $day->modify("+$i days");
    $weekDays[] = $day;
}

// Lấy dữ liệu
$workSchedules = $workScheduleModel->getLichbyNV($ktvId);
$weeklySchedule = $orderModel->getKTVWeeklySchedule($ktvId, $firstDay->format('Y-m-d'));
$ngayNghi = $workScheduleModel->getLichNP($ktvId);

// Xử lý xin nghỉ phép - CHỈ CHO PHÉP 1 NGÀY
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_leave'])) {
    $leaveDate = $_POST['leave_date'];
    $leaveReason = $_POST['leave_reason'];

    // Kiểm tra ngày xin nghỉ có phải là ngày làm việc không
    $isWorkingDay = $workScheduleModel->layNgayLV($ktvId, $leaveDate);

    if ($isWorkingDay) {
        // CHỈ XIN NGHỈ 1 NGÀY - start_date và end_date giống nhau
        $result = $workScheduleModel->xinNghi(
            $ktvId,
            $leaveDate, // start_date
            $leaveDate, // end_date (cùng với start_date)
            1,          // total_days = 1
            $leaveReason
        );

        if ($result) {
            $_SESSION['success_message'] = "Đã gửi đơn xin nghỉ phép thành công!";
            header("Location: " . $_SERVER['PHP_SELF'] . "?week=$week");
            exit();
        } else {
            $errorMessage = "Có lỗi xảy ra khi gửi đơn!";
        }
    } else {
        $errorMessage = "Không thể xin nghỉ! Ngày này không có lịch làm việc.";
    }
}

// Nhóm lịch theo ngày
$scheduleByDay = [];
foreach ($weeklySchedule as $schedule) {
    $day = date('Y-m-d', strtotime($schedule['ngayLamViec']));
    $scheduleByDay[$day][] = $schedule;
}
?>

<style>
.rating-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    border: none;
    transition: all 0.3s ease;
    overflow: hidden;
}

.rating-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.rating-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 15px 15px 0 0;
}

.star-rating-display {
    color: #ffc107;
    font-size: 1.1rem;
}

.criteria-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 20px;
    padding: 8px 15px;
    font-size: 0.85rem;
    margin-right: 8px;
    margin-bottom: 8px;
    display: inline-block;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.empty-state {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 20px;
    padding: 80px 40px;
    text-align: center;
    border: 2px dashed #dee2e6;
}

.technician-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.5rem;
    margin: 0 auto 15px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.rating-content {
    padding: 25px;
}

.rating-comment {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 8px;
    margin: 15px 0;
    font-style: italic;
}

.rating-meta {
    border-top: 1px solid #e9ecef;
    padding-top: 15px;
    margin-top: 15px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    margin-bottom: 30px;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.2);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 5px;
}

.stat-label {
    opacity: 0.9;
    font-size: 1rem;
}

.cursor-pointer {
    cursor: pointer;
}

.cursor-pointer:hover {
    transform: translateY(-1px);
    transition: transform 0.2s;
}

.work-schedule-item {
    background: #e3f2fd;
    border-left: 3px solid #3498db;
}

.appointment-slot {
    border-left: 3px solid #ffeaa7;
}
</style>

<main class="bg-light min-vh-100 py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <a href="<?php echo url('don-cua-toi'); ?>" class="btn btn-outline-primary mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại đơn hàng
                    </a>
                    <h1 class="display-5 fw-bold text-primary mb-3">
                        <i class="fas fa-calendar-alt text-warning me-3"></i>Lịch Làm Việc
                    </h1>
                    <p class="lead text-muted">Tuần <?php echo $weekNum; ?>, <?php echo $year; ?></p>
                </div>

                <!-- Thống kê -->
                <?php if (!empty($weeklySchedule)): ?>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number"><?php echo count($weeklySchedule); ?></div>
                            <div class="stat-label">Tổng số lịch hẹn</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">
                                <?php 
                                $completed = array_filter($weeklySchedule, function($schedule) {
                                    return ($schedule['trangThai'] ?? '') == 'completed';
                                });
                                echo count($completed);
                                ?>
                            </div>
                            <div class="stat-label">Đã hoàn thành</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-number">
                                <?php 
                                $pending = array_filter($weeklySchedule, function($schedule) {
                                    return ($schedule['trangThai'] ?? '') == 'assigned';
                                });
                                echo count($pending);
                                ?>
                            </div>
                            <div class="stat-label">Đang chờ</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Thông báo -->
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?php echo $_SESSION['success_message']; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($errorMessage)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?php echo $errorMessage; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Nút điều hướng -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button class="btn btn-success" onclick="openLeaveModal()">
                                <i class="fas fa-umbrella-beach me-1"></i>
                                Xin nghỉ phép
                            </button>
                            <a href="<?php echo url('KTV/orders'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-list me-1"></i>
                                Đơn hàng
                            </a>
                            <a href="<?php echo url('KTV/xemLNP'); ?>" class="btn btn-outline-primary">
                                <i class="fas fa-clipboard-list me-1"></i>
                                Đơn xin nghỉ
                            </a>
                            <div class="btn-group">
                                <a href="?week=<?php echo date('Y-W', strtotime('-1 week', $firstDay->getTimestamp())); ?>"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <span class="btn btn-light fw-bold">Tuần <?php echo $weekNum; ?></span>
                                <a href="?week=<?php echo date('Y-W', strtotime('+1 week', $firstDay->getTimestamp())); ?>"
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                                <a href="?week=<?php echo date('Y-W'); ?>" class="btn btn-primary">
                                    Hôm nay
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lịch tuần -->
                <div class="row g-3">
                    <?php foreach ($weekDays as $day): 
                        $dayFormatted = $day->format('Y-m-d');
                        $isToday = $dayFormatted == date('Y-m-d');
                        $dayOfWeek = $day->format('w'); // 0=CN, 1=T2, ..., 6=T7
                        
                        // Kiểm tra ngày làm việc từ lịch cố định
                        $isWorkingDay = false;
                        $workScheduleForDay = [];
                        
                        foreach ($workSchedules as $schedule) {
                            $ngayLamViec = $schedule['ngayLamViec'] ?? '';
                            $ngayLamViecArray = array_map('trim', explode(',', $ngayLamViec));
                            $ngayLamViecArray = array_filter($ngayLamViecArray);
                            
                            if (in_array($dayOfWeek, $ngayLamViecArray)) {
                                $isWorkingDay = true;
                                $workScheduleForDay[] = $schedule;
                            }
                        }

                        // Kiểm tra nghỉ phép
                        $hasLeave = false;
                        $leaveType = '';
                        $leaveReason = '';
                        $leaveID = '';

                        foreach ($ngayNghi as $leave) {
                            $leaveStart = $leave['ngayBatDau'];
                            $leaveEnd = $leave['ngayKetThuc'];
                            
                            if ($dayFormatted >= $leaveStart && $dayFormatted <= $leaveEnd) {
                                $hasLeave = true;
                                $leaveType = $leave['trangThai'];
                                $leaveReason = $leave['lyDo'];
                                $leaveID = $leave['maLichXN'];
                                break;
                            }
                        }

                        // Xác định màu sắc cho header
                        $headerClass = '';
                        if ($hasLeave) {
                            switch ($leaveType) {
                                case '0': $headerClass = 'bg-warning text-dark'; break;
                                case '1': $headerClass = 'bg-success text-white'; break;
                                case '2': $headerClass = 'bg-danger text-white'; break;
                            }
                        } else {
                            $headerClass = $isWorkingDay ? 'bg-primary text-white' : 'bg-secondary text-white';
                        }
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0">
                            <!-- Header -->
                            <div class="card-header py-3 <?php echo $headerClass; ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="fs-6">
                                            <?php
                                            $dayNames = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
                                            echo $dayNames[$dayOfWeek];
                                            ?>
                                        </strong>
                                        <br>
                                        <small><?php echo $day->format('d/m/Y'); ?></small>
                                    </div>
                                    <div class="text-end">
                                        <?php if ($isToday): ?>
                                            <span class="badge bg-light text-dark">Hôm nay</span>
                                        <?php endif; ?>
                                        <?php if ($hasLeave): ?>
                                            <?php
                                            $leaveIcons = ['0' => 'fa-clock', '1' => 'fa-check', '2' => 'fa-times'];
                                            $leaveTexts = ['0' => 'Chờ duyệt', '1' => 'Đã duyệt', '2' => 'Từ chối'];
                                            ?>
                                            <span class="badge bg-white text-dark mt-1">
                                                <i class="fas <?php echo $leaveIcons[$leaveType]; ?> me-1"></i>
                                                <?php echo $leaveTexts[$leaveType]; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="card-body p-3" style="max-height: 400px; overflow-y: auto;">
                                <!-- Thông báo nghỉ phép -->
                                <?php if ($hasLeave): ?>
                                    <div class="alert 
                                        <?php echo $leaveType == '0' ? 'alert-warning' : 
                                              ($leaveType == '1' ? 'alert-success' : 'alert-danger'); ?> 
                                        py-2 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas 
                                                <?php echo $leaveType == '0' ? 'fa-clock' : 
                                                      ($leaveType == '1' ? 'fa-check' : 'fa-times'); ?> 
                                                me-2"></i>
                                            <div>
                                                <small class="fw-bold"><?php echo $leaveTexts[$leaveType]; ?></small>
                                                <?php if ($leaveReason): ?>
                                                    <br>
                                                    <small><?php echo htmlspecialchars($leaveReason); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Lịch làm việc cố định -->
                                <?php if (!empty($workScheduleForDay) && (!$hasLeave || $leaveType != '1')): ?>
                                    <div class="mb-3">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="fas fa-calendar me-1"></i>Lịch làm việc
                                        </h6>
                                        <?php foreach ($workScheduleForDay as $schedule): ?>
                                            <div class="work-schedule-item p-2 rounded mb-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong class="small"><?php echo htmlspecialchars($schedule['tenLich'] ?? 'Lịch làm việc'); ?></strong>
                                                        <br>
                                                        <small class="text-muted">Làm việc từ 8-12 và 13h30-17h30</small>
                                                    </div>
                                                    <span class="badge bg-primary">Cố định</span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php elseif (!$isWorkingDay && !$hasLeave): ?>
                                    <div class="text-center py-3 text-muted">
                                        <i class="fas fa-moon fa-2x mb-2"></i>
                                        <br>
                                        <small>Ngày nghỉ</small>
                                    </div>
                                <?php endif; ?>

                                <!-- Lịch hẹn -->
                                <?php if (!$hasLeave || $leaveType != '1'): ?>
                                    <?php
                                    $daySchedules = $scheduleByDay[$dayFormatted] ?? [];
                                    if (!empty($daySchedules)): 
                                    ?>
                                        <div class="mt-3">
                                            <h6 class="fw-bold text-success mb-2">
                                                <i class="fas fa-clock me-1"></i>Lịch hẹn
                                                <span class="badge bg-success ms-1"><?php echo count($daySchedules); ?></span>
                                            </h6>
                                            
                                            <?php 
                                            $timeSlots = [
                                                'sang' => ['Sáng', '8:00-12:00'],
                                                'chieu' => ['Chiều', '13:00-17:00'], 
                                                'toi' => ['Tối', '18:00-21:00'],
                                                'trongngay' => ['Cả ngày', '8:00-17:00']
                                            ];
                                            
                                            foreach ($timeSlots as $slotKey => $slotInfo):
                                                $slotSchedules = array_filter($daySchedules, function($s) use ($slotKey) {
                                                    return isset($s['khungGio']) && $s['khungGio'] == $slotKey;
                                                });
                                                
                                                if (!empty($slotSchedules)):
                                            ?>
                                                <div class="appointment-slot mb-3 p-2 border rounded">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <strong class="small"><?php echo $slotInfo[0]; ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?php echo $slotInfo[1]; ?></small>
                                                        </div>
                                                        <span class="badge bg-success rounded-pill"><?php echo count($slotSchedules); ?></span>
                                                    </div>

                                                    <?php foreach ($slotSchedules as $assignment): ?>
                                                        <div class="appointment-item card mb-2 cursor-pointer border-0 shadow-sm"
                                                            onclick="viewOrderDetail(<?php echo $assignment['maDon']; ?>)">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                                    <span class="badge bg-dark">#<?php echo $assignment['maDon']; ?></span>
                                                                    <span class="badge <?php echo $assignment['noiSuaChua'] == 1 ? 'bg-success' : 'bg-info'; ?>">
                                                                        <i class="fas <?php echo $assignment['noiSuaChua'] == 1 ? 'fa-store' : 'fa-home'; ?> me-1"></i>
                                                                        <?php echo $assignment['noiSuaChua'] == 1 ? 'Cửa hàng' : 'Nhà KH'; ?>
                                                                    </span>
                                                                </div>
                                                                <div class="small">
                                                                    <div class="fw-bold text-dark">
                                                                        <?php echo htmlspecialchars($assignment['tenThietBi'] ?? 'N/A'); ?>
                                                                    </div>
                                                                    <div class="text-muted">Loại: <?php echo htmlspecialchars($assignment['loai_thietbi'] ?? ''); ?></div>
                                                                    <div class="mt-1">
                                                                        <i class="fas fa-user text-primary me-1"></i>
                                                                        <?php echo htmlspecialchars($assignment['customer_name'] ?? 'N/A'); ?>
                                                                    </div>
                                                                    <?php if (!empty($assignment['ghiChu'])): ?>
                                                                        <div class="mt-1">
                                                                            <i class="fas fa-sticky-note text-warning me-1"></i>
                                                                            <small><?php echo htmlspecialchars($assignment['ghiChu']); ?></small>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="text-center mt-2">
                                                                    <span class="badge <?php echo ($assignment['trangThai'] ?? '') == 'completed' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                                                        <?php
                                                                        $statusText = [
                                                                            'assigned' => 'Đã phân công',
                                                                            'completed' => 'Hoàn thành', 
                                                                            'cancelled' => 'Đã hủy'
                                                                        ];
                                                                        echo $statusText[$assignment['trangThai']] ?? 'Đã phân công';
                                                                        ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endif; endforeach; ?>
                                        </div>
                                    <?php elseif ($isWorkingDay && !empty($workScheduleForDay) && !$hasLeave): ?>
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-coffee fa-2x mb-2"></i>
                                            <br>
                                            <small>Không có lịch hẹn</small>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal xin nghỉ phép - CHỈ CHO PHÉP 1 NGÀY -->
<div class="modal fade" id="leaveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-umbrella-beach me-2"></i>
                    Xin Nghỉ Phép (1 Ngày)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <!-- CHỈ CÓ 1 TRƯỜNG NGÀY NGHỈ -->
                    <div class="mb-3">
                        <label for="leave_date" class="form-label">Ngày muốn nghỉ:</label>
                        <input type="date" class="form-control" id="leave_date" name="leave_date" required
                            min="<?php echo date('Y-m-d'); ?>">
                        <div class="form-text">Chỉ có thể xin nghỉ 1 ngày duy nhất</div>
                    </div>

                    <div class="alert alert-warning py-2">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Lưu ý:</strong> Chỉ có thể xin nghỉ những ngày có lịch làm việc.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="leave_reason" class="form-label">Lý do nghỉ phép:</label>
                        <textarea class="form-control" id="leave_reason" name="leave_reason" rows="4"
                            placeholder="Nhập lý do xin nghỉ phép..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="request_leave" class="btn btn-primary">Gửi Đơn Xin Nghỉ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function viewOrderDetail(orderId) {
    window.location.href = '<?php echo url("KTV/xemChiTietDon"); ?>?id=' + orderId;
}

function openLeaveModal() {
    var modal = new bootstrap.Modal(document.getElementById('leaveModal'));
    modal.show();
}
</script>

<?php include __DIR__ . '/../footer.php'; ?>