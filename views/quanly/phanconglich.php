<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

$pageTitle = "Phân Công Lịch Làm - TechCare";
include __DIR__ . '/../header.php';

require_once __DIR__ . '/../../models/Employee.php';

$employeeModel = new Employee($db);

// Kiểm tra role - chỉ cho phép quản lý (role 4) truy cập
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 4) {
    header('Location: ' . url('home'));
    exit();
}

// Xử lý phân công lịch làm
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_schedule'])) {
    $employee_id = $_POST['employee_id'];
    $schedule_type = $_POST['schedule_type'];
    
    if (empty($employee_id) || empty($schedule_type)) {
        $error_message = 'Vui lòng chọn nhân viên và lịch làm việc';
    } else {
        $result = $employeeModel->assignWorkSchedule($employee_id, $schedule_type, $_SESSION['user_id']);
        
        if ($result) {
            $success_message = 'Phân công lịch làm việc thành công!';
        } else {
            $error_message = 'Có lỗi xảy ra khi phân công lịch làm việc';
        }
    }
}

// Lấy danh sách nhân viên
$filter = $_GET['filter'] ?? 'all';
$employees = $employeeModel->getEmployeesForSchedule($filter);

// Lấy thống kê
$stats = $employeeModel->getScheduleStatistics();
?>

<div class="container">
    <h1>Phân Công Lịch Làm Việc</h1>
    
    <!-- Thống kê -->
    <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <strong>Thống kê:</strong> 
        Tổng: <?php echo $stats['total_employees']; ?> | 
        Lịch 1: <?php echo $stats['schedule_1_count']; ?> | 
        Lịch 2: <?php echo $stats['schedule_2_count']; ?> | 
        Chưa có lịch: <?php echo $stats['no_schedule_count']; ?>
    </div>

    <!-- Thông báo -->
    <?php if ($success_message): ?>
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <!-- Bộ lọc -->
    <form method="GET" style="margin-bottom: 20px;">
        <label><strong>Lọc theo:</strong></label>
        <select name="filter" onchange="this.form.submit()" style="padding: 5px; margin-left: 10px;">
            <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>Tất cả nhân viên</option>
            <option value="employee" <?php echo $filter === 'employee' ? 'selected' : ''; ?>>Nhân viên</option>
            <option value="technician" <?php echo $filter === 'technician' ? 'selected' : ''; ?>>Kỹ thuật viên</option>
            <option value="no_schedule" <?php echo $filter === 'no_schedule' ? 'selected' : ''; ?>>Chưa có lịch làm</option>
            <option value="schedule_1" <?php echo $filter === 'schedule_1' ? 'selected' : ''; ?>>Đang làm Lịch 1</option>
            <option value="schedule_2" <?php echo $filter === 'schedule_2' ? 'selected' : ''; ?>>Đang làm Lịch 2</option>
        </select>
    </form>

    <!-- Danh sách nhân viên -->
    <h3>Danh sách nhân viên (<?php echo count($employees); ?> kết quả)</h3>
    
    <?php if (empty($employees)): ?>
        <p style="text-align: center; color: #6c757d; padding: 40px;">
            Không tìm thấy nhân viên nào phù hợp với bộ lọc đã chọn
        </p>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #3498db; color: white;">
                    <th style="padding: 12px; text-align: left;">Nhân viên</th>
                    <th style="padding: 12px; text-align: left;">Chức vụ</th>
                    <th style="padding: 12px; text-align: left;">Chuyên môn</th>
                    <th style="padding: 12px; text-align: left;">Lịch hiện tại</th>
                    <th style="padding: 12px; text-align: left;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($employees as $employee): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 12px;">
                        <strong><?php echo htmlspecialchars($employee['name']); ?></strong><br>
                        <small style="color: #666;"><?php echo htmlspecialchars($employee['phone']); ?></small>
                    </td>
                    <td style="padding: 12px;">
                        <?php echo $employeeModel->getRoleText($employee['role']); ?>
                    </td>
                    <td style="padding: 12px;">
                        <?php echo !empty($employee['chuyenMon']) ? htmlspecialchars($employee['chuyenMon']) : '-'; ?>
                    </td>
                    <td style="padding: 12px;">
                        <?php if ($employee['work_schedule'] == 1): ?>
                            <span style="background: #d4edda; color: #155724; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                Lịch 1 (T3-T7)
                            </span>
                        <?php elseif ($employee['work_schedule'] == 2): ?>
                            <span style="background: #cce7ff; color: #004085; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                Lịch 2 (T2-T5,CN)
                            </span>
                        <?php else: ?>
                            <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 3px; font-size: 12px;">
                                Chưa phân công
                            </span>
                        <?php endif; ?>
                    </td>
                    <td style="padding: 12px;">
                        <form method="POST" style="display: flex; gap: 5px;">
                            <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                            <select name="schedule_type" style="padding: 6px; border: 1px solid #ddd; border-radius: 3px;" required>
                                <option value="">-- Chọn lịch --</option>
                                <option value="1" <?php echo $employee['work_schedule'] == 1 ? 'selected' : ''; ?>>Lịch 1 (T3-T7)</option>
                                <option value="2" <?php echo $employee['work_schedule'] == 2 ? 'selected' : ''; ?>>Lịch 2 (T2-T5,CN)</option>
                            </select>
                            <button type="submit" name="assign_schedule" 
                                    style="background: #27ae60; color: white; border: none; padding: 6px 12px; border-radius: 3px; cursor: pointer;">
                                Lưu
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

table {
    background: white;
    border-radius: 5px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

tr:hover {
    background: #f8f9fa;
}
</style>