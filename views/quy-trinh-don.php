<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../function/dichvu.php';
require_once __DIR__ . '/../function/khachhang.php';

session_start();

// === KIỂM TRA ĐĂNG NHẬP ===
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Bạn cần đăng nhập để đặt dịch vụ!";
    header("Location: " . url('dang-nhap'));
    exit;
}

$maKH = $_SESSION['user_id'];
$dichVuService = new DichVuService($db);
$khachhang     = new khachhang($db);

// === LẤY DỮ LIỆU TỪ FORM MỚI ===
$booking_date        = trim($_POST['booking_date'] ?? '');
$maKhungGio          = trim($_POST['maKhungGio'] ?? '');
$problem_description = trim($_POST['problem_description'] ?? '');
$customer_name       = trim($_POST['customer_name'] ?? '');
$customer_phone      = trim($_POST['customer_phone'] ?? '');
$customer_address    = trim($_POST['customer_address'] ?? '');

// MỚI: device_brands[] và device_models[] (maMau)
$device_types   = $_POST['device_types']   ?? [];  // maThietBi
$device_brands  = $_POST['device_brands']  ?? [];  // maHang
$device_models  = $_POST['device_models']  ?? [];  // maMau ← quan trọng nhất
$device_problems= $_POST['device_problems']?? [];

// === VALIDATION ===
$errors = [];

if (empty($customer_name))   $errors[] = "Vui lòng nhập họ tên";
if (empty($customer_phone) || !preg_match('/^(0|\+84)[0-9]{9,10}$/', $customer_phone))
    $errors[] = "Số điện thoại không hợp lệ";
if (empty($customer_address)) $errors[] = "Vui lòng nhập địa chỉ";

if (empty($device_types) || count($device_types) === 0)
    $errors[] = "Vui lòng chọn ít nhất một thiết bị";

foreach ($device_types as $i => $type) {
    if (empty($type))                     $errors[] = "Chọn loại thiết bị " . ($i+1);
    if (empty($device_brands[$i] ?? ''))  $errors[] = "Chọn hãng cho thiết bị " . ($i+1);
    if (empty($device_models[$i] ?? ''))  $errors[] = "Chọn mẫu sản phẩm cho thiết bị " . ($i+1);
    if (empty(trim($device_problems[$i] ?? ''))) 
        $errors[] = "Mô tả tình trạng thiết bị " . ($i+1);
    elseif (strlen(trim($device_problems[$i])) < 10)
        $errors[] = "Mô tả thiết bị " . ($i+1) . " phải ít nhất 10 ký tự";
}

if (empty($booking_date)) $errors[] = "Chọn ngày đặt lịch";
elseif (DateTime::createFromFormat('Y-m-d', $booking_date) < new DateTime('today'))
    $errors[] = "Không đặt lịch ngày trong quá khứ";

if (empty($maKhungGio)) $errors[] = "Chọn khung giờ";

// Nếu có lỗi → trả về form
if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    $_SESSION['form_data'] = $_POST; // giữ lại toàn bộ dữ liệu
    header("Location: " . url('dat-dich-vu'));
    exit;
}

// === CHUẨN BỊ DANH SÁCH THIẾT BỊ THEO ĐỊNH DẠNG MỚI ===
$danhSachThietBi = [];
foreach ($device_types as $i => $maThietBi) {
    $danhSachThietBi[] = [
        'maThietBi'     => (int)$maThietBi,
        'maHang'        => (int)($device_brands[$i] ?? 0),
        'maMau'         => (int)$device_models[$i],           // ← quan trọng: truyền maMau
        'motaTinhTrang' => trim($device_problems[$i])
    ];
}

try {
    // Gọi hàm thêm đơn (đảm bảo hàm này nhận đúng tham số maMau)
    $maDon = $dichVuService->themDonDichVu(
        $maKH,
        $booking_date,
        $maKhungGio,
        $customer_address,
        $danhSachThietBi,        // danh sách có maMau rồi
        $problem_description      // ghi chú thêm
    );

    if ($maDon) {
        $_SESSION['success'] = "Đặt lịch thành công!<br>Mã đơn: <strong>#{$maDon}</strong><br>Nhân viên sẽ liên hệ xác nhận sớm nhất.";
        unset($_SESSION['form_data']);
        header("Location: " . url('don-cua-toi'));
        exit;
    } else {
        throw new Exception("Không tạo được đơn hàng");
    }

} catch (Exception $e) {
    error_log("Lỗi đặt dịch vụ: " . $e->getMessage());
    $_SESSION['error'] = "Hệ thống lỗi, vui lòng thử lại sau.<br>(Chi tiết: " . $e->getMessage() . ")";
    $_SESSION['form_data'] = $_POST;
    header("Location: " . url('dat-dich-vu'));
    exit;
}