<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../function/dichvu.php';
require_once __DIR__ . '/../function/khachhang.php';

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để đặt dịch vụ!";
    header("Location: " . url('login'));
    exit;
}

$maKH = $_SESSION['user_id'];

// Khởi tạo đối tượng DichVuService
$dichVuService = new DichVuService($db);
$khachhang = new khachhang($db);

// Lấy dữ liệu POST
$booking_date = trim($_POST['booking_date'] ?? '');
$booking_time = trim($_POST['maKhungGio'] ?? '');
$problem_description = trim($_POST['problem_description'] ?? '');
$customer_address = trim($_POST['customer_address'] ?? '');
$customer_name = trim($_POST['customer_name'] ?? '');
$customer_phone = trim($_POST['customer_phone'] ?? '');
$device_types = $_POST['device_types'] ?? [];
$device_models = $_POST['device_models'] ?? [];
$device_problems = $_POST['device_problems'] ?? [];
$service_type = '0'; // 0 = sửa tại nhà
$is_immediate_service = 0; // Luôn là 0 vì chỉ có đặt lịch hẹn

// DEBUG: Kiểm tra dữ liệu nhận được
error_log("=== DEBUG PROCESS BOOKING ===");
error_log("Booking date: " . $booking_date);
error_log("Booking time: " . $booking_time);
error_log("Customer name: " . $customer_name);
error_log("Customer phone: " . $customer_phone);
error_log("Customer address: " . $customer_address);
error_log("Device types count: " . count($device_types));
error_log("Device models count: " . count($device_models));
error_log("Device problems count: " . count($device_problems));

// VALIDATE DỮ LIỆU
$errors = [];

// Validate thông tin khách hàng
if (empty($customer_name)) {
    $errors[] = "Vui lòng nhập họ và tên";
}

if (empty($customer_phone)) {
    $errors[] = "Vui lòng nhập số điện thoại";
} elseif (!preg_match('/^(0|\+84)[0-9]{9,10}$/', $customer_phone)) {
    $errors[] = "Số điện thoại không hợp lệ";
}

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
    
    foreach ($device_models as $index => $model) {
        if (empty(trim($model))) {
            $errors[] = "Vui lòng nhập thông tin phiên bản/thương hiệu cho thiết bị " . ($index + 1);
        } elseif (strlen(trim($model)) < 2) {
            $errors[] = "Thông tin thiết bị " . ($index + 1) . " phải có ít nhất 2 ký tự";
        }
    }
    
    foreach ($device_problems as $index => $problem) {
        if (empty(trim($problem))) {
            $errors[] = "Vui lòng mô tả tình trạng hư hỏng cho thiết bị " . ($index + 1);
        } elseif (strlen(trim($problem)) < 10) {
            $errors[] = "Mô tả tình trạng thiết bị " . ($index + 1) . " phải có ít nhất 10 ký tự";
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
}

// Nếu có lỗi, quay lại form
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    $_SESSION['form_data'] = [
        'customer_name' => $customer_name,
        'customer_phone' => $customer_phone,
        'customer_address' => $customer_address,
        'booking_date' => $booking_date,
        'booking_time' => $booking_time,
        'problem_description' => $problem_description,
        'device_types' => $device_types,
        'device_models' => $device_models,
        'device_problems' => $device_problems
    ];
    header("Location: " . url('dat-dich-vu'));
    exit;
}

try {
    

    // Gọi phương thức thêm đơn dịch vụ từ DichVuService
    // Format danh sách thiết bị
$danhSachThietBi = [];
for ($i = 0; $i < count($device_types); $i++) {
    $danhSachThietBi[] = [
        'maThietBi' => $device_types[$i],
        'phienBan' => $device_models[$i],
        'motaTinhTrang' => $device_problems[$i]
    ];
}

// Gọi hàm với thứ tự tham số mới
$maDon = $dichVuService->themDonDichVu(
    $maKH,
    $booking_date,
    $booking_time,
    $customer_address, // $noiSuaChua
    $danhSachThietBi,  // $danhSachThietBi
    $problem_description // $ghiChu (tham số tùy chọn)
);
    

    if ($maDon) {
        // Thông báo thành công
        $ngay_hien = date('d/m/Y', strtotime($booking_date));
        $_SESSION['success'] = "✅ <strong>Đặt lịch sửa chữa thành công!</strong><br>
                               Mã đơn: <strong>#$maDon</strong><br>
                               Nhân viên sẽ liên hệ bạn trong vòng 30 phút để xác nhận lịch hẹn.";

        unset($_SESSION['form_data']);
        header("Location: " . url('don-cua-toi'));
        exit;
    } else {
        throw new Exception("Không thể tạo đơn dịch vụ");
    }

} catch (Exception $e) {
    error_log("Lỗi đặt lịch: " . $e->getMessage());
    $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại: " . $e->getMessage();
    $_SESSION['form_data'] = [
        'customer_name' => $customer_name,
        'customer_phone' => $customer_phone,
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