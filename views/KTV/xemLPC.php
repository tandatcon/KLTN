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

// DEBUG


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


// Lấy lịch làm việc cá nhân của KTV
$workSchedules = $workScheduleModel->getLichbyNV($ktvId);



// Lấy lịch phân công trong tuần
$weeklySchedule = $orderModel->getKTVWeeklySchedule($ktvId, $firstDay->format('Y-m-d'));

// Lấy lịch làm việc cá nhân của KTV
$ngayNghi = $workScheduleModel->getLichNP($ktvId);

// Lấy lịch làm việc cá nhân của KTV
$workSchedules = $workScheduleModel->getLichbyNV($ktvId);

// Xử lý xin nghỉ phép
// Trong phần xử lý xin nghỉ phép, thay đoạn code hiện tại bằng:
// Xử lý xin nghỉ phép - CHỈ CHO PHÉP NẾU TẤT CẢ NGÀY ĐỀU CÓ LỊCH LÀM
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_leave'])) {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $leaveReason = $_POST['leave_reason'];

    // KIỂM TRA TẤT CẢ CÁC NGÀY ĐỀU CÓ LỊCH LÀM VIỆC
    $currentDate = $startDate;
    $allDaysAreWorking = true;

    while (strtotime($currentDate) <= strtotime($endDate)) {
        if (!$workScheduleModel->layNgayLV($ktvId, $currentDate)) {
            $allDaysAreWorking = false;
            break; // Dừng ngay khi gặp 1 ngày không có lịch làm
        }
        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
    }

    // CHỈ CHO PHÉP XIN NGHỈ NẾU TẤT CẢ NGÀY ĐỀU CÓ LỊCH LÀM
    if ($allDaysAreWorking) {
        $totalDays = floor((strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24)) + 1;

        $result = $workScheduleModel->xinNghi(
            $ktvId,
            $startDate,
            $endDate,
            $totalDays,
            $leaveReason
        );

        if ($result) {
            $_SESSION['success_message'] = "Đã gửi đơn xin nghỉ phép thành công!";
            header("Location: " . url('KTV/xemLPC'));
        } else {
            $errorMessage = "Có lỗi xảy ra khi gửi đơn!";
        }
    } else {
        $errorMessage = "Không thể xin nghỉ! Có ngày không có lịch làm việc trong khoảng thời gian này.";
    }
}

// Nhóm lịch theo ngày
$scheduleByDay = [];
foreach ($weeklySchedule as $schedule) {
    $day = date('Y-m-d', strtotime($schedule['ngayLamViec']));
    $scheduleByDay[$day][] = $schedule;
}
?>

