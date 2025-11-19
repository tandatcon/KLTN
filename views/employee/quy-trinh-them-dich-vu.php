<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../function/khachhang.php';
require_once __DIR__ . '/../../function/dichvu.php';

session_start();

// Kiểm tra đăng nhập và role nhân viên
if (!isset($_SESSION['user_id']) ) {
    $_SESSION['error'] = "Bạn không có quyền truy cập trang này!";
    header("Location: " . url('home')); 
    exit;
}

// Lấy dữ liệu POST
$customer_id         = trim($_POST['customer_id'] ?? '');
$customer_phone      = trim($_POST['sdt'] ?? '');
$customer_name       = trim($_POST['customer_name'] ?? '');
$customer_email      = trim($_POST['customer_email'] ?? '');
$customer_address    = trim($_POST['customer_address'] ?? '');
$booking_datetime    = trim($_POST['booking_datetime'] ?? '');
$problem_description = trim($_POST['problem_description'] ?? '');
$device_types        = $_POST['device_types'] ?? [];
$device_models       = $_POST['device_models'] ?? [];
$device_problems     = $_POST['device_problems'] ?? [];

// Debug: Hiển thị dữ liệu nhận được
error_log("=== DEBUG PROCESS EMPLOYEE BOOKING ===");
error_log("Customer ID: " . $customer_id);
error_log("Customer Phone: " . $customer_phone);
error_log("Customer Name: " . $customer_name);
error_log("Booking DateTime: " . $booking_datetime);
error_log("Device Types: " . implode(', ', $device_types));
error_log("Device Models: " . implode(', ', $device_models));
error_log("Device Problems: " . implode(', ', $device_problems));

// Validate cơ bản
if (empty($customer_id) || empty($customer_phone) || empty($customer_name)) {
    $_SESSION['error'] = "Thông tin khách hàng không hợp lệ!";
    header("Location: " . url('employee/them-dich-vu'));
    exit;
}

if (empty($booking_datetime) || empty($device_types)) {
    $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
    header("Location: " . url('employee/them-dich-vu'));
    exit;
}

// Validate số lượng thiết bị
if (count($device_types) !== count($device_models) || count($device_types) !== count($device_problems)) {
    $_SESSION['error'] = "Thông tin thiết bị không hợp lệ!";
    header("Location: " . url('employee/them-dich-vu'));
    exit;
}

try {
    $khachhang = new KhachHang($db);
    $dichVuService = new DichVuService($db);

    // Kiểm tra lại thông tin khách hàng
    $customerInfo = $khachhang->layKHByID($customer_id);
    if (!$customerInfo) {
        $_SESSION['error'] = "Không tìm thấy thông tin khách hàng!";
        header("Location: " . url('employee/them-dich-vu'));
        exit;
    }

    // Tách ngày và giờ từ booking_datetime
    $booking_date = date('Y-m-d', strtotime($booking_datetime));
    
    // Chuẩn bị danh sách thiết bị theo định dạng hàm cũ
    $danhSachThietBi = [];
    for ($i = 0; $i < count($device_types); $i++) {
        $danhSachThietBi[] = [
            'maThietBi' => $device_types[$i],
            'phienBan' => $device_models[$i] ?? '',
            'motaTinhTrang' => $device_problems[$i] ?? ''
        ];
    }

    // DEBUG: Log danh sách thiết bị
    error_log("📦 Danh sách thiết bị chuẩn bị insert:");
    foreach ($danhSachThietBi as $index => $thietBi) {
        error_log("   Thiết bị " . ($index + 1) . ": " . print_r($thietBi, true));
    }

    // Thêm đơn dịch vụ tại cửa hàng - SỬA THỨ TỰ THAM SỐ
    $maDon = $dichVuService->themDonDichVuTaiCuaHang(
        $customer_id,           // maKH
        $booking_date,          // ngayDat
        1,                      // noiSuaChua (1 = tại cửa hàng)
        $danhSachThietBi,       // danhSachThietBi
        $problem_description,   // ghiChu
        $_SESSION['user_id']    // maNhanVienTaoDon
    );

    error_log("✅ Kết quả tạo đơn: " . ($maDon ? "Thành công - Mã đơn #$maDon" : "Thất bại"));

    if ($maDon) {
        // Xóa session customer_info sau khi đăng ký thành công
        unset($_SESSION['customer_info']);
        unset($_SESSION['search_phone']);

        $_SESSION['success'] = "Đăng ký dịch vụ tại cửa hàng thành công! Mã đơn là #$maDon";
        header("Location: " . url('employee/them-dich-vu'));
        exit;
    } else {
        throw new Exception("Không thể tạo đơn dịch vụ");
    }

} catch (Exception $e) {
    error_log("❌ Lỗi khi xử lý đăng ký dịch vụ: " . $e->getMessage());
    $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại: " . $e->getMessage();
    header("Location: " . url('employee/them-dich-vu'));
    exit;
}