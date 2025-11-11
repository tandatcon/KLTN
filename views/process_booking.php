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
$booking_date = trim($_POST['booking_date'] ?? '');
$booking_time = trim($_POST['booking_time'] ?? '');
$problem_description = trim($_POST['problem_description'] ?? '');
$customer_address = trim($_POST['customer_address'] ?? '');
$device_types = $_POST['device_types'] ?? [];
$device_models = $_POST['device_models'] ?? [];
$device_problems = $_POST['device_problems'] ?? [];
$service_type = '0'; // 0 = sửa tại nhà
$is_immediate_service = 0; // Luôn là 0 vì chỉ có đặt lịch hẹn

// DEBUG: Kiểm tra dữ liệu nhận được
error_log("=== DEBUG PROCESS BOOKING ===");
error_log("Booking date: " . $booking_date);
error_log("Booking time: " . $booking_time);
error_log("Customer address: " . $customer_address);
error_log("Device types count: " . count($device_types));
error_log("Device models count: " . count($device_models));
error_log("Device problems count: " . count($device_problems));

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

// Validate ngày và giờ đặt lịch
if (empty($booking_date)) {
    $errors[] = "Vui lòng chọn ngày đặt lịch";
} else {
    $selected_date = DateTime::createFromFormat('Y-m-d', $booking_date);
    $today = new DateTime('today');
    
    if ($selected_date < $today) {
        $errors[] = "Không thể đặt lịch cho ngày trong quá khứ";
    }
}

if (empty($booking_time)) {
    $errors[] = "Vui lòng chọn khung giờ đặt lịch";
} else {
    // Validate khung giờ hợp lệ (1 = sáng, 2 = chiều)
    if (!in_array($booking_time, ['1', '2'])) {
        $errors[] = "Khung giờ không hợp lệ";
    }
    
    // Kiểm tra nếu đặt lịch cho ngày hôm nay
    $selected_date = DateTime::createFromFormat('Y-m-d', $booking_date);
    $today = new DateTime('today');
    $current_hour = date('H');
    
    if ($selected_date == $today) {
        if ($booking_time == '1' && $current_hour >= 12) {
            $errors[] = "Không thể đặt ca sáng cho ngày hôm nay sau 12:00";
        }
        if ($booking_time == '2' && $current_hour >= 18) {
            $errors[] = "Không thể đặt ca chiều cho ngày hôm nay sau 18:00";
        }
    }
}

// Kiểm tra số lượng slot available (nếu cần)
require_once __DIR__ . '/../controllers/BookingController.php';
$bookingController = new BookingController($db);
$availableSlots = $bookingController->getAvailableSlots();

if (isset($availableSlots[$booking_date][$booking_time])) {
    $slot = $availableSlots[$booking_date][$booking_time];
    if ($slot['available'] <= 0 || $slot['disabled']) {
        $errors[] = "Khung giờ này đã hết slot. Vui lòng chọn khung giờ khác!";
    }
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
        'device_problems' => $device_problems
    ];
    header("Location: " . url('datdichvu'));
    exit;
}

try {
    //$userModel = new User($db);

    // Gọi phương thức thêm đơn dịch vụ
    $maDon = $bookingController->themDonDichVu(
        $maKH,
        $booking_date,
        $booking_time, // Giờ đặt (1 = sáng, 2 = chiều)
        $problem_description,
        $customer_address,
        $device_types,
        $device_models,
        $device_problems,
        $service_type,
        $is_immediate_service
    );

    // Thông báo thành công
    $ngay_hien = date('d/m/Y', strtotime($booking_date));
    $thoi_gian = getTimeSlotText($booking_time);
    
    $_SESSION['success'] = "✅ <strong>Đặt lịch sửa chữa thành công!</strong><br>
                           Mã đơn: <strong>#$maDon</strong><br>
                           Thời gian: <strong>$ngay_hien - $thoi_gian</strong><br>
                           Địa chỉ: <strong>$customer_address</strong>";

    unset($_SESSION['form_data']);
    //header("Location: " . url('my_orders'));
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
        'device_problems' => $device_problems
    ];
    header("Location: " . url('datdichvu'));
    exit;
}

function getTimeSlotText($time_slot) {
    $time_slots = [
        '1' => 'Ca sáng (7:30 - 12:00)',
        '2' => 'Ca chiều (13:00 - 18:00)'
    ];
    return $time_slots[$time_slot] ?? 'Không xác định';
}