<section class="py-3">
    <div class="container-fluid">
        <!-- HEADER -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-1">
                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                            Lịch Làm Việc Tuần
                        </h1>
                        <p class="text-muted mb-0">
                            <?php echo htmlspecialchars($ktvInfo['name'] ?? 'Kỹ thuật viên'); ?> - Tuần
                            <?php echo $weekNum; ?>, <?php echo $year; ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
                            <div class="d-flex gap-2 mb-2 mb-md-0">
                                <button class="btn btn-success btn-sm" onclick="openLeaveModal()">
                                    <i class="fas fa-umbrella-beach me-1"></i>
                                    Xin nghỉ phép
                                </button>
                                <a href="<?php echo url('KTV/orders'); ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-list me-1"></i>
                                    Đơn hàng
                                </a>
                                <a href="<?php echo url('KTV/xemLNP'); ?>" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-list me-1"></i>
                                    Xem danh sách xin nghỉ
                                </a>
                            </div>
                            <div class="d-flex align-items-center gap-2 bg-light rounded px-2 py-1">
                                <a href="?week=<?php echo date('Y-W', strtotime('-1 week', $firstDay->getTimestamp())); ?>"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                                <span class="fw-bold small">Tuần <?php echo $weekNum; ?></span>
                                <a href="?week=<?php echo date('Y-W', strtotime('+1 week', $firstDay->getTimestamp())); ?>"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                                <a href="?week=<?php echo date('Y-W'); ?>" class="btn btn-primary btn-sm">
                                    Hôm nay
                                </a>
                            </div>
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

        <!-- LỊCH CÁ NHÂN -->



        <!-- LỊCH TUẦN - TÍCH HỢP CẢ LỊCH LÀM VÀ LỊCH HẸN -->
        <!-- LỊCH TUẦN - TÍCH HỢP CẢ LỊCH LÀM VÀ LỊCH HẸN -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-2">
                    <?php foreach ($weekDays as $day):
                        $dayFormatted = $day->format('Y-m-d');
                        $isToday = $dayFormatted == date('Y-m-d');
                        $dayOfWeek = $day->format('w');
                        $isWorkingDay = $workScheduleModel->layNgayLV($ktvId, $dayFormatted);

                        // Kiểm tra xem ngày này có đơn xin nghỉ phép không
                        $hasLeave = false;
                        $leaveStatus = '';
                        $leaveReason = '';
                        $leaveType = '';

                        foreach ($ngayNghi as $leave) {
                            $leaveID = $leave['maLichXN'];
                            $leaveStart = $leave['ngayBatDau'];
                            $leaveEnd = $leave['ngayKetThuc'];
                            $leaveStatus = $leave['trangThai'];

                            // Kiểm tra nếu ngày hiện tại nằm trong khoảng nghỉ phép
                            if ($dayFormatted >= $leaveStart && $dayFormatted <= $leaveEnd) {
                                $hasLeave = true;
                                $leaveReason = $leave['lyDo'];
                                $leaveType = $leaveStatus; // 0: chờ duyệt, 1: đã duyệt, 2: từ chối
                                break;
                            }
                        }

                        // Xác định class và màu sắc dựa trên trạng thái nghỉ phép
                        $leaveClass = '';
                        $leaveBadgeClass = '';
                        $leaveIcon = '';
                        $leaveText = '';

                        if ($hasLeave) {
                            switch ($leaveType) {
                                case '0': // Chờ duyệt
                                    $leaveClass = 'border-warning';
                                    $leaveBadgeClass = 'bg-warning text-dark';
                                    $leaveIcon = 'fa-clock';
                                    $leaveText = 'Đã xin nghỉ';
                                    break;
                                case '1': // Đã duyệt
                                    $leaveClass = 'border-success';
                                    $leaveBadgeClass = 'bg-success text-white';
                                    $leaveIcon = 'fa-check-circle';
                                    $leaveText = 'Đã duyệt nghỉ';
                                    break;
                                case '2': // Từ chối
                                    $leaveClass = 'border-danger';
                                    $leaveBadgeClass = 'bg-danger text-white';
                                    $leaveIcon = 'fa-times-circle';
                                    $leaveText = 'Từ chối nghỉ';
                                    break;
                            }
                        }

                        // Lấy thông tin lịch làm việc cố định cho ngày này
                        $workScheduleForDay = [];
                        foreach ($workSchedules as $schedule) {
                            $ngayLamViec = json_decode($schedule['ngayLamViec'] ?? '[]', true);
                            if (in_array($dayOfWeek, $ngayLamViec)) {
                                $workScheduleForDay[] = $schedule;
                            }
                        }
                        ?>
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                            <div
                                class="card h-100 <?php echo $isToday ? 'border-primary' : ''; ?> <?php echo $hasLeave ? $leaveClass : ''; ?>">
                                <div
                                    class="card-header py-2 <?php echo $hasLeave ? $leaveBadgeClass : ($isWorkingDay ? 'bg-primary text-white' : ''); ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold"><?php
                                        $dayNames = ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'];
                                        echo $dayNames[$dayOfWeek];
                                        ?></span>
                                        <span class="fw-bold"><?php echo $day->format('d/m'); ?></span>
                                    </div>
                                    <div class="d-flex gap-1 mt-1">
                                        <?php if ($isToday): ?>
                                            <span class="badge bg-light text-dark">Hôm nay</span>
                                        <?php endif; ?>
                                        <?php if (!$isWorkingDay): ?>
                                            <span class="badge bg-warning text-dark">Nghỉ</span>
                                        <?php endif; ?>
                                        <?php if ($hasLeave): ?>
                                            <span class="badge <?php echo $leaveBadgeClass; ?>">
                                                <i class="fas <?php echo $leaveIcon; ?> me-1"></i><?php echo $leaveText; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="card-body p-2" style="max-height: 300px; overflow-y: auto;">

                                    <!-- HIỂN THỊ THÔNG BÁO NGHỈ PHÉP -->
                                    <?php if ($hasLeave): ?>
                                        <div class="alert <?php
                                        echo $leaveType == '0' ? 'alert-warning' :
                                            ($leaveType == '1' ? 'alert-success' : 'alert-danger');
                                        ?> py-2 mb-2" role="alert">
                                            <div class="d-flex align-items-center">
                                                <i class="fas <?php echo $leaveIcon; ?> me-2"></i>
                                                <div>
                                                    <small class="fw-bold"><?php echo $leaveText; ?></small>
                                                    <?php if ($leaveReason): ?>
                                                        <br>
                                                        <small class="text-muted"><a href="xemLNP?idNP=<?= $leaveID ?>">Nhấn xem chi
                                                                tiết ></a></small>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- HIỂN THỊ LỊCH LÀM CỐ ĐỊNH - CHỈ HIỆN KHI KHÔNG CÓ NGHỈ PHÉP ĐÃ DUYỆT -->
                                    <?php if (!empty($workScheduleForDay) && $isWorkingDay && (!$hasLeave || $leaveType != '1')): ?>
                                        <div class="mb-2">
                                            <?php foreach ($workScheduleForDay as $schedule): ?>
                                                <div class="work-schedule-item p-2 border rounded mb-2"
                                                    style="background: #e3f2fd; border-left: 3px solid #3498db;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <strong class="small text-primary">
                                                                <i class="fas fa-user-clock me-1"></i>
                                                                Lịch làm
                                                            </strong>
                                                            <br>

                                                        </div>
                                                        <span class="badge bg-primary">Cố định</span>
                                                    </div>
                                                    <?php if (!empty($schedule['moTa'])): ?>
                                                        <div class="mt-1">
                                                            <small class="text-muted">Làm việc từ 8-12 và 13h30-17h30</small>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php elseif (!$isWorkingDay && !$hasLeave): ?>
                                        <div class="text-center py-2 text-muted">
                                            <i class="fas fa-moon fa-lg mb-2 d-block"></i>
                                            <small>Ngày nghỉ</small>
                                        </div>
                                    <?php endif; ?>

                                    <!-- HIỂN THỊ LỊCH HẸN (PHÂN CÔNG) - CHỈ HIỆN KHI KHÔNG CÓ NGHỈ PHÉP ĐÃ DUYỆT -->
                                    <?php if (!$hasLeave || $leaveType != '1'): ?>
                                        <?php
                                        $daySchedules = $scheduleByDay[$dayFormatted] ?? [];
                                        $timeSlots = [
                                            'sang' => ['Sáng', '8:00-12:00', '#e3f2fd'],
                                            'chieu' => ['Chiều', '13:00-17:00', '#fff3cd'],
                                            'toi' => ['Tối', '18:00-21:00', '#ffeaa7'],
                                            'trongngay' => ['Cả ngày', '8:00-17:00', '#d1ecf1']
                                        ];

                                        foreach ($timeSlots as $slotKey => $slotInfo):
                                            $slotSchedules = array_filter($daySchedules, function ($s) use ($slotKey) {
                                                return isset($s['khungGio']) && $s['khungGio'] == $slotKey;
                                            });

                                            if (!empty($slotSchedules)):
                                                ?>
                                                <div class="appointment-slot mb-2 p-2 border rounded"
                                                    style="border-left: 3px solid <?php echo $slotInfo[2]; ?>">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <div>
                                                            <strong class="small"><?php echo $slotInfo[0]; ?></strong>
                                                            <br>
                                                            <small class="text-muted"><?php echo $slotInfo[1]; ?></small>
                                                        </div>
                                                        <span
                                                            class="badge bg-success rounded-pill"><?php echo count($slotSchedules); ?></span>
                                                    </div>

                                                    <div class="mt-2">
                                                        <?php foreach ($slotSchedules as $assignment): ?>
                                                            <div class="appointment-item card mb-2 cursor-pointer"
                                                                onclick="viewOrderDetail(<?php echo $assignment['maDon']; ?>)">
                                                                <div class="card-body p-2">
                                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                                        <span
                                                                            class="badge bg-dark">#<?php echo $assignment['maDon']; ?></span>
                                                                        <span
                                                                            class="badge <?php echo $assignment['noiSuaChua'] == 1 ? 'bg-success' : 'bg-info'; ?>">
                                                                            <i
                                                                                class="fas <?php echo $assignment['noiSuaChua'] == 1 ? 'fa-store' : 'fa-home'; ?> me-1"></i>
                                                                            <?php echo $assignment['noiSuaChua'] == 1 ? 'Cửa hàng' : 'Nhà KH'; ?>
                                                                        </span>
                                                                    </div>
                                                                    <div class="small">
                                                                        <div class="fw-bold">
                                                                            <?php echo htmlspecialchars($assignment['tenThietBi'] ?? 'N/A'); ?>
                                                                        </div>
                                                                        <div class="text-muted">Loại:
                                                                            <?php echo htmlspecialchars($assignment['loai_thietbi'] ?? ''); ?>
                                                                        </div>
                                                                        <div class="mt-1">
                                                                            <i class="fas fa-user text-muted me-1"></i>
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
                                                                        <span
                                                                            class="badge <?php echo ($assignment['trangThai'] ?? '') == 'completed' ? 'bg-success' : 'bg-warning text-dark'; ?>">
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
                                                </div>
                                            <?php endif; endforeach; ?>

                                        <!-- THÔNG BÁO KHÔNG CÓ LỊCH HẸN -->
                                        <?php if (empty($daySchedules) && $isWorkingDay && !empty($workScheduleForDay) && !$hasLeave): ?>
                                            <div class="text-center py-3 text-muted">
                                                <i class="fas fa-coffee fa-lg mb-2 d-block"></i>
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
        <!-- MODAL XIN NGHỈ PHÉP -->
        <div class="modal fade" id="leaveModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-umbrella-beach me-2"></i>
                            Xin Nghỉ Phép
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST">
                        <div class="modal-body">
                            <!-- Ngày bắt đầu -->
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Ngày bắt đầu:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required
                                    min="<?php echo date('Y-m-d'); ?>" onchange="calculateDays()">
                            </div>

                            <!-- Ngày kết thúc -->
                            <div class="mb-3">
                                <label for="end_date" class="form-label">Ngày kết thúc:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required
                                    min="<?php echo date('Y-m-d'); ?>" onchange="calculateDays()">
                            </div>

                            <!-- Số ngày (tự động tính) -->
                            <div class="mb-3">
                                <label class="form-label">Số ngày nghỉ:</label>
                                <div class="form-control-plaintext fw-bold text-primary" id="days_count">0 ngày</div>
                                <input type="hidden" id="total_days" name="total_days">
                            </div>

                            <!-- NOTE QUAN TRỌNG -->
                            <div class="alert alert-warning py-2">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Lưu ý quan trọng:</strong> Hãy đảm bảo các ngày xin nghỉ phép đều có lịch
                                    làm việc.
                                    Nếu không, hãy đăng ký từng ngày riêng lẻ.
                                </small>
                            </div>

                            <!-- Lý do -->
                            <div class="mb-3">
                                <label for="leave_reason" class="form-label">Lý do chi tiết:</label>
                                <textarea class="form-control" id="leave_reason" name="leave_reason" rows="4"
                                    placeholder="Nhập lý do xin nghỉ phép chi tiết..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" name="request_leave" class="btn btn-primary">Gửi Yêu Cầu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            function calculateDays() {
                const startDate = new Date(document.getElementById('start_date').value);
                const endDate = new Date(document.getElementById('end_date').value);

                if (startDate && endDate && startDate <= endDate) {
                    // Tính số ngày (bao gồm cả ngày bắt đầu)
                    const timeDiff = endDate.getTime() - startDate.getTime();
                    const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;

                    document.getElementById('days_count').textContent = dayDiff + ' ngày';
                    document.getElementById('total_days').value = dayDiff;
                } else {
                    document.getElementById('days_count').textContent = '0 ngày';
                    document.getElementById('total_days').value = '0';
                }
            }

            // Set min date for end_date when start_date changes
            document.getElementById('start_date').addEventListener('change', function () {
                const startDate = this.value;
                document.getElementById('end_date').min = startDate;
            });
        </script>

        <?php include __DIR__ . '/../footer.php'; ?>

        <script>
            function viewOrderDetail(orderId) {
                window.location.href = '<?php echo url("KTV/xemChiTietDon"); ?>?id=' + orderId;
            }

            function openLeaveModal() {
                var modal = new bootstrap.Modal(document.getElementById('leaveModal'));
                modal.show();
            }

            // Bootstrap sẽ tự xử lý đóng modal
        </script>

        <style>
            .cursor-pointer {
                cursor: pointer;
            }

            .cursor-pointer:hover {
                transform: translateY(-1px);
                transition: transform 0.2s;
            }

            .card {
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            }

            .card:hover {
                box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            }
        </style>