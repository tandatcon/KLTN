<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Customer.php';

session_start();

// Kiểm tra đăng nhập và role nhân viên
if (!isset($_SESSION['user_id']) || $_SESSION['role'] == 1) {
    $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
    header("Location: " . url('home')); 
    exit;
}

// Lấy dữ liệu POST
$customer_id         = trim($_POST['customer_id'] ?? '');
$customer_phone      = trim($_POST['customer_phone'] ?? '');
$customer_name       = trim($_POST['customer_name'] ?? '');
$customer_email      = trim($_POST['customer_email'] ?? '');
$service_type        = $_POST['service_type'] ?? [];
$booking_date        = trim($_POST['booking_date'] ?? '');
$booking_time        = trim($_POST['booking_time'] ?? '');
$problem_description = trim($_POST['problem_description'] ?? '');
$device_types        = $_POST['device_types'] ?? [];
$device_models       = $_POST['device_models'] ?? [];
$device_problems     = $_POST['device_problems'] ?? [];

// Validate cơ bản
if (empty($customer_id) || empty($customer_phone) || empty($customer_name)) {
    $_SESSION['error'] = "Thông tin khách hàng không hợp lệ!";
    header("Location: " . url('employee/themdichvu'));
    exit;
}

if (empty($booking_date) || empty($booking_time) || empty($device_types)) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
    header("Location: " . url('employee/themdichvu'));
    exit;
}

try {
    $userModel = new User($db);
    $customerModel = new Customer($db);

    // Kiểm tra lại thông tin khách hàng
    // $customerInfo = $customerModel->findById($customer_id);
    // if (!$customerInfo) {
    //     $_SESSION['error'] = "Không tìm thấy thông tin khách hàng!";
    //     header("Location: " . url('employee/themdichvu'));
    //     exit;
    // }

    // Thêm đơn dịch vụ với ID khách hàng
    $maDon = $userModel->themDonDichVu(
        $customer_id,           // Sử dụng customer_id thay vì user_id
        $booking_date,
        $booking_time,
        $problem_description,
        '',                     // Địa chỉ không cần vì mặc định tại cửa hàng
        $device_types,
        $device_models,
        $device_problems,
        $service_type,          // Thêm hình thức dịch vụ
        $_SESSION['user_id']
            // ID nhân viên tạo đơn
    );

    // Xóa session customer_info sau khi đăng ký thành công
    unset($_SESSION['customer_info']);

    $_SESSION['success'] = "Đăng ký dịch vụ thành công! Mã đơn là #$maDon";
    header("Location: " . url('employee/themdichvu'));
    //header("Location: " . url('employee/donhang'));
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại: " . $e->getMessage();
    header("Location: " . url('employee/themdichvu'));
    exit;
}