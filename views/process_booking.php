<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../models/User.php';

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để đặt dịch vụ!";
    header("Location: " . url('login'));
    exit;
}

$maKH = $_SESSION['user_id'];

// Lấy dữ liệu POST
$schedule_type = trim($_POST['schedule_type'] ?? '');
$booking_date = trim($_POST['booking_date'] ?? '');
$booking_time = trim($_POST['booking_time'] ?? '');
$immediate_date = trim($_POST['immediate_date'] ?? '');
$immediate_time = trim($_POST['immediate_time'] ?? '');
$problem_description = trim($_POST['problem_description'] ?? '');
$customer_address = trim($_POST['customer_address'] ?? '');
$device_types = $_POST['device_types'] ?? [];
$device_models = $_POST['device_models'] ?? [];
$device_problems = $_POST['device_problems'] ?? [];
$service_type = '0'; // 0 = sửa tại nhà

// VALIDATE DỮ LIỆU
$errors = [];

// Validate địa chỉ
if (empty($customer_address)) {
    $errors[] = "Vui lòng nhập địa chỉ";
}

// Validate thiết bị
if (empty($device_types)) {
    $errors[] = "Vui lòng chọn ít nhất một thiết bị cần sửa chữa";
} else {
    foreach ($device_types as $index => $device_type) {
        if (empty($device_type)) {
            $errors[] = "Vui lòng chọn loại thiết bị cho thiết bị " . ($index + 1);
        }
    }
    
    foreach ($device_problems as $index => $problem) {
        if (empty(trim($problem))) {
            $errors[] = "Vui lòng mô tả tình trạng hư hỏng cho thiết bị " . ($index + 1);
        }
    }
}

// Xác định loại dịch vụ và validate thời gian
$is_immediate_service = 0; // Mặc định là đặt lịch hẹn

if ($schedule_type === 'today') {
    // Đặt lịch hôm nay - sử dụng ngày giờ mặc định
    $is_immediate_service = 1;
    $final_booking_date = $immediate_date; // Ngày hôm nay
    $final_booking_time = $immediate_time; // Khung giờ chiều
    
    // Validate ngày hôm nay
    if (empty($final_booking_date)) {
        $errors[] = "Lỗi hệ thống: Không xác định được ngày đặt lịch";
    }
    
} else if ($schedule_type === 'appointment') {
    // Đặt lịch hẹn - validate ngày giờ
    $is_immediate_service = 0;
    $final_booking_date = $booking_date;
    $final_booking_time = $booking_time;
    
    if (empty($final_booking_date)) {
        $errors[] = "Vui lòng chọn ngày đặt lịch";
    } else {
        $selected_date = DateTime::createFromFormat('Y-m-d', $final_booking_date);
        $tomorrow = new DateTime('tomorrow');
        
        if ($selected_date < $tomorrow) {
            $errors[] = "Ngày đặt lịch phải từ ngày mai trở đi";
        }
    }
    
    if (empty($final_booking_time)) {
        $errors[] = "Vui lòng chọn khung giờ đặt lịch";
    }
} else {
    $errors[] = "Vui lòng chọn loại đặt lịch";
}

// Nếu có lỗi, quay lại form
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    $_SESSION['form_data'] = [
        'customer_address' => $customer_address,
        'booking_date' => $booking_date,
        'booking_time' => $booking_time,
        'problem_description' => $problem_description,
        'device_types' => $device_types,
        'device_models' => $device_models,
        'device_problems' => $device_problems,
        'schedule_type' => $schedule_type
    ];
    header("Location: " . url('datdichvu'));
    exit;
}

try {
    $userModel = new User($db);

    // Gọi phương thức thêm đơn dịch vụ
    $maDon = $userModel->themDonDichVu(
        $maKH,
        $final_booking_date,    // Sử dụng ngày đã xác định
        $final_booking_time,    // Sử dụng giờ đã xác định
        $problem_description,
        $customer_address,
        $device_types,
        $device_models,
        $device_problems,
        $service_type,
        $is_immediate_service   // 1 = sửa ngay, 0 = đặt lịch hẹn
    );

    // Thông báo thành công
    if ($is_immediate_service) {
        $_SESSION['success'] = "✅ <strong>Đã ghi nhận yêu cầu sửa chữa ngay!</strong><br>
                               Mã đơn: <strong>#$maDon</strong><br>
                               Kỹ thuật viên sẽ liên hệ với bạn trong vòng <strong>15-30 phút</strong>.";
    } else {
        $ngay_hien = date('d/m/Y', strtotime($final_booking_date));
        $thoi_gian = getTimeSlotText($final_booking_time);
        $_SESSION['success'] = "✅ <strong>Đặt lịch sửa chữa thành công!</strong><br>
                               Mã đơn: <strong>#$maDon</strong><br>
                               Thời gian: <strong>$ngay_hien - $thoi_gian</strong>";
    }

    unset($_SESSION['form_data']);
    header("Location: " . url('my_orders'));
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại: " . $e->getMessage();
    $_SESSION['form_data'] = [
        'customer_address' => $customer_address,
        'booking_date' => $booking_date,
        'booking_time' => $booking_time,
        'problem_description' => $problem_description,
        'device_types' => $device_types,
        'device_models' => $device_models,
        'device_problems' => $device_problems,
        'schedule_type' => $schedule_type
    ];
    header("Location: " . url('datdichvu'));
    exit;
}

function getTimeSlotText($time_slot) {
    $time_slots = [
        'sang' => 'Sáng (8:00 - 11:00)',
        'chieu' => 'Chiều (13:00 - 17:00)', 
        'toi' => 'Tối (18:00 - 21:00)'
    ];
    return $time_slots[$time_slot] ?? 'Không xác định';
}